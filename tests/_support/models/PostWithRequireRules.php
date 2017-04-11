<?php

namespace models;

use yii\helpers\ArrayHelper;

class PostWithRequireRules extends PostCustomized
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
                    'multilingual' => [
                        'requireTranslations' => false,
                    ],
        ]);

        return $behaviors;
    }

    public function rules()
    {
        return [
                [['title', 'content'], 'required'],
                [['slug'], 'string', 'max' => 127],
                [['title', 'content'], 'string'],
        ];
    }

}
