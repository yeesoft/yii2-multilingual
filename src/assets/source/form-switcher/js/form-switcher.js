yii.multilingual = (function ($) {
    var pub = {
        buttonSelector: '[data-toggle="pill"]',
        switcherSelector: '.form-language-switcher',
        fieldSelector: '[data-toggle="multilingual-field"]',
        init: function () {
            $(document).on('click', pub.switcherSelector + ' ' + pub.buttonSelector, switchLanguage);
            $(document).on('afterValidate', $(pub.switcherSelector).closest('form'), afterValidateAction);
        }
    };

    function switchLanguage(event) {
        var language = $(this).attr('data-lang');
        $(pub.fieldSelector).hide().filter('[data-lang="' + language + '"]').show();
    }
    function afterValidateAction(event) {
        var language = getLanguageWithErrors();
        if (language !== false) {
            $(pub.switcherSelector).find(pub.buttonSelector).filter('[data-lang="' + language + '"]').click();
        }
    }

    function getLanguageWithErrors() {
        var language = false;

        $(pub.switcherSelector).find(pub.buttonSelector).each(function (index, button) {
            var lang = $(button).data('lang');
            var errors = $(pub.fieldSelector).filter('[data-lang="' + lang + '"]').filter('.has-error');

            if (errors.length > 0) {
                language = lang;
                return false;
            }
        });

        return language;
    }

    return pub;
})(jQuery);
