<?php

namespace yeesoft\multilingual\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yeesoft\multilingual\helpers\MultilingualHelper;
use yii\helpers\Url;

class LanguageSwitcher extends \yii\base\Widget
{
    const VIEW_LINKS = 'links';
    const VIEW_PILLS = 'pills';
    const DISPLAY_CODE = 'code';
    const DISPLAY_TITLE = 'title';

    /**
     * Available languages. It can be a simple array ['en-US', 'es'] or an associative 
     * array ['en-US' => 'English', 'es' => 'Español'].
     * 
     * @var array
     */
    public $languages;

    /**
     * @var array List of language redirects.
     * 
     * For example,
     *
     * ```php
     * [
     *   'en-US' => 'en',
     *   'pt-BR' => 'pt',
     * ]
     * ```
     */
    public $languageRedirects;

    /**
     * @var string View file of switcher. Could be `links`, `pills` or custom view.
     */
    public $view = self::VIEW_PILLS;

    /**
     * @var string  code | title
     */
    public $display = self::DISPLAY_TITLE;

    /**
     * @var string current language.
     */
    protected $_currentLanguage;

    /**
     * @var array default views of switcher. 
     */
    protected $_reservedViews = [
        'links' => '@vendor/yeesoft/yii2-multilingual/src/views/switcher/links',
        'pills' => '@vendor/yeesoft/yii2-multilingual/src/views/switcher/pills',
    ];

    public function init()
    {
        parent::init();

        $this->_currentLanguage = Yii::$app->language;
        $this->languages = MultilingualHelper::getLanguages($this->languages, $this);
        $this->languageRedirects = MultilingualHelper::getLanguageRedirects($this->languageRedirects);
    }

    public function run()
    {
        if (count($this->languages) > 1) {
            $view = isset($this->_reservedViews[$this->view]) ? $this->_reservedViews[$this->view] : $this->view;
            list($route, $params) = Yii::$app->getUrlManager()->parseRequest(Yii::$app->getRequest());
            /*echo '<pre>';
            print_r(Yii::$app->getUrlManager()->parseRequest(Yii::$app->getRequest()));*/

            $params = ArrayHelper::merge(Yii::$app->getRequest()->get(), $params);
            $url = isset($params['route']) ? $params['route'] : $route;
	        /*print_r($params);
	        print_r(MultilingualHelper::getDisplayLanguages($this->languages, $this->languageRedirects));
			die();*/

	        $languages = $this->languages;
	        foreach($languages as $languageCode => $languageTitle) {
		        $temp = [];
		        $temp['label'] = $languageTitle;
		        $homePageMultilingualRoute = Yii::$app->defaultRoute;
		        if (is_array($homePageMultilingualRoute) && count($homePageMultilingualRoute) >= 2) {
			        $homePageMultilingualRoute['language'] = $languageCode;
		        }

		        $temp['url'] = (Yii::$app->controller->route == Yii::$app->errorHandler->errorAction) ?
			        Url::to($homePageMultilingualRoute) : Url::current(['language' => $languageCode]);

		        echo '<pre>';
		        print_r(MultilingualHelper::getDisplayLanguageCode(Yii::$app->language, $this->languageRedirects));
		        die();
		        $temp['active'] = ($languageCode == MultilingualHelper::getDisplayLanguageCode($this->_currentLanguage, $this->languageRedirects));
		        $languageItems[] = $temp;
	        }

	        return $this->render($view, [
	        	'languages' => $languageItems,
            ]);
        }
    }

}
