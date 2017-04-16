<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yeesoft\multilingual\assets\LanguageSwitcherAsset;

/* @var $this yii\web\View */

LanguageSwitcherAsset::register($this);
?>

<div class="language-switcher language-switcher-pills">
    <ul class="nav nav-pills">
        <?php foreach ($languages as $key => $lang) : ?>
            <?php $title = ($display == 'code') ? $key : $lang; ?>
            <?php if ($language == $key) : ?>
                <li class="active">
                    <a><?= $title ?></a>
                </li>
            <?php else: ?>
                <li>
                    <?= Html::a($title, ArrayHelper::merge($params, [$url, 'language' => $key, 'forceLanguageParam' => true])) ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>


