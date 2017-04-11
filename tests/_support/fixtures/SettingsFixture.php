<?php

namespace fixtures;

use yii\test\ArrayFixture;

class SettingsFixture extends ArrayFixture
{

    public function init()
    {
        $this->dataFile = __DIR__ . '/data/settings.php';
    }

}
