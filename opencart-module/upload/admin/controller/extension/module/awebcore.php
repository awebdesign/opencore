<?php

require_once realpath(__DIR__ . '/../../../../') . '/core/Startup.php';

use AwebCore\Traits\Installer;
use AwebCore\Traits\OcCore;

class ControllerExtensionModuleAwebcore extends Controller
{
    use OcCore, Installer;

    static $booted = false;

    private $errors = [];

    public function index()
    {
        $this->load->language('extension/module/awebcore');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/awebcore', 'token=' . $this->session->data['token'], true)
        );

        $this->checkInstalation();
        if (empty($this->errors)) {
            $data['success_message'] = $this->language->get('text_success');
        } else {
            $data['error_instalation_failure'] = $this->language->get('error_instalation_failure');
            $data['errors'] = $this->errors;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/awebcore', $data));
    }

    public function install()
    {
        $this->installHtaccess();

        $this->installOcmod('awebcore');
        $this->refreshOcmod();

        $this->installEvent('awebcore_admin_menu', 'startup/awebcore/before_view', 'admin/view/*/before');
        $this->installEvent('awebcore_admin_before_controller', 'startup/awebcore/before_controller', 'admin/controller/*/before');
        $this->installEvent('awebcore_catalog_before_controller', 'startup/awebcore/before_controller', 'catalog/controller/*/before');

        $this->addPermissions('extension/module/awebcore', ['access', 'modify']);
        $this->addPermissions('core/*', ['access', 'modify']);

        $this->session->data['success'] = $this->language->get('text_success');
    }

    public function uninstall()
    {
        $this->removeHtaccess();

        $this->removeOcmod('awebcore');
        $this->refreshOcmod();

        $this->removeEvent('awebcore_admin_menu');
        $this->removeEvent('awebcore_admin_before_controller');
        $this->removeEvent('awebcore_catalog_before_controller');

        $this->removePermissions('extension/module/awebcore', ['access', 'modify']);
        $this->removePermissions('core/*', ['access', 'modify']);

        $this->session->data['success'] = $this->language->get('text_success');

        if (isOc3()) {
            $route = 'marketplace/extension';
        } else {
            $route = 'extension/extension';
        }

        $location = "index.php?route={$route}&" . $this->getTokenStr() . '&type=module';
        die("<script>window.location.href = '{$location}';</script>");
    }

    private function checkInstalation()
    {
        /*
        * Server Requirements
        * PHP >= 5.6.4
        * OpenSSL PHP Extension
        * PDO PHP Extension
        * Mbstring PHP Extension
        */

        //will fail if the before_controller will not be triggered
        if (!class_exists('ControllerStartupAwebcore')) {
            $this->errors[] = $this->language->get('error_router');
        }

        //TODO: need to check event entry/status for awebcore_catalog_before_controll & awebcore_admin_menu

        $this->load->model('extension/modification');
        $modification = $this->model_extension_modification->getModificationByCode('AwebCore');

        if (empty($modification)) {
            $this->errors[] = $this->language->get('error_modification_entry');
        } elseif (empty($modification['status'])) {
            $this->errors[] = $this->language->get('error_modification_status');
        } elseif (empty($modification['xml'])) {
            $this->errors[] = $this->language->get('error_modification_empty_xml');
        }

        //TODO: check if awebcore menu event is installed!
        if (!file_exists(DIR_APPLICATION . '.htaccess')) {
            $this->errors[] = $this->language->get('error_htaccess_not_found');
        }
    }
}
