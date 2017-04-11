<?php

use yeesoft\multilingual\widgets\LanguageSwitcher;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div>
    <?=
    LanguageSwitcher::widget([
        'view' => '@module/src/views/switcher/pills',
        'languages' => [
            'en-US' => 'English',
            'es' => 'Español',
            'pt-BR' => 'Português',
        ],
        'languageRedirects' => [
            'pt-BR' => 'pt',
        ],
    ])
    ?>
</div>
