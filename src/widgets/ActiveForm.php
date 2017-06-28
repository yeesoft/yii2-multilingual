<?php

namespace yeesoft\multilingual\widgets;

use yeesoft\multilingual\containers\MultilingualFieldContainer;
use yeesoft\multilingual\widgets\FormLanguageSwitcher;

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

        $isNonMultilingual = (isset($options['multilingual']) && $options['multilingual'] === false);
        $isFieldMultilingual = (isset($options['multilingual']) && $options['multilingual']);
        $isAttributeMultilingual = ($model->getBehavior('multilingual') && $model->hasMultilingualAttribute($attribute));

        if (!$isNonMultilingual && ($isFieldMultilingual || $isAttributeMultilingual)) {
            $languages = array_keys($model->getBehavior('multilingual')->languages);
            
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
        return FormLanguageSwitcher::widget(['model' => $model, 'view' => $view]);
    }
}