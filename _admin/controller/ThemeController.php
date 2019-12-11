<?php
require_once DIR_SYSTEM . 'awebcore/Loader.php';

class ThemeController extends CoreUi
{
    use Installer, Errors, Input;

    static $booted = false;

	public function getToken()
	{
		if(isOc3()) {
            $token_str = 'user_token=' . $this->session->data['user_token'];            
        } else {
            $token_str = 'token=' . $this->session->data['token'];
		}
		
		return $token_str;
	}

    public function index()
    {        
        $this->loadTranslation('colorix');

		$this->document->setTitle($this->data['heading_title']);

        $this->load->model('localisation/language');

        $this->store_id = Input::get_post('store_id', 0);

		if(isOc3()) {
            $data['user_token'] = $this->session->data['user_token'];
        } else {
            $data['token'] = $this->session->data['token'];
		}

		if (Input::post() && $this->validate()) {
			$this->saveSettings();

			$this->session->data['success'] = $this->data['text_success'];

			$this->response->redirect($this->url->link('extension/theme/colorix', $this->getToken(), true));
		}

        $data['version'] = $this->engineVersion;

        $data['store_id'] = Input::get_post('store_id', 0);

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();

        //if edit
		if (!Input::post()) {
            $data = array_merge_recursive($data, $this->setConfig()->getConfig());
        } else {
            $data = array_merge_recursive($data, Input::post());
        }

        if(isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = false;
        }

        $data['errors'] = $this->getErrors();
        $data['action'] = $this->url->link('extension/theme/colorix', $this->getToken() . '&store_id=' . Input::get('store_id'), true);
        if(isOc3()){
            $data['cancel'] = $this->url->link('marketplace/extension', $this->getToken() . '&type=theme', true);
        } else {
            $data['cancel'] = $this->url->link('extension/extension', $this->getToken() . '&type=theme', true);
        }
        $data['export'] =  $this->url->link('extension/theme/awebcore/export', $this->getToken() , true);
        $data['import'] = $this->url->link('extension/theme/awebcore/import', $this->getToken() , true);

        $data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'redirect'     => $this->url->link('extension/theme/colorix', $this->getToken(). '&store_id=0', true)
        );

        $this->load->model('setting/store');
        $results = $this->model_setting_store->getStores();
		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'redirect'     => $this->url->link('extension/theme/colorix', $this->getToken() . '&store_id=' . $result['store_id'], true)
			);
        }

        $this->load->model('tool/image');
        if ($data['theme']['background_image'] && is_file(DIR_IMAGE . $data['theme']['background_image'])) {
            $data['background_image_thumb'] = $data['theme']['background_image'];
        } else {
            $data['background_image_thumb'] = 'no_image.png';
        }
        $data['background_image_thumb'] = $this->model_tool_image->resize($data['background_image_thumb'], 100, 100);

        if ($data['theme']['sticky_logo'] && is_file(DIR_IMAGE . $data['theme']['sticky_logo'])) {
            $data['sticky_logo_thumb'] = $data['theme']['sticky_logo'];
        } else {
            $data['sticky_logo_thumb'] = 'no_image.png';
        }
        $data['sticky_logo_thumb'] = $this->model_tool_image->resize($data['sticky_logo_thumb'], 100, 100);

        //check for custom css files
        $custom_css = glob(DIR_CATALOG . 'view/theme/awebcore/css/stylesheet_custom*.css');
        $custom_css = array_map(function($file) {
            if(preg_match('~(.*)/stylesheet_([^\/]+)\.css~', $file, $match)) {
                return $match[2];
            } else {
                return false;
            }
        }, $custom_css);        
        $data['custom_css'] = array_filter($custom_css);

        $this->getOcControllers([
            'header' => 'common/header',
            'column_left' => 'common/column_left',
            'footer' => 'common/footer'
        ])->view('colorix', $data);        
    }

    public function export()
    {
        // Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/theme/colorix')) {
            $this->response->redirect($this->url->link('error/permission', $this->getToken() . $url, true));            
            exit();
        }

        $json = $this->exportSettings();

        $file = 'colorix_export_' . date('Y_m_d_h_i_s') . '.json';\
        
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename = $file");
        header('Content-type: application/json');
        
        echo json_encode( $json );
    }

    public function import() {
        $this->loadTranslation('colorix');
        
        $allowed_tables = ['colorix_setting', 'event', 'extension', 'setting', 'module'];

        $json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/theme/colorix')) {
			$json['error'] = $this->data['error_permission'];
        }
        
        if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->data['error_upload_' . $this->request->files['file']['error']];
                }

				// Sanitize the filename
				$filename = html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8');

                //check file extenssion
				if (strtolower(substr(strrchr($filename, '.'), 1)) != 'json' ) {
					$json['error'] = $this->data['error_filetype'];
				}

				if ($this->request->files['file']['type'] != 'application/json') {
					$json['error'] = $this->data['error_filetype'];
				}

                if(!$json) {
                    // Check to see if any PHP files are trying to be uploaded
                    $content = file_get_contents($this->request->files['file']['tmp_name']);
                    
                    if(empty($content)) {
                        $json['error'] = $this->data['error_empty_import_file'];
                    } elseif (preg_match('/\<\?php/i', $content)) {
                        $json['error'] = $this->data['error_filetype'];
                    }
                    
                    $content = json_decode($content, true);
                    if(json_last_error()) {
                        $json['error'] = $this->data['error_wrong_import_file_format'];
                    }

                    if(!$json) {
                        $tables = array_keys($content);
                        foreach($tables as $table) {
                            if(!in_array($table, $allowed_tables)) {
                                $json['error'] = sprintf($this->data['error_wrong_import_file_table'], $table);
                                break;
                            }
                        }
                    }                    
                }
			} else {
				$json['error'] = $this->data['error_upload'];
			}
		}

		if (!$json) {   
            try {
                $this->importSettings($content, isset($this->request->post['import_extensions']));
            } catch(Exception $e) {
                $json['error'] = sprintf($this->data['error_wrong_import_file_sql'], $table);
            }

            if(!$json) {
                $json['success'] = $this->data['text_upload'];
            }			
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function install()
    {
        $this->installEngine();

        //add permissions
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/theme/colorix');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/theme/colorix');
		
		//maybe load language files ?
        $this->session->data['success'] = $this->data['text_success'];
		
		if(isOc3()){
            $this->response->redirect($this->url->link('marketplace/extension', $this->getToken() . '&type=theme', true));
        } else {
            $this->response->redirect($this->url->link('extension/extension', $this->getToken() . '&type=theme', true));
        }
    }

    public function uninstall()
    {
        $this->uninstallEngine();

        //add permissions
        $this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/theme/colorix');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/theme/colorix');

        //maybe load language files ?
        $this->session->data['success'] = $this->data['text_success'];

        if(isOc3()){
            $this->response->redirect($this->url->link('marketplace/extension', $this->getToken() . '&type=theme', true));
        } else {
            $this->response->redirect($this->url->link('extension/extension', $this->getToken() . '&type=theme', true));
        }
        
    }

    /* start admin events for Models & Views */
    //TO DO
    /*public function afterLoadingModels($route)
    {
        return;
    }*/

    public function beforeLoadingController()
    {
        if (self::$booted) {
            return;
        }

        $this->event->register('view/*/before', new Action('extension/theme/awebcore/beforeLoadingView'));

        self::$booted = true;
    }

    public function beforeLoadingView($route, &$data)
    {
        if ($route == 'common/column_left') {
            if(isOc3()) {
                $data['menus'][] = [
                        'id'       => 'colorix-menu',
                        'icon'	   => 'fa-code',
                        'name'	   => 'Colorix UI',
                        'href'     => $this->url->link('extension/theme/colorix', 'user_token=' . Input::get('user_token') . '&store_id=' . (int)$this->config->get('config_store_id')),
                        'children' => []
                ];
            } else {
                $data['menus'][] = [
                    'id'       => 'colorix-menu',
                    'icon'	   => 'fa-code',
                    'name'	   => 'Colorix UI',
                    'href'     => $this->url->link('extension/theme/colorix', 'token=' . Input::get('token') . '&store_id=' . (int)$this->config->get('config_store_id')),
                    'children' => []
                ];
            }
        }
    }

    /* private and protected methods starts here */
    protected function validate()
    {
        $errors = [];
		if (!$this->user->hasPermission('modify', 'extension/theme/colorix')) {
			$errors['warning'] = $this->data['error_permission'];
		} else {

            if (!Input::post('theme.product_limit') || !is_numeric(Input::post('theme.product_limit'))) {
                $errors['product_limit'] = $this->data['error_limit'];
            }

            if (!Input::post('theme.product_description_length') || !is_numeric(Input::post('theme.product_description_length'))) {
                $errors['product_description_length'] = $this->data['error_limit'];
            }

            if (!Input::post('theme.image_category_width') || !Input::post('theme.image_category_height') || !is_numeric(Input::post('theme.image_category_width')) || !is_numeric(Input::post('theme.image_category_height'))) {
                $errors['image_category'] = $this->data['error_image_category'];
            }

            if (!Input::post('theme.image_thumb_width') || !Input::post('theme.image_thumb_height') || !is_numeric(Input::post('theme.image_thumb_width')) || !is_numeric(Input::post('theme.image_thumb_height'))) {
                $errors['image_thumb'] = $this->data['error_image_thumb'];
            }

            if (!Input::post('theme.image_popup_width') || !Input::post('theme.image_popup_height') || !is_numeric(Input::post('theme.image_popup_width')) || !is_numeric(Input::post('theme.image_popup_height'))) {
                $errors['image_popup'] = $this->data['error_image_popup'];
            }

            if (!Input::post('theme.image_product_width') || !Input::post('theme.image_product_height') || !is_numeric(Input::post('theme.image_product_width')) || !is_numeric(Input::post('theme.image_product_height'))) {
                $errors['image_product'] = $this->data['error_image_product'];
            }

            if (!Input::post('theme.image_additional_width') || !Input::post('theme.image_additional_height') || !is_numeric(Input::post('theme.image_additional_width')) || !is_numeric(Input::post('theme.image_additional_height'))) {
                $errors['image_additional'] = $this->data['error_image_additional'];
            }

            if (!Input::post('theme.image_related_width') || !Input::post('theme.image_related_height') || !is_numeric(Input::post('theme.image_related_width')) || !is_numeric(Input::post('theme.image_related_height'))) {
                $errors['image_related'] = $this->data['error_image_related'];
            }

            if (!Input::post('theme.image_compare_width') || !Input::post('theme.image_compare_height') || !is_numeric(Input::post('theme.image_compare_width')) || !is_numeric(Input::post('theme.image_compare_height'))) {
                $errors['image_compare'] = $this->data['error_image_compare'];
            }

            if (!Input::post('theme.image_wishlist_width') || !Input::post('theme.image_wishlist_height') || !is_numeric(Input::post('theme.image_wishlist_width')) || !is_numeric(Input::post('theme.image_wishlist_height'))) {
                $errors['image_wishlist'] = $this->data['error_image_wishlist'];
            }

            if (!Input::post('theme.image_cart_width') || !Input::post('theme.image_cart_height') || !is_numeric(Input::post('theme.image_cart_width')) || !is_numeric(Input::post('theme.image_cart_height'))) {
                $errors['image_cart'] = $this->data['error_image_cart'];
            }

            if (!Input::post('theme.image_location_width') || !Input::post('theme.image_location_height') || !is_numeric(Input::post('theme.image_location_width')) || !is_numeric(Input::post('theme.image_location_height'))) {
                $errors['image_location'] = $this->data['error_image_location'];
            }

            if (!Input::post('theme.image_quality') || !is_numeric(Input::post('theme.image_quality'))) {
                $errors['image_quality'] = $this->data['error_image_quality'];
            }

            if($errors) {
                $errors['warning'] = $this->data['error_invalid_form'];
            }
        }

        if($errors) {
            $this->addErrors($errors);
        }

		return !$errors;
    }

    protected function saveSettings()
    {
        $collectData = Input::post();
        $collectData['theme']['currency_sup'] = Input::post('theme.currency_sup', 0);
        $collectData['theme']['quickview'] = Input::post('theme.quickview', 0);
        $collectData['theme']['compare'] = Input::post('theme.compare', 0);
        $collectData['theme']['wishlist'] = Input::post('theme.wishlist', 0);

        $collectData['config']['theme'] = 'colorix';
        foreach($collectData as $code => $values) {
            $this->db->query("DELETE FROM " . DB_PREFIX . strtolower($this->engineName) . "_setting WHERE store_id = '" . $this->store_id . "' AND code = '" . $this->db->escape($code) . "'");

            foreach($values as $key => $value) {
                $serialized = 0;
                if(is_array($value)) {
                    $serialized = 1;
                    $value = json_encode($value);
                }

                $this->db->query("INSERT INTO `" . DB_PREFIX . strtolower($this->engineName) . "_setting` SET `store_id` = '" . $this->store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', `serialized` = '" . $this->db->escape($serialized) . "'");
            }
        }

        $this->changeThemeStatus($this->store_id, $collectData['theme']['status']);
    }
}