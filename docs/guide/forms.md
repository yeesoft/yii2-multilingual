Multilingual Form
============

Multilingual form allows you to create forms with multilingual fields. You can 
either display fields for each language of your site or use language switcher 
to display buttons for switching between languages. 

This example shows how to create and use multilingual forms.

1. Let's create action in `SiteController` that allows as update our multilingual `common\models\Post` record with id = 1.

```php
<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Post;

class SiteController extends Controller
{
    ...

    public function actionUpdate()
    {
        $model = Post::find()->multilingual()->where(['id' => 1])->one();

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('message', 'Your item has been updated.');
            return $this->redirect(['update']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', compact('model'));
        } else {
            return $this->render('update', compact('model'));
        }
    }

    ...

}
```

2. Second step is creating of `views/site/update.php` view for the action:

```php
<?php

use yii\helpers\Html;
use yeesoft\multilingual\widgets\ActiveForm;

/* @var $this yii\web\View */
?>

<?php if (Yii::$app->session->hasFlash('message')): ?>
    <div class="alert alert-info" role="alert">
        <?= Yii::$app->session->getFlash('message') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin() ?>

<?= $form->languageSwitcher($model); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'content')->textarea() ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

```

`yeesoft\multilingual\widgets\ActiveForm` form looks and bahaves in the same way as `\yii\bootstrap\ActiveForm`.
The only difference is using of language switcher `echo $form->languageSwitcher($model)`. This code render widget to switch between languages.
But this is optional. If there is no language switcher all multilingual fields will be rendered one by one.

- Form With Switcher:

  ![Form With Switcher](https://raw.githubusercontent.com/yeesoft/yii2-multilingual/master/docs/images/form-with-switcher.png)


- Form Without Switcher:

  ![Form Without Switcher](https://raw.githubusercontent.com/yeesoft/yii2-multilingual/master/docs/images/form-without-switcher.png)

 
