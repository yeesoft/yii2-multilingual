<?php

namespace tests;

use models\PostAdvanced;
use fixtures\ExceptionsFixture;
use yii\base\InvalidConfigException;

class MultilingualBehaviorExceptionTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'settings' => ExceptionsFixture::className(),
        ];
    }

    private function loadAndTestBehaviorSettings($fixtureName)
    {
        $fixture = $this->tester->grabFixture('settings')->data[$fixtureName];

        $post = new PostAdvanced();
        $post->detachBehavior('multilingual');

        try {
            $post->attachBehavior('multilingual', $fixture['settings']);
            $this->fail("Expected exception not thrown");
        } catch (InvalidConfigException $e) {
            $this->assertEquals($fixture['message'], $e->getMessage());
        }
    }

    public function testBehaviorSettingsNoAttributes()
    {
        $this->loadAndTestBehaviorSettings('no_attributes');
    }

    public function testBehaviorSettingsNoLanguages()
    {
        $this->loadAndTestBehaviorSettings('no_languages');
    }

    public function testBehaviorSettingsTableNotExist()
    {
        $this->loadAndTestBehaviorSettings('table_not_exist');
    }

}
