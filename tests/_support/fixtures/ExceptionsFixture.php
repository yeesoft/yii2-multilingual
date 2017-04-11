<?php

namespace fixtures;

use yii\test\ArrayFixture;

class ExceptionsFixture extends ArrayFixture
{

    public function init()
    {
        $this->dataFile = __DIR__ . '/data/exceptions.php';
    }

}
