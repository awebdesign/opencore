<?php
/*
 * Created on Tue Dec 12 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support;

use Closure;
use Illuminate\Support\Facades\Request;
use Intervention\Image\Facades\Image;

class OcCore
{
    public function getTokenStr()
    {
        return $this->getTokenKey() . '=' . $this->getToken();
    }

    public function getToken()
    {
        return isOc3() ? Request::input('user_token') : Request::input('token');
    }

    public function getTokenKey()
    {
        return (isOc3() ? 'user_token' : 'token');
    }

    public function ImageResize($image, $width, $height, Closure $closure = null)
    {
        if (!is_file(DIR_IMAGE . $image) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $image)), 0, strlen(DIR_IMAGE)) != DIR_IMAGE) {
			$image = 'no_image.jpg';
        }

        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $cacheImage = 'cache/' . str_replace('.' . $extension, '', $image) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
        if (!is_file(DIR_IMAGE . $cacheImage) || (filectime(DIR_IMAGE . $image) > filectime(DIR_IMAGE . $cacheImage))) {
            $dir = pathinfo(DIR_IMAGE . $cacheImage, PATHINFO_DIRNAME);
            if( ! \File::isDirectory($dir) ) {
                \File::makeDirectory($dir, 493, true);
            }

            if($closure) {
                $img = Image::make(DIR_IMAGE . $image)->resize($width, $height, $closure);
            } else {
                $img = Image::make(DIR_IMAGE . $image)->resize($width, $height, function($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $img->save(DIR_IMAGE . $cacheImage);
        }
        if(defined('HTTPS_CATALOG')) {
            $url = Request::secure() ? HTTPS_CATALOG : HTTP_CATALOG;
        } else {
            $url = Request::secure() ? HTTPS_SERVER : HTTP_SERVER;
        }

        return  $url . 'image/' . $cacheImage;
    }
}
