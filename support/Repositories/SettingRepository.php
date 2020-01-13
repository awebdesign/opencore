<?php

namespace OpenCore\Support\Repositories;

use OpenCore\Support\Entities\Setting;

class SettingRepository
{
    public function get($key)
    {
        $setting = Setting::select(['value', 'serialized'])->where('key', $key)->first();

        return $setting->serialized ? json_decode($setting->value) : $setting->value;
    }
}
