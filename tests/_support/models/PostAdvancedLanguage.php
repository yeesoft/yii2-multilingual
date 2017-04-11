<?php

namespace models;

use yii\db\ActiveRecord;

class PostAdvancedLanguage extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%post_advanced_language}}';
    }

}
