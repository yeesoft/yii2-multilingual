<?php

namespace fixtures;

use yii\test\ArrayFixture;

class PostFixture extends ArrayFixture
{

    public function init()
    {
        $this->dataFile = __DIR__ . '/data/post.php';
    }

}
