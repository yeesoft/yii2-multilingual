<?php

namespace yeesoft\multilingual\containers;

use yii\base\BaseObject;
use yeesoft\multilingual\helpers\MultilingualHelper;

class MultilingualFieldContainer extends BaseObject
{

    /**
     * Fields.
     * 
     * @var array 
     */
    public $fields;

    public function __call($method, $arguments)
    {
        $_html = '';
        foreach ($this->fields as $field) {
            $_html .= call_user_func_array(array($field, $method), $this->updateArguments($method, $arguments, $field));
        }

        return $_html;
    }

    /**
     * Updates id for multilingual inputs with custom id.
     * 
     * @param string $method
     * @param array $arguments
     * @param mixed $field
     * @return array
     */
    protected function updateArguments($method, $arguments, $field)
    {
        if ($method == 'widget' && isset($arguments[1]['options']['id'])) {
            $arguments[1]['options']['id'] = MultilingualHelper::getAttributeName($arguments[1]['options']['id'], $field->language);
        }

        if (in_array($method, ['textInput', 'textarea', 'radio', 'checkbox', 'fileInput', 'hiddenInput', 'passwordInput']) && isset($arguments[0]['id'])) {
            $arguments[0]['id'] = MultilingualHelper::getAttributeName($arguments[0]['id'], $field->language);
        }

        if (in_array($method, ['input', 'dropDownList', 'listBox', 'radioList', 'checkboxList']) && isset($arguments[1]['id'])) {
            $arguments[1]['id'] = MultilingualHelper::getAttributeName($arguments[1]['id'], $field->language);
        }

        return $arguments;
    }

}
