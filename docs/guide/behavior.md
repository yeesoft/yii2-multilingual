Multilingual Behavior
============

Multilingual behavior allows you to create multilingual models and use them as 
regular models. Translations are stored in a separate table in the database for 
each model, so you can add or remove a language easily. This part of the module 
is a fork of the [omgdef/yii2-multilingual-behavior](https://github.com/OmgDef/yii2-multilingual-behavior) module.

Configuration
------

This example shows how to create and use multilingual models.

1. First of all we need create tables for multilingual model. Let's create 
`console/migrations/m170101_101010_create_post_table.php` migration class:

```php
<?php

use yii\db\Migration;

class m170101_101010_create_post_table extends Migration {

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(127),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%post_lang}}', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer()->notNull(),
            'language' => $this->string(6)->notNull(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey('fk_post_lang', '{{%post_lang}}', 'owner_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_post_lang', '{{%post_lang}}');
        $this->dropTable('{{%post_lang}}');
        $this->dropTable('{{%post}}');
    }

}
```

Run migration tool to apply current migration:

```bash
php yii migrate
```

2. Second step is creating multilingual model. Let's create `common/models/Post.php` file
with model class:

```php
<?php

namespace common\models;

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
                'languages' => [
                    'en-US' => 'English',
                    'es' => 'Español',
                ],
                'attributes' => [
                    'title', 'content',
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
                [['title'], 'required'],
                [['slug'], 'string', 'max' => 127],
                [['title'], 'string', 'max' => 255],
                [['created_at', 'updated_at'], 'integer'],
                [['content'], 'string'],
        ];
    }

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

}
```

Three steps to create multilingual model:

1. Add `yeesoft\multilingual\behaviors\MultilingualBehavior` behavior to model. Name of the behavior must be `multilingual`. Only `attributes` parameter is  required. You can specify `languages` either in the behavior or in application's parameters.
2. Method `find` must return `yeesoft\multilingual\db\MultilingualQuery`. You can either override `find` method of the model or use `yeesoft\multilingual\db\MultilingualTrait` in your `ActiveQuery` class.
3. Use `yeesoft\multilingual\db\MultilingualLabelsTrait` to fix generating attribute labels for multilingual fields. This step is optional.


Usage
------

1. Creation:

```php
<?php

    $post = new Post();
    $post->title = 'Title';
    $post->title_es = 'ES Title';
    $post->title_en_us = 'EN-US Title';
    $post->save();
```

If application's language is 'en-US' (default) then in this case `title` will be overwritten by `title_en_us`. 
If language is `es` then `title` will be overwritten by `title_es`.


2. Reading:

```php
<?php

    //title == title_en_us
    Yii::$app->language = 'en-US';
    $post = Post::find()->multilingual()->one();
    echo $post->title;
    echo $post->title_es;
    echo $post->title_en_us;
    
    //title == title_es
    Yii::$app->language = 'es';
    $post = Post::find()->multilingual()->one();
    echo $post->title;
    echo $post->title_es;
    echo $post->title_en_us;
    
    $post = Post::find()->localized('es')->one();
    echo $post->title; //echo Spanish title
```

Attributes
------

Attribute | Description
----------|------------
**attributes** | List of multilingual attributes
**languages** | List of available languages. It can be a simple array: ```['en-US', 'es']``` or an associative array: ```['en-US' => 'English', 'es' => 'Español']```. You can specify `languages` either in the behavior or in application's parameters.
tableName | The name of the translation table. If `tableName` is not set `$owner->tableName()` + `tableNameSuffix` will be used as `tableName`.
tableNameSuffix | Translation table name suffix. Is used to generate translation table name when `tableName` is not set. Default: `_lang`.
languageField | The name of the language field of the translation table. Default: `language`.
languageForeignKey | Name of the foreign key field of the translation table related to the base model table. Default: `owner_id`.
translationClassName | The name of translation model class. If this value is empty translation model class will be dynamically created using of the `eval()` function. No additional files are required. Default: `get_class($model)` + `translationClassNameSuffix`.
translationClassNameSuffix | Translation class name suffix. Is used to generate translation model class name when `translationClassName` is not set. Default: `Lang`.
requireTranslations | If this property is set to true required validators will be applied to all translation models. Default: `true`.
attributeLabelPattern | Multilingual attribute label pattern. Available variables: `{label}` - original attribute label, `{language}` - language name, `{code}` - language code. Default: `{label} [{language}`.
localizedPrefix | The prefix of the localized attributes in the language table. Used to avoid collisions in queries. In the translation table, the columns corresponding to the localized attributes have to be name like this: 'prefix_[name of the attribute]' and the id column (primary key) like this : 'prefix_id'. Default: `''`.
forceDelete | Whether to use force deletion of the associated translations when a base model is deleted. Not needed if using foreign key with 'on delete cascade'. Default: `true`.


**Bold** attributes are required. 

Note!
------

Be sure that language class name that will be generated by behavior is not used yet in your application!