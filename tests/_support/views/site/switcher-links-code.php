<?php

use yeesoft\multilingual\widgets\LanguageSwitcher;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div>
    <?=
    LanguageSwitcher::widget([
        'view' => '@module/src/views/switcher/links',
        'display' => LanguageSwitcher::DISPLAY_CODE,
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
