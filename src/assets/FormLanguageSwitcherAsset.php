<?php

namespace yeesoft\multilingual\assets;

use yii\web\AssetBundle;

class FormLanguageSwitcherAsset extends AssetBundle
{

    public $sourcePath = '@vendor/yeesoft/yii2-multilingual/src/assets/source/form-switcher';
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
    
}
