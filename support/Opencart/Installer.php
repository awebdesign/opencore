<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support\OpenCart;

trait Installer
{
    public function installOcmod($code)
    {
        $this->removeOcmod($code);

        $xml = $this->loadOcMod($code);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXml($xml);

        $version = $dom->getElementsByTagName('version')->item(0)->nodeValue;
        $modification_data = array(
            'name'    => $dom->getElementsByTagName('name')->item(0)->nodeValue,
            'author'  => $dom->getElementsByTagName('author')->item(0)->nodeValue,
            'version' => $version ? $version : OPENCORE_VERSION,
            'link'    => $dom->getElementsByTagName('link')->item(0)->nodeValue,
            'code'    => $dom->getElementsByTagName('code')->item(0)->nodeValue,
            'status'  => 1,
            'xml'     => str_replace('{{version}}', OPENCORE_VERSION, $xml)
        );

        if (isOc3()) {
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'extension` WHERE code = "' . $this->db->escape($code) . '"');

            $modification_data['extension_install_id'] = $query->num_rows ? (int) $query->row['extension_id'] : 0;

            $this->getOcmodel('setting/modification')->addModification($modification_data);
        } else {
            $this->getOcmodel('extension/modification')->addModification($modification_data);
        }
    }

    public function loadOcMod($file, $replace = null)
    {
        $engine_mod_path = __DIR__ . '/../Resources/' . $file . '.ocmod.xml';

        if (!file_exists($engine_mod_path)) {
            throw new \Exception($engine_mod_path . ' cannot be found.');
        }

        $xmlContent = file_get_contents($engine_mod_path);

        if ($replace) {
            $xmlContent = str_replace('</modification>', $xmlContent . '</modification>', $replace);
        }

        return $xmlContent;
    }

    public function removeOcmod($code)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");

        // delete files
        $files = array();

        // Make path into an array
        $path = array(DIR_MODIFICATION . '*');

        // While the path array is still populated keep looping through
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                // If directory add to path array
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                // Add the file to the files to be deleted array
                $files[] = $file;
            }
        }

        // Reverse sort the file array
        rsort($files);

        // Clear all modification files
        foreach ($files as $file) {
            if ($file != DIR_MODIFICATION . 'index.html') {
                // If file just delete
                if (is_file($file)) {
                    unlink($file);

                    // If directory use the remove directory function
                } elseif (is_dir($file)) {
                    rmdir($file);
                }
            }
        }
    }

    public function refreshOcmod()
    {
        if (isOc3()) {
            $this->load->model('setting/modification');
            $this->model_extension_modification = $this->model_setting_modification;
        } else {
            $this->load->model('extension/modification');
        }

        // Just before files are deleted, if config settings say maintenance mode is off then turn it on
        $maintenance = $this->config->get('config_maintenance');

        $this->load->model('setting/setting');

        $this->model_setting_setting->editSettingValue('config', 'config_maintenance', true);

        //Log
        $log = array();

        // Clear all modification files
        $files = array();

        // Make path into an array
        $path = array(DIR_MODIFICATION . '*');

        // While the path array is still populated keep looping through
        while (count($path) != 0) {
            $next = array_shift($path);

            foreach (glob($next) as $file) {
                // If directory add to path array
                if (is_dir($file)) {
                    $path[] = $file . '/*';
                }

                // Add the file to the files to be deleted array
                $files[] = $file;
            }
        }

        // Reverse sort the file array
        rsort($files);

        // Clear all modification files
        foreach ($files as $file) {
            if ($file != DIR_MODIFICATION . 'index.html') {
                // If file just delete
                if (is_file($file)) {
                    unlink($file);

                    // If directory use the remove directory function
                } elseif (is_dir($file)) {
                    rmdir($file);
                }
            }
        }

        // Begin
        $xml = array();

        // Load the default modification XML
        $xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

        // This is purly for developers so they can run mods directly and have them run without upload sfter each change.
        $files = glob(DIR_SYSTEM . '*.ocmod.xml');

        if ($files) {
            foreach ($files as $file) {
                $xml[] = file_get_contents($file);
            }
        }

        // Get the default modification file
        $results = $this->model_extension_modification->getModifications();

        foreach ($results as $result) {
            if ($result['status']) {
                $xml[] = $result['xml'];
            }
        }

        $modification = array();

        foreach ($xml as $xml) {
            if (empty($xml)) {
                continue;
            }

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->loadXml($xml);

            // Log
            $log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

            // Wipe the past modification store in the backup array
            $recovery = array();

            // Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
            if (isset($modification)) {
                $recovery = $modification;
            }

            $files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

            foreach ($files as $file) {
                $operations = $file->getElementsByTagName('operation');

                $files = explode('|', $file->getAttribute('path'));

                foreach ($files as $file) {
                    $path = '';

                    // Get the full path of the files that are going to be used for modification
                    if ((substr($file, 0, 7) == 'catalog')) {
                        $path = DIR_CATALOG . substr($file, 8);
                    }

                    if ((substr($file, 0, 5) == 'admin')) {
                        $path = DIR_APPLICATION . substr($file, 6);
                    }

                    if ((substr($file, 0, 6) == 'system')) {
                        $path = DIR_SYSTEM . substr($file, 7);
                    }

                    if ($path) {
                        $files = glob($path, GLOB_BRACE);

                        if ($files) {
                            foreach ($files as $file) {
                                // Get the key to be used for the modification cache filename.
                                if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
                                    $key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
                                }

                                if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
                                    $key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
                                }

                                if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
                                    $key = 'system/' . substr($file, strlen(DIR_SYSTEM));
                                }

                                // If file contents is not already in the modification array we need to load it.
                                if (!isset($modification[$key])) {
                                    $content = file_get_contents($file);

                                    $modification[$key] = preg_replace('~\r?\n~', "\n", $content);
                                    $original[$key] = preg_replace('~\r?\n~', "\n", $content);

                                    // Log
                                    $log[] = PHP_EOL . 'FILE: ' . $key;
                                }

                                foreach ($operations as $operation) {
                                    $error = $operation->getAttribute('error');

                                    // Ignoreif
                                    $ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

                                    if ($ignoreif) {
                                        if ($ignoreif->getAttribute('regex') != 'true') {
                                            if (strpos($modification[$key], $ignoreif->textContent) !== false) {
                                                continue;
                                            }
                                        } else {
                                            if (preg_match($ignoreif->textContent, $modification[$key])) {
                                                continue;
                                            }
                                        }
                                    }

                                    $status = false;

                                    // Search and replace
                                    if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
                                        // Search
                                        $search = $operation->getElementsByTagName('search')->item(0)->textContent;
                                        $trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
                                        $index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

                                        // Trim line if no trim attribute is set or is set to true.
                                        if (!$trim || $trim == 'true') {
                                            $search = trim($search);
                                        }

                                        // Add
                                        $add = $operation->getElementsByTagName('add')->item(0)->textContent;
                                        $trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
                                        $position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
                                        $offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

                                        if ($offset == '') {
                                            $offset = 0;
                                        }

                                        // Trim line if is set to true.
                                        if ($trim == 'true') {
                                            $add = trim($add);
                                        }

                                        // Log
                                        $log[] = 'CODE: ' . $search;

                                        // Check if using indexes
                                        if ($index !== '') {
                                            $indexes = explode(',', $index);
                                        } else {
                                            $indexes = array();
                                        }

                                        // Get all the matches
                                        $i = 0;

                                        $lines = explode("\n", $modification[$key]);

                                        for ($line_id = 0; $line_id < count($lines); $line_id++) {
                                            $line = $lines[$line_id];

                                            // Status
                                            $match = false;

                                            // Check to see if the line matches the search code.
                                            if (stripos($line, $search) !== false) {
                                                // If indexes are not used then just set the found status to true.
                                                if (!$indexes) {
                                                    $match = true;
                                                } elseif (in_array($i, $indexes)) {
                                                    $match = true;
                                                }

                                                $i++;
                                            }

                                            // Now for replacing or adding to the matched elements
                                            if ($match) {
                                                switch ($position) {
                                                    default:
                                                    case 'replace':
                                                        $new_lines = explode("\n", $add);

                                                        if ($offset < 0) {
                                                            array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

                                                            $line_id -= $offset;
                                                        } else {
                                                            array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
                                                        }

                                                        break;
                                                    case 'before':
                                                        $new_lines = explode("\n", $add);

                                                        array_splice($lines, $line_id - $offset, 0, $new_lines);

                                                        $line_id += count($new_lines);
                                                        break;
                                                    case 'after':
                                                        $new_lines = explode("\n", $add);

                                                        array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

                                                        $line_id += count($new_lines);
                                                        break;
                                                }

                                                // Log
                                                $log[] = 'LINE: ' . $line_id;

                                                $status = true;
                                            }
                                        }

                                        $modification[$key] = implode("\n", $lines);
                                    } else {
                                        $search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
                                        $limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
                                        $replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

                                        // Limit
                                        if (!$limit) {
                                            $limit = -1;
                                        }

                                        // Log
                                        $match = array();

                                        preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

                                        // Remove part of the the result if a limit is set.
                                        if ($limit > 0) {
                                            $match[0] = array_slice($match[0], 0, $limit);
                                        }

                                        if ($match[0]) {
                                            $log[] = 'REGEX: ' . $search;

                                            for ($i = 0; $i < count($match[0]); $i++) {
                                                $log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
                                            }

                                            $status = true;
                                        }

                                        // Make the modification
                                        $modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
                                    }

                                    if (!$status) {
                                        // Abort applying this modification completely.
                                        if ($error == 'abort') {
                                            $modification = $recovery;
                                            // Log
                                            $log[] = 'NOT FOUND - ABORTING!';
                                            break 5;
                                        }
                                        // Skip current operation or break
                                        elseif ($error == 'skip') {
                                            // Log
                                            $log[] = 'NOT FOUND - OPERATION SKIPPED!';
                                            continue;
                                        }
                                        // Break current operations
                                        else {
                                            // Log
                                            $log[] = 'NOT FOUND - OPERATIONS ABORTED!';
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Log
            $log[] = '----------------------------------------------------------------';
        }

        // Log
        $ocmod = new \Log('ocmod.log');
        $ocmod->write(implode("\n", $log));

        // Write all modification files
        foreach ($modification as $key => $value) {
            // Only create a file if there are changes
            if ($original[$key] != $value) {
                $path = '';

                $directories = explode('/', dirname($key));

                foreach ($directories as $directory) {
                    $path = $path . '/' . $directory;

                    if (!is_dir(DIR_MODIFICATION . $path)) {
                        @mkdir(DIR_MODIFICATION . $path, 0777);
                    }
                }

                $handle = fopen(DIR_MODIFICATION . $key, 'w');

                fwrite($handle, $value);

                fclose($handle);
            }
        }

        // Maintance mode back to original settings
        $this->model_setting_setting->editSettingValue('config', 'config_maintenance', $maintenance);
    }

    public function installHtaccess()
    {
        $file = __DIR__ . '/../Resources/htaccess.txt';

        if (!file_exists($file)) {
            throw new \Exception($file . ' cannot be found.');
        }

        $destination_file = DIR_APPLICATION . '.htaccess';
        if (file_exists($destination_file)) {
            throw new \Exception('Admin .htaccess file alreay exists!');
        }

        if (!copy($file, $destination_file)) {
            throw new \Exception('Failed to copy .htaccess file to admin folder!');
        }
    }

    public function removeHtaccess()
    {
        $destination_file = DIR_APPLICATION . '.htaccess';
        if (file_exists($destination_file) && !unlink($destination_file)) {
            throw new \Exception('Failed to remove .htaccess file to admin folder!');
        }
    }

    function installEvent($eventName, $eventPath, $eventAction)
    {
        /*
            Ex: OpenCore, extension/module/opencore/beforeLoadingController, admin/controller/\*\/before
        */
        //delete old events if any
        $extensionEvent = $this->removeEvent($eventName);

        $extensionEvent->addEvent($eventName, $eventAction, $eventPath);
    }

    function removeEvent($eventName)
    {
        /*
            Ex: OpenCore
        */
        if (isOc3()) {
            $extensionEvent = $this->getOcModel('setting/event');
        } else {
            $extensionEvent = $this->getOcModel('extension/event');
        }

        if (isOc3()) {
            $extensionEvent->deleteEventByCode($eventName);
        } else {
            $extensionEvent->deleteEvent($eventName);
        }

        return $extensionEvent;
    }

    /*public function installEngine()
    {
        if (!$this->checkWorkingFolder('admin')) {
            return false;
        }

        if ($this->checkRequirements()) {
            $this->createSettingsTable();

            $this->installEvents();

            if (isOc3()) {
                $this->load->model('setting/extension');
                $this->model_setting_extension->install('theme', 'colorix');
            } else {
                $this->load->model('extension/extension');
                $this->model_extension_extension->install('theme', 'colorix');
            }

            $this->installSystemOcmodinstallSystemOcmod();

            $this->setDefaultTheme('colorix');

            $this->refreshOcmod();
        }
    }

    public function uninstallEngine()
    {
        if (!$this->checkWorkingFolder('admin')) {
            return false;
        }
        if (isOc3()) {
            $extensionEvent = $this->getOcModel('setting/event');
        } else {
            $extensionEvent = $this->getOcModel('extension/event');
        }

        //delete old events if any
        if (isOc3()) {
            $extensionEvent->deleteEventByCode($this->engineName);
        } else {
            $extensionEvent->deleteEvent($this->engineName);
        }

        $this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . strtolower($this->engineName) . '_setting`');

        if (isOc3()) {
            $this->model_setting_extension->uninstall('theme', 'colorix');
        } else {
            $this->model_extension_extension->uninstall('theme', 'colorix');
        }

        $this->removeOcmod($this->engineName);

        $this->setDefaultTheme();

        $this->refreshOcmod();
    }

    public function exportSettings()
    {
        $data = [];

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "colorix_setting");
        $data['colorix_setting'] = $query->rows;

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE code like '%colorix%'");
        $data['event'] = $query->rows;

        $query = $this->db->query("SELECT type, code FROM " . DB_PREFIX . "extension WHERE (type = 'module' AND (code like '%colorix%' OR code in ('ajax_search_suggestions', 'html', 'vertical_menu', 'layout_container_split', 'homepage_products'))) OR (type = 'theme' AND code = 'colorix')");
        $data['extension'] = $query->rows;

        if (!empty($data['extension'])) {
            $codes = array_column($data['extension'], 'code');

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE code IN ('" . implode("', '", $codes) . "')");
            $data['setting'] = $query->rows;
        } else {
            $data['setting'] = [];
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code like '%colorix%' OR code in ('ajax_search_suggestions', 'html', 'vertical_menu', 'layout_container_split', 'homepage_products')");
        $data['module'] = $query->rows;

        return $data;
    }

    public function importSettings($content, $import_extensions = false)
    {
        //filter entries based on files selection
        if (!$import_extensions) {
            $process_data = [];
            foreach ($content as $table => $entries) {
                if ($table == 'colorix_setting') {
                    $process_data[$table] = $entries;
                } elseif (in_array($table, ['extension', 'setting', 'event'])) {
                    foreach ($entries as $entry) {
                        if (strstr($entry['code'], 'colorix')) {
                            $process_data[$table][] = $entry;
                        }
                    }
                }
            }
        } else {
            $process_data = $content;
        }

        $sql = ["START TRANSACTION;"];
        $sql[] = "TRUNCATE TABLE `" . DB_PREFIX . "colorix_setting`;";
        foreach ($process_data as $table => $entries) {
            foreach ($entries as $entry) {
                $sql[] = "REPLACE INTO `" . DB_PREFIX . "{$table}` (`" . implode("`, `", array_keys($entry)) . "`) VALUES ('" . implode("', '", $entry) . "');";
            }
        }

        $sql[] = "COMMIT;";

        foreach ($sql as $execute_sql) {
            $this->db->query($execute_sql);
        }

        return true;
    }

    protected function setDefaultTheme($themeName = 'theme_default')
    {
        $sql = 'UPDATE `' . DB_PREFIX . 'setting` SET `value` = "' . $themeName . '" WHERE `key` = "config_theme"';
        $this->db->query($sql);
    }

    protected function createSettingsTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . strtolower($this->engineName) . '_setting` (
                    `setting_id` int(11) NOT NULL AUTO_INCREMENT,
                    `store_id` smallint(6) unsigned NOT NULL,
                    `code` varchar(32) NOT NULL,
                    `key` varchar(64) NOT NULL,
                    `value` longtext NOT NULL,
                    `serialized` tinyint(1) NOT NULL,
                    PRIMARY KEY (`setting_id`)
                ) ENGINE=MyISAM COLLATE=utf8_general_ci;';
        $this->db->query($sql);

        $this->insertDbDefaultSettings(0);

        $this->load->model('setting/store');
        $results = $this->model_setting_store->getStores();
        foreach ($results as $result) {
            $this->insertDbDefaultSettings($result['store_id']);
        }
    }

    protected function insertDbDefaultSettings($store_id = 0)
    {
        $this->changeThemeStatus($store_id, 1); //OC default extension status

        $sql_dump = "INSERT INTO `" . DB_PREFIX . strtolower($this->engineName) . "_setting` (store_id, `code`, `key`, `value`, serialized) VALUES
        (" . (int) $store_id . ", 'theme', 'show_footer_block', '0', 0),
        (" . (int) $store_id . ", 'theme', 'image_location_height', '50', 0),
        (" . (int) $store_id . ", 'theme', 'image_location_width', '268', 0),
        (" . (int) $store_id . ", 'theme', 'image_cart_height', '100', 0),
        (" . (int) $store_id . ", 'theme', 'image_cart_width', '100', 0),
        (" . (int) $store_id . ", 'theme', 'image_wishlist_height', '47', 0),
        (" . (int) $store_id . ", 'theme', 'image_wishlist_width', '47', 0),
        (" . (int) $store_id . ", 'theme', 'image_compare_height', '90', 0),
        (" . (int) $store_id . ", 'theme', 'image_compare_width', '90', 0),
        (" . (int) $store_id . ", 'theme', 'image_related_height', '228', 0),
        (" . (int) $store_id . ", 'theme', 'image_related_width', '228', 0),
        (" . (int) $store_id . ", 'theme', 'image_additional_height', '74', 0),
        (" . (int) $store_id . ", 'theme', 'image_additional_width', '74', 0),
        (" . (int) $store_id . ", 'theme', 'image_product_height', '190', 0),
        (" . (int) $store_id . ", 'theme', 'image_product_width', '190', 0),
        (" . (int) $store_id . ", 'theme', 'image_popup_height', '500', 0),
        (" . (int) $store_id . ", 'theme', 'image_popup_width', '500', 0),
        (" . (int) $store_id . ", 'theme', 'image_thumb_height', '328', 0),
        (" . (int) $store_id . ", 'theme', 'image_thumb_width', '328', 0),
        (" . (int) $store_id . ", 'theme', 'image_category_height', '80', 0),
        (" . (int) $store_id . ", 'theme', 'image_category_width', '80', 0),
        (" . (int) $store_id . ", 'theme', 'currency_sup', '1', 0),
        (" . (int) $store_id . ", 'theme', 'quickview', '1', 0),
        (" . (int) $store_id . ", 'theme', 'compare', '1', 0),
        (" . (int) $store_id . ", 'theme', 'wishlist', '1', 0),
        (" . (int) $store_id . ", 'theme', 'warranty_information_id', '7', 0),
        (" . (int) $store_id . ", 'theme', 'product_description_length', '100', 0),
        (" . (int) $store_id . ", 'theme', 'product_limit', '15', 0),
        (" . (int) $store_id . ", 'theme', 'map_embed_url', '&lt;iframe src=&quot;https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d16110.491226282891!2d26.088162120703185!3d44.45450376106539!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sro!2sro!4v1540223073883&quot; width=&quot;600&quot; height=&quot;450&quot; frameborder=&quot;0&quot; style=&quot;border:0&quot; allowfullscreen&gt;&lt;/iframe&gt;', 0),
        (" . (int) $store_id . ", 'theme', 'map', '1', 0),
        (" . (int) $store_id . ", 'theme', 'social', '{\"fb\":\"Facebook URL\",\"google\":\"Google+ URL\",\"twitter\":\"Twitter URL\",\"instagram\":\"Instagram URL\"}', 1),
        (" . (int) $store_id . ", 'theme', 'custom_css', '', 0),
        (" . (int) $store_id . ", 'theme', 'nav_type', 'wide', 0),
        (" . (int) $store_id . ", 'theme', 'category_menu', 'button', 0),
        (" . (int) $store_id . ", 'theme', 'background_repeat', 'no-repeat', 0),
        (" . (int) $store_id . ", 'theme', 'background_size', 'cover', 0),
        (" . (int) $store_id . ", 'theme', 'background_position', 'center top', 0),
        (" . (int) $store_id . ", 'theme', 'background_image', '', 0),
        (" . (int) $store_id . ", 'theme', 'sticky_logo', '', 0),
        (" . (int) $store_id . ", 'theme', 'background_color', 'f6f6f6', 0),
        (" . (int) $store_id . ", 'theme', 'color_scheme', 'orange', 0),
        (" . (int) $store_id . ", 'theme', 'status', '1', 0),
        (" . (int) $store_id . ", 'theme', 'image_cart_width', '100', 0),
        (" . (int) $store_id . ", 'theme', 'image_cart_height', '100', 0),
        (" . (int) $store_id . ", 'theme', 'image_location_width', '150', 0),
        (" . (int) $store_id . ", 'theme', 'image_location_height', '150', 0),
        (" . (int) $store_id . ", 'theme', 'image_quality', '80', 0),
        (" . (int) $store_id . ", 'theme', 'footer_template', 'template_1', 0),
        (" . (int) $store_id . ", 'theme', 'footer_template', 'template_1', 0),
        (" . (int) $store_id . ", 'theme', 'show_sales_agents', '1', 0),
        (" . (int) $store_id . ", 'theme', 'sales_agents', '{\"1\":{\"name\":\"Agent Name\",\"phone\":\"123456789\",\"email\":\"agent_email@company.com\"}}', 1),";

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language");

        $footer_about = [];
        $sections = [];
        $footer_custom_html = [];

        foreach ($query->rows as $lang) {
            if (stristr($lang['code'], 'ro')) {
                $footer_about[$lang['language_id']] = [
                    'title' => 'Despre Noi',
                    'text' => 'Magazinul nostru comercializeaza o gama variata de produse electronice la cele mai bune preturi. Garantam calitatea produselor noastre si facem eforturi continue pentru a satisface nevoile clientilor nostri.',
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Producatori',
                    'href' => 'index.php?route=product/manufacturer'
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Oferte',
                    'href' => 'index.php?route=product/special'
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Contact',
                    'href' => 'index.php?route=information/contact'
                ];

                $footer_custom_html[$lang['language_id']] = '<ul>
                <li><a target="_blank" rel="nofollow" href="http://www.anpc.gov.ro/">ANPC - Protectia Consumatorilor</a></li>
                <li><a target="_blank" rel="nofollow" href="https://ec.europa.eu/consumers/odr/main/index.cfm?event=main.home2.show&lng=RO">SOL</a></li>
                </ul>';

                //extra prod tabs
                $extra_tabs[$lang['language_id']][] = [
                    'title' => 'Custom Product Tab',
                    'description' => 'This is the custom product tab.'
                ];
            } else {
                $footer_about[$lang['language_id']] = [
                    'title' => 'About Us',
                    'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae lorem semper, pharetra felis id, ornare purus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sit amet elit fermentum, laoreet ante non, egestas dolor.',
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Manufacturers',
                    'href' => 'index.php?route=product/manufacturer'
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Special Offers',
                    'href' => 'index.php?route=product/special'
                ];

                $sections[$lang['language_id']][] = [
                    'title' => 'Contact Us',
                    'href' => 'index.php?route=information/contact'
                ];

                $footer_custom_html[$lang['language_id']] = '<ul>
                <li><a href="#n">GDPR Policy</a></li>
                <li><a href="#n">Cookie Policy</a></li>
                </ul>';

                //extra prod tabs
                $extra_tabs[$lang['language_id']][] = [
                    'title' => 'Custom Product Tab',
                    'description' => 'This is the custom product tab.'
                ];
            }
        }
        $sql_dump .= "(" . (int) $store_id . ", 'theme', 'footer_about', '" . json_encode($footer_about) . "', 1),";

        $sql_dump .= "(" . (int) $store_id . ", 'theme', 'extra_tabs', '" . json_encode($extra_tabs) . "', 1),";

        $sql_dump .= "(" . (int) $store_id . ", 'theme', 'sections', '" . json_encode($sections) . "', 1),

        (" . (int) $store_id . ", 'theme', 'footer_custom_html', '" . $this->db->escape(json_encode($this->request->clean($footer_custom_html))) . "', 1)";

        $this->db->query($sql_dump);
    }

    protected function changeThemeStatus($store_id, $status)
    {
        $this->load->model('setting/setting');
        if (isOc3()) {
            $this->model_setting_setting->editSetting('theme_colorix', ['theme_colorix_status' => (int) $status], $store_id);
        } else {
            $this->model_setting_setting->editSetting('colorix', ['colorix_status' => (int) $status], $store_id);
        }
    }

    protected function checkRequirements()
    {
        if (!isCompatible()) {
            throw new \Exception('Incompatible Open Cart Version!');
            return false;
        }

        if (!is_writable(DIR_MODIFICATION)) {
            throw new \Exception('Modification folder <strong>' . DIR_MODIFICATION . '</strong> needs to be writable. Please change its permissions to 777');

            return false;
        }

        return true;
    }

    public function installSystemOcmod() {

        $this->removeOcmod('engine');

        $xml = $this->loadOcMod('engine');

        //we have a default Engine OC MOD and a different file for each version
        if (isAwebDropshipping()) {
            $xml = $this->loadOcMod('dropshipping', $xml);
        } elseif (isOc3()) {
            $xml = $this->loadOcMod('opencart30', $xml);
        } else {
            $xml = $this->loadOcMod('opencart23', $xml);
        }

        $this->installOcmod($xml);
    }*/
}
