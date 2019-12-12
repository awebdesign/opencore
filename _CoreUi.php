<?php

namespace AwebCore;

class CoreController extends Controller
{
    protected static $instance;

    private $db;
    private $translations = [];
    public $data = [];

    public $store_id = 0;
    public $engineName = 'Colorix';
    public $engineVersion = '1.5';
    static $config = null;

    public function __construct($registry)
    {
        // $this->registry = $registry;
        parent::__construct($registry);
        
        $this->db = $registry->get('db');
        $this->config = $registry->get('config');

        //set store id
        $this->store_id = $this->config->get('config_store_id');
    }

    public function loadTranslation($filename, $load_fallback = false)
    {
        if (isset($this->translations[$filename])) {
            return true; //already loaded
        }
        $file_path = AWEBCORE_DIR . $this->getWorkingFolder() . '/language/' . $this->getCurrentLanguage($load_fallback) . '/' . $filename . '.php';

        $this->checkWrongFileName($filename);

        if (!file_exists($file_path)) {
            if ($load_fallback) {
                throw new Exception('Could not load language file ' . $file_path);
            }
            return $this->loadTranslation($filename, true);
        }

        require_once modification($file_path);

        if (isset($_)) {
            $this->translations[$filename] = $_;

            foreach ($_ as $key => $value) {
                $this->setData($key, $value);
            }

            return $this;
        }

        throw new Exception('Language file is bad formatted: ' . $file_path);
    }

    public function data($var_name, $default_value = null, $replace = null)
    {
        $data = arrayMultiKey($this->data, $var_name, $default_value);

        if ($replace) {
            $data = vsprintf($data, $replace);
        }
        return $data;
    }

    public function setConfig()
    {
        if (self::$config !== null) {
            return self::$config;
        }

        // load Engine Settings
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . strtolower($this->engineName) . "_setting WHERE store_id = '" . $this->store_id . "'");

        $data = [];
        foreach ($query->rows as $setting) {
            $value = !$setting['serialized'] ? $setting['value'] : json_decode($setting['value'], true);

            $data[$setting['code']][$setting['key']] = $value;
        }

        self::$config = $data;

        return $this;
    }

    public function getConfig()
    {
        return self::$config;
    }

    public function view($filename, $data = [], $return = false)
    {
        $this->checkWrongFileName($filename);

        $this->setData($data); //set new vars

        $output = $this->loadTemplate($filename, $this->data);

        if ($return) {
            return $return;
        } else {
            $this->response->setOutput($output);
        }
    }

    public function checkWorkingFolder($checkFolder)
    {
        return ($checkFolder == $this->getWorkingFolder());
    }

    public function getOcModel($name)
    {
        $instance_name = 'model_' . str_replace('/', '_', $name);
        if (!$this->registry->has($instance_name)) {
            $this->load->model($name);
        }

        return $this->registry->get($instance_name);
    }

    public function getOcController($route, $args = array())
    {
        $action = new Action($route);
        $content = $action->execute($this->registry, $args);

        if ($content instanceof Exception) {
            throw $content;
        }

        return $content;
    }

    /* private & protected methods */

    /* template */
    private function setData($key, $value = '')
    {
        if (is_string($key) && strstr($key, '.')) {
            $newData = [];
            multikey($newData, $key, $value);

            $this->data = array_merge_recursive($this->data, $newData);
        } elseif (is_array($key)) {
            foreach ($key as $_key => $_val) {
                $this->setData($_key, $_val);
            }
        } else {
            $this->data[$key] = $value;
        }
    }

    private function checkWrongFileName($filename)
    {
        if (strstr($filename, '..')) {
            die('Hack Attempt!');
        }
    }

    /* template multikey assign */
    private function loadTemplate($filename, $data = array())
    {
        $this->checkWrongFileName($filename);

        $this->loadTranslation($filename);

        $data = array_merge($this->data, $data);
        $file_path = AWEBCORE_DIR . $this->getWorkingFolder() . '/views/template/' . $filename . '.tpl';
        if (!file_exists($file_path)) {
            throw new Exception('Could not load template ' . $file_path);
        }

        extract($data);

        ob_start();

        require modification($file_path);

        $content = ob_get_contents();

        ob_end_clean();

        return $content;
    }
}
