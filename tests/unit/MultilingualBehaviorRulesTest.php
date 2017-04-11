<?php

namespace tests;

use models\PostWithRules;
use models\PostWithLangRules;
use models\PostWithRequireRules;

class MultilingualBehaviorRulesTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testRequireTranslations()
    {
        $model = new PostWithRules();

        $this->assertFalse($model->validate());
        $this->assertEquals([
            'title' => ['Title cannot be blank.'],
            'content' => ['Content cannot be blank.'],
            'title_en_us' => ['Title [English] cannot be blank.'],
            'title_es' => ['Title [Español] cannot be blank.'],
            'content_en_us' => ['Content [English] cannot be blank.'],
            'content_es' => ['Content [Español] cannot be blank.'],
        ], $model->getErrors());

        
        $model2 = new PostWithRequireRules();

        $this->assertFalse($model2->validate());
        $this->assertEquals([
            'title' => ['Title cannot be blank.'],
            'content' => ['Content cannot be blank.'],
        ], $model2->getErrors());

        
    }
    public function testTranslationRules()
    {
        $model = new PostWithLangRules([
            'slug' => 'translation_rules_slug',
            'title' => '123',
            'title_es' => '987',
            'content' => 'abc',
            'content_es' => 'xyz',
        ]);

        $this->assertFalse($model->validate());
        $this->assertEquals([
            'title' => ['Title should contain at least 5 characters.'],
            'content' => ['Content is not a valid email address.'],
            'title_en_us' => ['Title [English] should contain at least 5 characters.'],
            'title_es' => ['Title [Español] should contain at least 5 characters.'],
            'content_en_us' => ['Content [English] is not a valid email address.'],
            'content_es' => ['Content [Español] is not a valid email address.'],
        ], $model->getErrors());
    }
    
    

}
