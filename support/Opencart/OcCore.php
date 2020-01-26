<?php
/*
 * Created on Tue Dec 12 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support\OpenCart;

trait OcCore
{
    public function removeIndexAndRouteParam($url)
    {
        if (defined('OPENCORE_DISABLED')) {
            return $url;
        }

        if (strstr($url, 'index.php?route=')) {
            $url = str_replace('index.php?route=common/home', '', $url); //replace home url for catalog
            $url = str_replace('&amp;', '&', $url);
            $url = str_replace('index.php?route=', '', $url);

            if (!strstr($url, '?')) {
                $url = preg_replace('/&/', '?', $url, 1);
            }
        }
        return $url;
    }

    public function routeCatalogSeoUrl()
    {
        /**
         * Check for Ceo Mega Packe module
         */
        if (!$this->config->get('smp_is_install')) {

            if (defined('OPENCORE_DISABLED')) {
                return true;
            }
            /**
             * check if not found, maybe the page exists in Laravel system
             */
            if (isset($this->request->get['_route_']) && isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
                $newRoute = $this->request->get['_route_'];
                $this->request->get['route'] = $newRoute;
                $this->request->has_route[$newRoute] = true;
            }
        }
    }

    public function isRouted($route)
    {
        return !defined('OPENCORE_DISABLED') && !empty($this->request->has_route[$route]);
    }

    public function getTokenStr()
    {
        if (isOc3()) {
            $token_str = 'user_token=' . $this->session->data['user_token'];
        } else {
            $token_str = 'token=' . $this->session->data['token'];
        }

        return $token_str;
    }

    public function getOcModel($name)
    {
        $instance_name = 'model_' . str_replace('/', '_', $name);
        if (!$this->registry->has($instance_name)) {
            $this->load->model($name);
        }

        return $this->registry->get($instance_name);
    }

    public function addPermissions($path, $permissions = ['access', 'modify'])
    {
        //add new permissions
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        $this->load->model('user/user_group');
        foreach ($permissions as $permission) {
            $this->model_user_user_group->addPermission($this->user->getGroupId(), $permission, $path);
        }
    }

    public function removePermissions($path, $permissions = ['access', 'modify'])
    {
        //add new permissions
        $permissions = is_array($permissions) ? $permissions : [$permissions];

        $this->load->model('user/user_group');
        foreach ($permissions as $permission) {
            $this->model_user_user_group->removePermission($this->user->getGroupId(), $permission, $path);
        }
    }
}
