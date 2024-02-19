<?php

namespace Elemenx\CirFrameworkSkeleton\Http\Controllers;

use Elemenx\CirFrameworkSkeleton\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    public function show()
    {
        $setting = $this->model->find('setting');
        return $this->success(!empty($setting) ? $setting->value : null);
    }

    public function setSetting()
    {
        $setting = $this->model->firstOrCreate([
            'key' => 'setting',
        ]);

        $setting->update(['value' => app('request')->all()]);
        Cache::forget('config:admin');

        return $this->success($setting->value);
    }
}
