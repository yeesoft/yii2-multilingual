<?php

namespace yeesoft\multilingual\assets;

use yii\web\AssetBundle;

class FormLanguageSwitcherAsset extends AssetBundle
{

    public $js = [
        'js/form-switcher.js',
    ];
    public $css = [
        'css/form-switcher.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source/form-switcher';
    }

}
