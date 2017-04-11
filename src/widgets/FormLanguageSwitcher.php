<?php

namespace yeesoft\multilingual\widgets;

use Yii;

/**
 * Widget to display buttons to switch languages in forms
 */
class FormLanguageSwitcher extends \yii\base\Widget
{

    /**
     * Form model.
     * 
     * @var \yii\base\Model 
     */
    public $model;

    /**
     *
     * @var string view file
     */
    public $view;

    /**
     * List of languages.
     * 
     * @var array 
     */
    private $_languages;

    /**
     *
     * @var string default view file
     */
    private $_defaultView = '@vendor/yeesoft/yii2-multilingual/src/views/form-switcher/pills';

    public function init()
    {
        $this->view = $this->view ?: $this->_defaultView;

        if ($this->model->getBehavior('multilingual')) {
            $this->_languages = $this->model->getBehavior('multilingual')->languages;
        }

        parent::init();
    }

    public function run()
    {
        if ($this->_languages) {
            return $this->render($this->view, [
                        'language' => Yii::$app->language,
                        'languages' => $this->_languages,
            ]);
        }
    }

}
