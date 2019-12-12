<?php
/*
 * Created on Tue Dec 12 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore\Traits;

trait OcCore
{
    public function routeUrl($url)
    {
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
        if (isset($this->request->get['_route_']) && $this->request->get['route'] == 'error/not_found') {
            $this->request->get['route'] = $this->request->get['_route_'];
        }
    }
}
