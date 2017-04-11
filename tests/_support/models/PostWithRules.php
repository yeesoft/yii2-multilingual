<?php

namespace models;

class PostWithRules extends PostCustomized
{

    public function rules()
    {
        return [
                [['title', 'content'], 'required'],
                [['slug'], 'string', 'max' => 127],
                [['title', 'content'], 'string'],
        ];
    }

}
