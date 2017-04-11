<?php

namespace models;

class Image extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%image}}';
    }

    public function rules()
    {
        return [
                ['src', 'string'],
        ];
    }

}
