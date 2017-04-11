<?php

namespace models;

use yeesoft\multilingual\behaviors\MultilingualBehavior;
use yeesoft\multilingual\db\MultilingualLabelsTrait;
use yeesoft\multilingual\db\MultilingualQuery;

class Post extends \yii\db\ActiveRecord
{

    use MultilingualLabelsTrait;

    public static function tableName()
    {
        return '{{%post}}';
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
            ],
        ];
    }

    public function rules()
    {
        return [
            [['slug'], 'string', 'max' => 127],
            [['title', 'content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     * @return MultilingualQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = ['translations'], $recursive = true)
    {
        return parent::toArray($fields, $expand, $recursive);
    }

}
