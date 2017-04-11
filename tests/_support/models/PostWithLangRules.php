<?php

namespace models;

class PostWithLangRules extends PostCustomized
{

    public function rules()
    {
        return [
                [['title', 'content'], 'required'],
                [['slug'], 'string', 'max' => 127],
                ['title', 'string', 'min' => 5],
                ['content', 'email'],
        ];
    }

}
