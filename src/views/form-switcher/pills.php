<?php

use yii\helpers\Html;
use yeesoft\multilingual\assets\FormLanguageSwitcherAsset;

/* @var $this yii\web\View */

FormLanguageSwitcherAsset::register($this);
?>
<?php if (count($languages) > 1): ?>
    <ul class="nav nav-pills form-language-switcher">
        <?php foreach ($languages as $key => $value) : ?>
            <li class="<?= ($language === $key) ? 'active' : '' ?>">
                <?= Html::a($value, "#{$key}", ['data-lang' => $key, 'data-toggle' => 'pill']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>