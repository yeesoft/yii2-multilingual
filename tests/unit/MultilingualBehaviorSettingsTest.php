<?php

namespace tests;

use yii\db\Query;
use fixtures\SettingsFixture;
use yeesoft\multilingual\behaviors\MultilingualBehavior;

class MultilingualBehaviorSettingsTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'settings' => SettingsFixture::className(),
        ];
    }

    private function loadAndTestBehaviorSettings($fixtureName)
    {
        $fixture = $this->tester->grabFixture('settings')->data[$fixtureName];
        $className = $fixture['modelClassName'];
        $params = $fixture['data'];
        $post = new $className;

        $this->assertTrue($post->load($params, ''));
        $this->assertTrue($post->save());

        $model = $className::find()->multilingual()->where(['slug' => $params['slug']])->one();

        foreach ($params as $field => $value) {
            $this->assertEquals($model->{$field}, $value);
        }
    }

    public function testCustomLanguageModel()
    {
        $this->loadAndTestBehaviorSettings('custom_language_model');
    }

    public function testCustomParameters()
    {
        $this->loadAndTestBehaviorSettings('custom_params');
    }

    public function testCustomLanguageTableNameParameter()
    {
        $this->loadAndTestBehaviorSettings('custom_table_name_param');
    }

    public function testLocalizedPrefixParameter()
    {
        $this->loadAndTestBehaviorSettings('localized_prefix_param');
    }

    public function testAttributeLabelPatternParameter()
    {
        $post = new \models\PostAdvanced();

        $this->assertEquals('Title', $post->getAttributeLabel('title'));
        $this->assertEquals('Title [English]', $post->getAttributeLabel('title_en_us'));
        $this->assertEquals('Title [Español]', $post->getAttributeLabel('title_es'));

        $post2 = new \models\PostWithLocalizedPrefix();

        $this->assertEquals('Title', $post2->getAttributeLabel('title'));
        $this->assertEquals('Title - English - en-US', $post2->getAttributeLabel('title_en_us'));
        $this->assertEquals('Title - Español - es', $post2->getAttributeLabel('title_es'));
    }

    public function testForceDeleteParameter()
    {
        $post = new \models\PostAdvanced([
            'slug' => 'force_delete_title',
            'title' => 'Post EN Force Delete Title',
            'title_es' => 'Post ES Force Delete Title',
        ]);

        $this->assertTrue($post->save());

        $postId = $post->id;
        $post->delete();

        $translations = (new Query)
                ->from('post_advanced_language')
                ->where(['post_id' => $postId])
                ->all();

        $this->assertEquals([], $translations);

        //This translations should remain in database
        $post2 = new \models\PostWithLocalizedPrefix([
            'slug' => 'force_delete_title_2',
            'title' => 'Post EN Force Delete Title 2',
            'title_es' => 'Post ES Force Delete Title 2',
        ]);

        $this->assertTrue($post2->save());

        $post2Id = $post2->id;
        $post2->delete();

        $translations2 = (new Query)
                ->from('post_prefixed_lang')
                ->where(['owner_id' => $post2Id])
                ->all();

        $this->assertEquals([
                [
                'id' => 3,
                'owner_id' => 2,
                'language' => 'en-US',
                'val_title' => 'Post EN Force Delete Title 2',
                'val_content' => '',
            ],
                [
                'id' => 4,
                'owner_id' => 2,
                'language' => 'es',
                'val_title' => 'Post ES Force Delete Title 2',
                'val_content' => '',
            ],
        ], $translations2);
    }

    public function testTableNameGeneration()
    {
        $this->assertEquals('{{%post_lang}}', MultilingualBehavior::generateTableName('post', '_lang'));
        $this->assertEquals('{{%post_language}}', MultilingualBehavior::generateTableName('post', '_language'));
        $this->assertEquals('{{%post_lang}}', MultilingualBehavior::generateTableName('{{%post}}', '_lang'));
    }

}
