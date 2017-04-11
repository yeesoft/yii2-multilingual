<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yeesoft\multilingual\assets\LanguageSwitcherAsset;

/* @var $this yii\web\View */

LanguageSwitcherAsset::register($this);
?>

<div class="language-switcher language-switcher-links">
    <ul>
        <?php foreach ($languages as $key => $lang) : ?>
            <?php $title = ($display == 'code') ? $key : $lang; ?>
            <li>
                <?php if ($language == $key) : ?>
                    <span><?= $title ?></span>
                <?php else: ?>
                    <?= Html::a($title, ArrayHelper::merge($params, [$url, 'language' => $key, 'forceLanguageParam' => true])) ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>