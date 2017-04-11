Multilingual URL Manager
============

Multilingual URL Manager helps you to achieve user friendly and search engine 
friendly URLs for your multilingual site. You can use language switcher to switch 
between languages.


Configuration
------

1. To use MultilingualUrlManager, you have to update `urlManager` component configuration 
in your application configuration. This is an example of configuration:

```php
<?php

    'components' => [
        'urlManager' => [
            'class' => 'yeesoft\multilingual\web\MultilingualUrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '<action:(login|logout)>' => 'site/<action>',
                '<language:([a-zA-Z-]{2,5})?>' => 'site/index',
                '<language:([a-zA-Z-]{2,5})?>/<action:[\w \-]+>' => 'site/<action>',
                '<language:([a-zA-Z-]{2,5})?>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
            'excludedActions' => [
                'site/login',
                'site/logout',
            ],
            'languages' => [
                'en-US' => 'English',
                'es' => 'Español',
            ],
            'languageRedirects' => [
                'en-US' => 'en',
            ]
        ],
    ]
```

Application with this configuration will generate user friendly URLs. Example:

- mysite.com/en/contacts
- mysite.com/es/contacts
- mysite.com/en/user/update
- mysite.com/login
- mysite.com/logout

As you can see `site/login` and `site/logout` actions are not multilingual. 
Multilingual rules are not applied to actions that are specified in `excludedActions`. 

You can specify `languages` and `languageRedirects` in application parameters. In 
this case you do need to specify these parameters in `urlManager`. Also you will 
be sure that you have the same `languages` and `languageRedirects` for all behaviors, 
forms, widgets and URL manager.


2. The following code shows how can we render `LanguageSwitcher` widget:

```php
<?php

    use yeesoft\multilingual\widgets\LanguageSwitcher;

    echo LanguageSwitcher::widget([
        'languages' => [
            'en-US' => 'English',
            'es' => 'Español',
        ],
        'languageRedirects' => [
            'en-US' => 'en',
        ]
    ]);
```

MultilingualUrlManager Attributes
------

Attribute | Description
----------|------------
**languages** | List of available languages. For example: ```['en-US' => 'English', 'es' => 'Español']```. You can specify `languages` either in the URL manager or in application's parameters.
**languageRedirect** | List of language code redirects.. For example: ```['en-US' => 'en', 'pt-BR' => 'pt']```. You can specify `languageRedirect` either in the URL manager or in application's parameters.
excludedActions | List of not multilingual actions. Should contain action id, including controller id and module id (if module is used). For example: ['site/logout', 'auth/default/oauth2']. Default: `[]`.
forceLanguageParam | Name of param that is used to forced including of the language param to the url. Is used for generating links for `excludedActions` in cases when we need include language param to the link. For example in `LanguageSwitcher`. Default: `forceLanguageParam`.

**Bold** attributes are required.

Also you can use attributes from parent `yii\web\UrlManager` class.

LanguageSwitcher Attributes
------

Attribute | Description
----------|------------
**languages** | List of available languages. For example: ```['en-US' => 'English', 'es' => 'Español']```. You can specify `languages` either in the URL manager or in application's parameters.
**languageRedirect** | List of language code redirects.. For example: ```['en-US' => 'en', 'pt-BR' => 'pt']```. You can specify `languageRedirect` either in the URL manager or in application's parameters.
view | View file of switcher. Could be `links`, `pills` or custom view. Default: `LanguageSwitcher::VIEW_PILLS`.
display | Indicate what switcher should display as a title of buttons: either `code` or `title`. Default: `LanguageSwitcher::DISPLAY_TITLE`.

**Bold** attributes are required. 