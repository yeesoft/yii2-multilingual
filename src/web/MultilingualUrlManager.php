<?php

namespace yeesoft\multilingual\web;

use Yii;
use yii\helpers\Url;
use yii\web\UrlManager;
use yii\web\Application;
use yii\web\NotFoundHttpException;
use yeesoft\multilingual\helpers\MultilingualHelper;

class MultilingualUrlManager extends UrlManager {

    /**
     * Available languages. It can be a simple array ['en-US', 'es'] or an associative 
     * array ['en-US' => 'English', 'es' => 'Español'].
     * 
     * @var array
     */
    public $languages;

    /**
     * @var array List of language code redirects.
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
     * List of not multilingual actions. Should contain action id, including 
     * controller id and module id (if module is used).
     * 
     * For example,
     * 
     * ```php
     * [
     *   'site/logout',
     *   'auth/default/oauth2',
     * ]
     * 
     * @var array 
     */
    public $excludedActions = [];

    /**
     * Name of param that is used to forced including of the language param to the
     * url. Is used for generating links for `excludedActions` in cases when we
     * need include language param to the link. For example in `LanguageSwitcher`.
     * 
     * @var string 
     */
    public $forceLanguageParam = 'forceLanguageParam';

    /**
     * When url not have language in get params and extract it by Session or Cookie redirect 
     * example: /controller/action => /lang/controller/action
     * @var boolean 
     */
    public $redirectOnStored = false;

    /**
     * @var string list of languages with applied language redirects.
     */
    protected $_displayLanguages;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->languages = MultilingualHelper::getLanguages($this->languages, $this);
        $this->languageRedirects = MultilingualHelper::getLanguageRedirects($this->languageRedirects);

        Yii::$app->on(Application::EVENT_BEFORE_ACTION, [$this, 'beforeAction']);
    }

    /**
     * Tracks language parameter for multilingual controllers.
     * 
     * @param ActionEvent $event
     * @return bool
     * @throws MethodNotAllowedHttpException|InvalidConfigException when the request method is not allowed.
     */
    public function beforeAction($event) {



        if (!Yii::$app->errorHandler->exception && count($this->languages) > 1) {

            // Set language by GET request, session or cookie
            if ($language = Yii::$app->getRequest()->get('language')) {

                Yii::$app->language = $this->getLanguageCode($language);
                Yii::$app->session->set('language', Yii::$app->language);
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => Yii::$app->session->get('language'),
                    'expire' => time() + 31536000 // one year
                ]));
            } elseif ($language = Yii::$app->session->get('language')) {

                Yii::$app->language = $this->getLanguageCode($language);

                if ($this->redirectOnStored) {
                    $this->redirecOnStored();
                }
            } elseif (isset(Yii::$app->request->cookies['language'])) {

                $language = Yii::$app->request->cookies['language']->value;
                Yii::$app->language = $this->getLanguageCode($language);

                if ($this->redirectOnStored) {
                    $this->redirecOnStored();
                }
            }

            Yii::$app->formatter->locale = Yii::$app->language;
        }

        return $event->isValid;
    }

    protected function redirecOnStored() {
        $params = \Yii::$app->request->get();
        $urlparam = Url::to(yii\helpers\ArrayHelper::merge(['/' . Yii::$app->controller->id . '/' . \Yii::$app->controller->action->id], $params));
        Yii::$app->response->redirect($urlparam);
    }

    /**
     * @inheritdoc
     */
    public function createUrl($params) {
        $forceLanguage = (isset($params[$this->forceLanguageParam]) && $params[$this->forceLanguageParam]);
        if ($forceLanguage) {
            unset($params[$this->forceLanguageParam]);
        }


        if (!$forceLanguage && ((isset($params['language']) && $params['language'] === false) || (isset($params[0]) && in_array($params[0], $this->excludedActions)))) {
            unset($params['language']);
            return parent::createUrl($params);
        }

        if (count($this->languages) > 1) {
            $languages = array_keys($this->getDisplayLanguages());
            //remove incorrect language param
            if (isset($params['language']) && !in_array($params['language'], $languages)) {
                unset($params['language']);
            }
            //trying to get language param
            if (!isset($params['language'])) {
                if (Yii::$app->session->has('language')) {
                    $language = Yii::$app->session->get('language');
                } elseif (isset(Yii::$app->request->cookies['language'])) {
                    $language = Yii::$app->request->cookies['language']->value;
                } else {
                    $language = Yii::$app->language;
                }
                if (in_array($language, $languages)) {
                    Yii::$app->language = $language;
                }
                $params['language'] = MultilingualHelper::getDisplayLanguageCode(Yii::$app->language, $this->languageRedirects);
            }
        }
        return parent::createUrl($params);
    }

    /**
     * @return array Returns list of languages with applied language redirects.
     */
    protected function getDisplayLanguages() {
        if (!isset($this->_displayLanguages)) {
            $this->_displayLanguages = MultilingualHelper::getDisplayLanguages($this->languages, $this->languageRedirects);
        }
        return $this->_displayLanguages;
    }

    /**
     * Returns code of language by its redirect language code.
     * 
     * @param string $redirectLanguageCode
     * @return string
     * @throws NotFoundHttpException
     */
    protected function getLanguageCode($redirectLanguageCode) {
        $sourceLanguage = MultilingualHelper::getRedirectedLanguageCode($redirectLanguageCode, $this->languageRedirects);

        if (!isset($this->languages[$sourceLanguage])) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        return $sourceLanguage;
    }

}
