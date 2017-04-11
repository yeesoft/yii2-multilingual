<?php

namespace tests;

use Yii;
use models\Post;
use fixtures\PostFixture;

class MultilingualBehaviorTest extends \Codeception\Test\Unit
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures()
    {
        return [
            'posts' => PostFixture::className(),
        ];
    }

    public function testFindPostsEnglish()
    {
        $data = [];
        $models = Post::find()->multilingual()->all();
        $fixture = $this->tester->grabFixture('posts')->data['posts'];

        foreach ($models as $model) {
            $this->assertEquals($model->title, $model->title_en_us);
            $this->assertEquals($model->content, $model->content_en_us);
            $this->assertNotNull($model->title_es);
            $this->assertNotNull($model->content_es);
            $data[] = $model->toArray();
        }

        $this->assertEquals($fixture, $data);
    }

    public function testFindPostsSpanish()
    {
        $data = [];
        Yii::$app->language = 'es';
        $models = Post::find()->multilingual()->all();
        $fixture = $this->tester->grabFixture('posts')->data['posts'];

        foreach ($models as $model) {
            $this->assertEquals($model->title, $model->title_es);
            $this->assertEquals($model->content, $model->content_es);
            $this->assertNotNull($model->title_en_us);
            $this->assertNotNull($model->content_en_us);
            $data[] = $model->toArray();
        }

        $this->assertEquals($fixture, $data);
    }

    public function testCreatePost()
    {
        $post = new Post([
            'slug' => 'new_post',
            'title' => 'New Post EN Title',
            'content' => 'New Post EN Content',
        ]);

        $this->assertTrue($post->save());

        $fixture = $this->tester->grabFixture('posts')->data['new_post'];
        $record = Post::find()->multilingual()->where(['slug' => 'new_post'])->one();

        $this->assertEquals($fixture, $record->toArray());
    }

    public function testCreatePostWithTranslations()
    {
        $params = [
            'slug' => 'new_post_with_translations',
            'title' => 'Post With Translations EN Title',
            'content' => 'Post With Translations EN Content',
            'title_es' => 'Post With Translations ES Title',
            'content_es' => 'Post With Translations ES Content',
        ];

        $post = new Post();
        $this->assertTrue($post->load($params, ''));
        $this->assertTrue($post->save());

        $fixture = $this->tester->grabFixture('posts')->data['new_post_with_translations'];
        $record = Post::find()->multilingual()->where(['slug' => 'new_post_with_translations'])->one();

        $this->assertEquals($fixture, $record->toArray());
    }

    public function testUpdatePost()
    {
        $post = Post::find()->multilingual()->where(['id' => 2])->one();
        $this->assertInstanceOf(Post::className(), $post);

        $post->setAttributes([
            'slug' => 'updated_post',
            'title' => 'Updated Post EN Title',
            'content' => 'Updated Post EN Content',
            'title_es' => 'Updated Post ES Title',
            'content_es' => 'Updated Post ES Content',
        ]);

        $this->assertTrue($post->save());

        $fixture = $this->tester->grabFixture('posts')->data['updated_post'];
        $record = Post::find()->multilingual()->where(['slug' => 'updated_post'])->one();

        $this->assertEquals($fixture, $record->toArray());
    }

    public function testDeletePost()
    {
        $post = Post::findOne(2);
        $this->assertEquals(1, $post->delete());

        $record = Post::findOne(2);
        $this->assertNull($record);
    }

    public function testLoadLocalizedPost()
    {
        //without `localized()`
        $post = Post::find()->where(['id' => 3])->one();
        $fixture = $this->tester->grabFixture('posts')->data['post_localized_en'];
        $this->assertEquals($fixture, [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'content' => $post->content,
        ]);

        //with `localized()`
        $post = Post::find()->localized()->where(['id' => 3])->one();
        $fixture = $this->tester->grabFixture('posts')->data['post_localized_en'];
        $this->assertEquals($fixture, [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'content' => $post->content,
        ]);

        //with `localized()`
        $post = Post::find()->localized('en-US')->where(['id' => 3])->one();
        $fixture = $this->tester->grabFixture('posts')->data['post_localized_en'];
        $this->assertEquals($fixture, [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'content' => $post->content,
        ]);

        //with `localized('es')`
        $post = Post::find()->localized('es')->where(['id' => 3])->one();
        $fixture = $this->tester->grabFixture('posts')->data['post_localized_es'];
        $this->assertEquals($fixture, [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'content' => $post->content,
        ]);
    }

    public function testLanguagesInApplicationsParams()
    {
        Yii::$app->params['languages'] = [
            'en-US' => 'English',
            'es' => 'Español',
        ];

        $post = new \models\PostLanguagesInParams();

        $this->assertEquals([
            'en-US' => 'English',
            'es' => 'Español',
        ], $post->getBehavior('multilingual')->languages);
    }

}
