<?php

namespace models;

use yeesoft\multilingual\behaviors\MultilingualBehavior;

class PostWithLocalizedPrefix extends PostCustomized
{

    public static function tableName()
    {
        return '{{%post_prefixed}}';
    }

    public function behaviors()
    {
        return [
            'multilingual' => [
                'class' => MultilingualBehavior::className(),
                'attributes' => [
                    'title', 'content',
                ],
                'languages' => [
                    'en-US' => 'English',
                    'es' => 'Español',
                ],
                'localizedPrefix' => 'val_',
                'attributeLabelPattern' => '{label} - {language} - {code}',
                'forceDelete' => false,
            ],
        ];
    }

}
