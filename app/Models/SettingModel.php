<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'description'];
    protected $useTimestamps = true;

    public function getValue($key)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : null;
    }
}
