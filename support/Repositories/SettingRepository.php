<?php

namespace OpenCore\Support\Repositories;

use OpenCore\Support\Entities\Setting;

class SettingRepository
{
    private $settings;

    public function __construct()
    {
        $settings = Setting::select(['key', 'value', 'serialized'])->get();
        foreach ($settings as $setting) {
            if ($setting->serialized) {
                $this->settings[$setting->key] = json_decode($setting->value);
            } else {
                $this->settings[$setting->key] = $setting->value;
            }
        }
    }
    public function get($key)
    {
        return $this->settings[$key] ?? null;
    }
}
