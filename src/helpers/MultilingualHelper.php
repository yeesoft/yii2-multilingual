<?php

namespace yeesoft\multilingual\helpers;

use Yii;
use yii\helpers\Inflector;
use yii\base\InvalidConfigException;

class MultilingualHelper
{

    /**
     * Validates and returns list of languages.
     * 
     * @param array $languages
     * @param \yii\base\BaseObject $owner
     * @return array
     * @throws InvalidConfigException
     */
    public static function getLanguages($languages, $owner)
    {
        if (!$languages && isset(Yii::$app->params['languages'])) {
            $languages = Yii::$app->params['languages'];
        }

        if (!is_array($languages) || empty($languages)) {
            throw new InvalidConfigException('Please specify array of available languages in the '
            . get_class($owner) . ' or in the application parameters');
        }

        return $languages;
    }

    /**
     * Validates and returns list of language redirects.
     * 
     * @param array $languageRedirects
     * @return array
     */
    public static function getLanguageRedirects($languageRedirects)
    {
        if (!$languageRedirects && isset(Yii::$app->params['languageRedirects'])) {
            $languageRedirects = Yii::$app->params['languageRedirects'];
        }

        return $languageRedirects;
    }

    /**
     * Returns code of language by its redirect language code.
     * 
     * @param string $redirectLanguageCode
     * @param array $redirects
     * @return string
     */
    public static function getRedirectedLanguageCode($redirectLanguageCode, $redirects = null)
    {
        if (!$redirects && isset(Yii::$app->params['languageRedirects'])) {
            $redirects = Yii::$app->params['languageRedirects'];
        }

        if (!is_array($redirects) || empty($redirects)) {
            return $redirectLanguageCode;
        }

        $codes = array_flip($redirects);
        return (isset($codes[$redirectLanguageCode])) ? $codes[$redirectLanguageCode] : $redirectLanguageCode;
    }

    /**
     * Returns list of languages with applied language redirects.
     * 
     * @param array $languages
     * @param array $languageRedirects
     * @return array
     */
    public static function getDisplayLanguages($languages, $languageRedirects)
    {
        foreach ($languages as $key => $value) {
            $key = (isset($languageRedirects[$key])) ? $languageRedirects[$key] : $key;
            $redirects[$key] = $value;
        }
        return $redirects;
    }

    /**
     * Returns language code that will be displayed on front-end.
     * 
     * @param string $language
     * @return string
     */
    public static function getDisplayLanguageCode($language, $languageRedirects)
    {
        return (isset($languageRedirects[$language])) ? $languageRedirects[$language] : $language;
    }
    
    /**
     * Updates attribute name to multilingual.
     * 
     * @param string $attribute
     * @param string $language
     * @return string
     */
    public static function getAttributeName($attribute, $language)
    {
        return $attribute . "_" . Inflector::camel2id(Inflector::id2camel($language), "_");
    }

}
