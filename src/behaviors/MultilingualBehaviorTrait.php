<?php

namespace yeesoft\multilingual\behaviors;

use yii\base\UnknownPropertyException;
use yeesoft\multilingual\helpers\MultilingualHelper;

trait MultilingualBehaviorTrait
{

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name) || $this->hasMultilingualAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $this->hasMultilingualAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if ($this->hasMultilingualAttribute($name)) {
                return $this->getMultilingualAttribute($name);
            }
            // @codeCoverageIgnoreStart
            else {
                throw $e;
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            if ($this->hasMultilingualAttribute($name)) {
                $this->setMultilingualAttribute($name, $value);
            }
            // @codeCoverageIgnoreStart
            else {
                throw $e;
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function __isset($name)
    {
        if (!parent::__isset($name)) {
            return $this->hasMultilingualAttribute($name);
        } else {
            return true;
        }
    }

    /**
     * Whether is attribute multilingual.
     * 
     * @param string $attribute
     * @return bool
     */
    public function isAttributeMultilingual($attribute)
    {
        return array_key_exists($attribute, $this->_multilingualAttributes);
    }

    /**
     * Whether an attribute exists
     * @param string $name the name of the attribute
     * @return boolean
     */
    public function hasMultilingualAttribute($name)
    {
        return in_array($name, $this->attributes) || array_key_exists($name, $this->_multilingualAttributes);
    }

    /**
     * @param string $name the name of the attribute
     * @return string the attribute value
     */
    public function getMultilingualAttribute($name)
    {
        if (array_key_exists($name, $this->_multilingualAttributes)) {
            return $this->_multilingualAttributes[$name];
        } elseif (in_array($name, $this->attributes)) {
            $name = $this->getAttributeName($name, $this->_currentLanguage);
            return $this->_multilingualAttributes[$name];
        }
        return null;
    }

    /**
     * @param string $name the name of the attribute
     * @param string $value the value of the attribute
     */
    public function setMultilingualAttribute($name, $value)
    {
        if (in_array($name, $this->attributes)) {
            $name = $this->getAttributeName($name, $this->_currentLanguage);
        }

        $this->_multilingualAttributes[$name] = $value;
    }

    /**
     * Return multilingual attribute label.
     * 
     * @param string $attribute
     * @return string
     */
    public function getMultilingualAttributeLabel($attribute)
    {
        $attributeLabels = $this->getMultilingualAttributeLabels();
        return isset($attributeLabels[$attribute]) ? $attributeLabels[$attribute] : $attribute;
    }

    /**
     * @return array generate multilingual attribute labels.
     */
    protected function getMultilingualAttributeLabels()
    {
        if (!$this->_attributeLabels) {
            foreach ($this->attributes as $attribute) {
                foreach ($this->languages as $code => $language) {
                    $title = $this->owner->getAttributeLabel($attribute);
                    $attributeName = $this->getAttributeName($attribute, $code);

                    $this->_attributeLabels[$attributeName] = $this->generateAttributeLabel($title, $code, $language);
                }
            }
        }

        return $this->_attributeLabels;
    }

    protected function generateAttributeLabel($label, $code, $language)
    {
        return strtr($this->attributeLabelPattern, [
            '{code}' => $code,
            '{label}' => $label,
            '{language}' => $language,
        ]);
    }

    /**
     * @param string $attribute
     * @param string $language
     * @return string
     */
    protected function getAttributeName($attribute, $language)
    {
        return MultilingualHelper::getAttributeName($attribute, $language);
    }

}
