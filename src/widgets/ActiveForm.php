<?php

namespace yeesoft\multilingual\widgets;

use Yii;
use yeesoft\multilingual\widgets\FormLanguageSwitcher;
use yeesoft\multilingual\containers\MultilingualFieldContainer;

/**
 * Multilingual ActiveForm
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     */
    public $fieldClass = 'yeesoft\multilingual\widgets\ActiveField';

    /**
     * 
     * @param \yii\base\Model $model
     * @param type $attribute
     * @param type $options
     * @return MultilingualFieldContainer
     */
    public function field($model, $attribute, $options = [])
    {
        $fields = [];

        $notMultilingual = (isset($options['multilingual']) && $options['multilingual'] === false);
        $multilingualField = (isset($options['multilingual']) && $options['multilingual']);
        $multilingualAttribute = ($model->getBehavior('multilingual') && $model->hasMultilingualAttribute($attribute));

        if (!$notMultilingual && ($multilingualField || $multilingualAttribute)) {
            if($multilingualAttribute){
                $languages = array_keys($model->getBehavior('multilingual')->languages);
            } else {
                $languages = (!empty($options['languages'])) ? array_keys($options['languages']) : [Yii::$app->language];
            }
            
            foreach ($languages as $language) {
                $fields[] = parent::field($model, $attribute, array_merge($options, ['language' => $language]));
            }

        } else {
            return parent::field($model, $attribute, $options);
        }

        return new MultilingualFieldContainer(['fields' => $fields]);
    }
    
    /**
     * Renders form language switcher.
     * 
     * @param \yii\base\Model $model
     * @param string $view
     * @return string
     */
    public function languageSwitcher($model, $view = null)
    {
        $languages = ($model->getBehavior('multilingual')) ? $languages = $model->getBehavior('multilingual')->languages : [];
        
        return FormLanguageSwitcher::widget(['languages' => $languages, 'view' => $view]);
    }
}