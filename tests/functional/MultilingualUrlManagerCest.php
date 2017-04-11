<?php

use yii\helpers\Url;

class MultilingualUrlManagerCest
{

    public function _before(\FunctionalTester $I)
    {
        Yii::$app->language = 'en-US';
        $I->amOnPage(['site/index']);
    }

    public function testUrlGeneration(\FunctionalTester $I)
    {
        PHPUnit_Framework_Assert::assertEquals('/en-US', Url::to(['site/index']));
        PHPUnit_Framework_Assert::assertEquals('/en-US/about', Url::to(['site/about']));
        PHPUnit_Framework_Assert::assertEquals('/es/about', Url::to(['site/about', 'language' => 'es']));
    }

    public function testlanguageRedirectParam(\FunctionalTester $I)
    {
        PHPUnit_Framework_Assert::assertEquals('/pt/about', Url::to(['site/about', 'language' => 'pt']));
    }

    public function testExcludedActionsParam(\FunctionalTester $I)
    {
        PHPUnit_Framework_Assert::assertEquals('/login', Url::to(['site/login']));
        PHPUnit_Framework_Assert::assertEquals('/login', Url::to(['site/login', 'language' => 'pt']));
    }

    public function testForceLanguageParam(\FunctionalTester $I)
    {
        PHPUnit_Framework_Assert::assertEquals('/login?language=en-US', Url::to(['site/login', 'forceLanguageParam' => true]));
        PHPUnit_Framework_Assert::assertEquals('/login?language=pt', Url::to(['site/login', 'language' => 'pt', 'forceLanguageParam' => true]));
    }
    
    public function testLanguageSwitcherPills(\FunctionalTester $I)
    {
        $I->amOnPage(['site/switcher-pills']);
        
        $I->seeElement('div.language-switcher.language-switcher-pills');
        
        $I->see('English','.nav-pills li.active a');
        $I->see('Español','.nav-pills li a[href="/es/switcher-pills"]');
        $I->see('Português','.nav-pills li a[href="/pt/switcher-pills"]');
        
        $I->click('.nav-pills a[href="/pt/switcher-pills"]');
        PHPUnit_Framework_Assert::assertEquals('pt-BR', Yii::$app->language);
   
        $I->see('English','.nav-pills li a[href="/en-US/switcher-pills"]');
        $I->see('Español','.nav-pills li a[href="/es/switcher-pills"]');
        $I->see('Português','.nav-pills li.active a');
        
        $I->click('.nav-pills a[href="/es/switcher-pills"]');
        PHPUnit_Framework_Assert::assertEquals('es', Yii::$app->language);
   
        $I->see('English','.nav-pills li a[href="/en-US/switcher-pills"]');
        $I->see('Español','.nav-pills li.active a');
        $I->see('Português','.nav-pills li a[href="/pt/switcher-pills"]');
    }
    
    public function testLanguageSwitcherPillsCode(\FunctionalTester $I)
    {
        $I->amOnPage(['site/switcher-pills-code']);
        
        $I->seeElement('div.language-switcher.language-switcher-pills');
        
        $I->see('en-US','.nav-pills li.active a');
        $I->see('es','.nav-pills li a[href="/es/switcher-pills-code"]');
        $I->see('pt','.nav-pills li a[href="/pt/switcher-pills-code"]');
        
        $I->click('.nav-pills a[href="/pt/switcher-pills-code"]');
        PHPUnit_Framework_Assert::assertEquals('pt-BR', Yii::$app->language);
   
        $I->see('en-US','.nav-pills li a[href="/en-US/switcher-pills-code"]');
        $I->see('es','.nav-pills li a[href="/es/switcher-pills-code"]');
        $I->see('pt','.nav-pills li.active a');
        
        $I->click('.nav-pills a[href="/es/switcher-pills-code"]');
        PHPUnit_Framework_Assert::assertEquals('es', Yii::$app->language);
   
        $I->see('en-US','.nav-pills li a[href="/en-US/switcher-pills-code"]');
        $I->see('es','.nav-pills li.active a');
        $I->see('pt','.nav-pills li a[href="/pt/switcher-pills-code"]');
    }
    
    public function testLanguageSwitcherLinks(\FunctionalTester $I)
    {
        $I->amOnPage(['site/switcher-links']);
        
        $I->seeElement('div.language-switcher.language-switcher-links');
        
        $I->see('English','ul li span');
        $I->see('Español','ul li a[href="/es/switcher-links"]');
        $I->see('Português','ul li a[href="/pt/switcher-links"]');
        
        $I->click('ul li a[href="/pt/switcher-links"]');
        PHPUnit_Framework_Assert::assertEquals('pt-BR', Yii::$app->language);
   
        $I->see('English','ul li a[href="/en-US/switcher-links"]');
        $I->see('Español','ul li a[href="/es/switcher-links"]');
        $I->see('Português','ul li span');
        
        $I->click('ul li a[href="/es/switcher-links"]');
        PHPUnit_Framework_Assert::assertEquals('es', Yii::$app->language);
   
        $I->see('English','ul li a[href="/en-US/switcher-links"]');
        $I->see('Español','ul li span');
        $I->see('Português','ul li a[href="/pt/switcher-links"]');
    }
    
    public function testLanguageSwitcherLinksCode(\FunctionalTester $I)
    {
        $I->amOnPage(['site/switcher-links-code']);
        
        $I->seeElement('div.language-switcher.language-switcher-links');
        
        $I->see('en-US','ul li span');
        $I->see('es','ul li a[href="/es/switcher-links-code"]');
        $I->see('pt','ul li a[href="/pt/switcher-links-code"]');
        
        $I->click('ul li a[href="/pt/switcher-links-code"]');
        PHPUnit_Framework_Assert::assertEquals('pt-BR', Yii::$app->language);
   
        $I->see('en-US','ul li a[href="/en-US/switcher-links-code"]');
        $I->see('es','ul li a[href="/es/switcher-links-code"]');
        $I->see('pt','ul li span');
        
        $I->click('ul li a[href="/es/switcher-links-code"]');
        PHPUnit_Framework_Assert::assertEquals('es', Yii::$app->language);
   
        $I->see('en-US','ul li a[href="/en-US/switcher-links-code"]');
        $I->see('es','ul li span');
        $I->see('pt','ul li a[href="/pt/switcher-links-code"]');
    }

}
