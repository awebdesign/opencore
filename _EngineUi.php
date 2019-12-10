<?php

namespace AwebCore;

class EngineUi
{
    use Request;

    protected static $boot = false;
    protected static $core;
    protected static $registry;

    public static function boot(Registry $registry = null)
    {
        if (!self::$boot) {
            if (!isset(self::$registry)) {
                if (null === $registry) {
                    throw new InvalidArgumentException('Registry instance not defined!');
                }
                self::$registry = $registry;
            }

            self::getCore()->setConfig();
            self::lang('colorix');
            self::setEngineConfig();

            self::$boot = true;
        }

        return new self();
    }

    public static function isLoaded()
    {
        return self::$boot;
    }

    public static function sess($name, $default_value = null)
    {
        $default_key = Input::Cookie('default');
        if (!$default_key) {
            return $default_value;
        }
        return (isset($_SESSION[$default_key]) && isset($_SESSION[$default_key][$name])) ? $_SESSION[$default_key][$name] : $default_value;
    }

    public static function _sess($name, $default_value = null)
    {
        echo self::sess($name, $default_value);
    }

    public static function url()
    {
        return (Input::server('HTTPS') && defined('HTTPS_SERVER')) ? HTTPS_SERVER : HTTP_SERVER;
    }

    public static function _url()
    {
        echo self::url();
    }

    public static function link($name, $suffix)
    {
        return self::registry('url')->link($name, $suffix);
    }

    public static function _link($name, $replace)
    {
        echo self::link($name, $replace);
    }

    public static function config($var_name, $default_value = null, $oc_config = false)
    {
        $var_name = self::replaceCallbackMethods($var_name);

        //in case demo session is activate take those values directly
        if (
            isset(self::registry('session')->data['demo_colorix']) && isset(self::registry('session')->data['demo_colorix.' . $var_name])
            && in_array($var_name, ['config.config_logo', 'theme.color_scheme', 'theme.nav_type', 'theme.category_menu', 'theme.footer_template'])
        ) {
            return self::registry('session')->data['demo_colorix.' . $var_name];
        }

        if ($oc_config) {
            if (strstr($var_name, '.')) {
                $var_name = explode('.', $var_name);
                $config_name = array_shift($var_name);
            } else {
                $config_name = $var_name;
            }
            $value = self::registry('config')->get('config_' . $config_name);
        } else {
            $value = self::getCore()->getConfig();
        }

        $value = arrayMultiKey($value, $var_name, $default_value);

        return $value;
    }

    public static function _config($var_name, $default_value = null, $oc_config = false)
    {
        echo self::config($var_name, $default_value, $oc_config);
    }

    public static function data($name, $default_value = null, $replace = null)
    {
        $data = self::getCore()->data($name, $default_value);
        if ($replace) {
            $data = vsprintf($data, $replace);
        }
        return $data;
    }

    public static function _($name, $replace = null)
    {
        echo self::data($name, null, $replace);
    }

    public static function lang($name)
    {
        self::getCore()->loadTranslation($name);

        return new self();
    }

    public static function languageId()
    {
        return self::registry('config')->get('config_language_id');
    }

    /* private methods */
    private static function getCore()
    {
        if (!isset(self::$core)) {
            self::$core = new CoreUi(self::$registry);
        }

        return self::$core;
    }

    private static function setEngineConfig()
    {
        $theme_prefix = isOc3() ? 'theme_colorix_' : 'colorix_';
        foreach (self::getCore()->getConfig() as $code => $values) {
            foreach ($values as $key => $value) {
                if ($code == 'theme') { //overwrite default OpenCart data                    
                    self::registry('config')->set($theme_prefix . $key, $value);
                }
            }
        }
    }

    private static function replaceCallbackMethods($var_name)
    {
        preg_match_all("/\{([^\}]*)\}/", $var_name, $matches);
        if (isset($matches[0]) && !empty($matches[0])) {
            foreach ($matches[1] as $key => $method) {
                $reflection = new ReflectionMethod(new self(), $method);
                if (!$reflection->isPublic()) {
                    throw new RuntimeException("The called method is not public.");
                }
                $callback_return = $reflection->invoke(new self());

                $var_name = str_replace($matches[0][$key], $callback_return, $var_name);
            }
        }

        return $var_name;
    }

    protected static function registry($var_name)
    {
        return self::$registry->get($var_name);
    }
}
