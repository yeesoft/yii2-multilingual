<?php

namespace yeesoft\multilingual\assets;

use yii\web\AssetBundle;

class LanguageSwitcherAsset extends AssetBundle
{

    public $css = [
        'css/switcher.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source/switcher';
    }

}
