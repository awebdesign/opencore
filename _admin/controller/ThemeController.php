<?php
require_once DIR_SYSTEM . 'awebcore/Loader.php';

class ThemeController extends CoreUi
{

    static $booted = false;
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

}