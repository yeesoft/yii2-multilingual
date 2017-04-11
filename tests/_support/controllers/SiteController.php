<?php

namespace controllers;

use Yii;
use models\Page;
use models\Image;
use yii\web\Controller;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionSwitcherPills()
    {
        return $this->render('switcher-pills');
    }

    /**
     * @return string
     */
    public function actionSwitcherPillsCode()
    {
        return $this->render('switcher-pills-code');
    }

    /**
     * @return string
     */
    public function actionSwitcherLinks()
    {
        return $this->render('switcher-links');
    }

    /**
     * @return string
     */
    public function actionSwitcherLinksCode()
    {
        return $this->render('switcher-links-code');
    }

    /**
     * @return string
     */
    public function actionForm()
    {
        $model = Page::find()->multilingual()->where(['id' => 1])->one();

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('message', 'Your item has been updated.');
            return $this->redirect(['form']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('form', compact('model'));
        } else {
            return $this->render('form', compact('model'));
        }
    }

    /**
     * @return string
     */
    public function actionFormWithoutSwitcher()
    {
        $model = Page::find()->multilingual()->where(['id' => 2])->one();

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('message', 'Your item has been updated.');
            return $this->redirect(['form-without-switcher']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('form-without-switcher', compact('model'));
        } else {
            return $this->render('form-without-switcher', compact('model'));
        }
    }

    /**
     * @return string
     */
    public function actionFormWithInputId()
    {
        $model = Page::find()->multilingual()->where(['id' => 1])->one();
        return $this->render('form-with-input-id', compact('model'));
    }

    /**
     * @return string
     */
    public function actionFormNotMultilingual()
    {
        $model = Image::find()->where(['id' => 1])->one();

        if ($model->load(Yii::$app->request->post()) AND $model->save()) {
            Yii::$app->session->setFlash('message', 'Your item has been updated.');
            return $this->redirect(['form-not-multilingual']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('form-not-multilingual', compact('model'));
        } else {
            return $this->render('form-not-multilingual', compact('model'));
        }
    }

}
