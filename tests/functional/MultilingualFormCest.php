<?php

use yii\db\Query;

class MultilingualFormCest
{

    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/form');
    }

    public function generateMultilingualFormWithSwitcher(\FunctionalTester $I)
    {
        $I->seeElement('form#multilang');
        $I->seeElement('ul.form-language-switcher');

        $I->see('English', 'ul.form-language-switcher li a[data-lang="en-US"]');
        $I->see('Español', 'ul.form-language-switcher li a[data-lang="es"]');
        $I->see('Português', 'ul.form-language-switcher li a[data-lang="pt-BR"]');

        $I->seeElement('.field-page-title_en_us[data-lang="en-US"]');
        $I->seeElement('input[name="Page[title_en_us]"]');
        $I->see('Title [English]', 'label[for="page-title_en_us"]');

        $I->seeElement('.field-page-title_es[data-lang="es"]');
        $I->seeElement('input[name="Page[title_es]"]');
        $I->see('Title [Español]', 'label[for="page-title_es"]');

        $I->seeElement('.field-page-title_pt_br[data-lang="pt-BR"]');
        $I->seeElement('input[name="Page[title_pt_br]"]');
        $I->see('Title [Português]', 'label[for="page-title_pt_br"]');

        $I->canSeeInFormFields('form#multilang', [
            'Page[title_en_us]' => 'EN-US Title',
            'Page[title_es]' => 'ES Title',
            'Page[title_pt_br]' => 'PT Title',
            'Page[content_en_us]' => 'EN-US Content',
            'Page[content_es]' => 'ES Content',
            'Page[content_pt_br]' => 'PT Content',
        ]);

        $I->fillField('input[name="Page[title_en_us]"]', 'Updated English Title');
        $I->fillField('input[name="Page[title_es]"]', 'Updated Español Title');
        $I->fillField('input[name="Page[title_pt_br]"]', 'Updated Português Title');

        $I->fillField('textarea[name="Page[content_en_us]"]', 'Updated English Content');
        $I->fillField('textarea[name="Page[content_es]"]', 'Updated Español Content');
        $I->fillField('textarea[name="Page[content_pt_br]"]', 'Updated Português Content');

        $I->click('#multilang button[type="submit"]');

        $I->amOnPage('site/form');

        $translations = (new Query)
                ->from('page_lang')
                ->where(['owner_id' => 1])
                ->all();

        PHPUnit_Framework_Assert::assertEquals([
                [
                'id' => 1,
                'owner_id' => 1,
                'language' => 'en-US',
                'title' => 'Updated English Title',
                'content' => 'Updated English Content',
            ],
                [
                'id' => 2,
                'owner_id' => 1,
                'language' => 'es',
                'title' => 'Updated Español Title',
                'content' => 'Updated Español Content',
            ],
                [
                'id' => 3,
                'owner_id' => 1,
                'language' => 'pt-BR',
                'title' => 'Updated Português Title',
                'content' => 'Updated Português Content',
            ],
                ], $translations);
    }

    public function generateMultilingualFormWithoutSwitcher(\FunctionalTester $I)
    {
        $I->amOnRoute('site/form-without-switcher');
        
        $I->seeElement('form#multilang');
        $I->dontSeeElement('ul.form-language-switcher');

        $I->dontSeeElement('#multilang li a[data-lang="en-US"]');
        $I->dontSeeElement('#multilang li a[data-lang="es"]');
        $I->dontSeeElement('#multilang li a[data-lang="pt-BR"]');

        $I->seeElement('.field-page-title_en_us[data-lang="en-US"]');
        $I->seeElement('input[name="Page[title_en_us]"]');
        $I->see('Title [English]', 'label[for="page-title_en_us"]');

        $I->seeElement('.field-page-title_es[data-lang="es"]');
        $I->seeElement('input[name="Page[title_es]"]');
        $I->see('Title [Español]', 'label[for="page-title_es"]');

        $I->seeElement('.field-page-title_pt_br[data-lang="pt-BR"]');
        $I->seeElement('input[name="Page[title_pt_br]"]');
        $I->see('Title [Português]', 'label[for="page-title_pt_br"]');

        $I->canSeeInFormFields('form#multilang', [
            'Page[title_en_us]' => 'Second EN-US Title',
            'Page[title_es]' => 'Second ES Title',
            'Page[title_pt_br]' => 'Second PT Title',
            'Page[content_en_us]' => 'Second EN-US Content',
            'Page[content_es]' => 'Second ES Content',
            'Page[content_pt_br]' => 'Second PT Content',
        ]);

        $I->fillField('input[name="Page[title_en_us]"]', 'Updated Second English Title');
        $I->fillField('input[name="Page[title_es]"]', 'Updated Second Español Title');
        $I->fillField('input[name="Page[title_pt_br]"]', 'Updated Second Português Title');

        $I->fillField('textarea[name="Page[content_en_us]"]', 'Updated Second English Content');
        $I->fillField('textarea[name="Page[content_es]"]', 'Updated Second Español Content');
        $I->fillField('textarea[name="Page[content_pt_br]"]', 'Updated Second Português Content');

        $I->click('#multilang button[type="submit"]');

        $I->amOnPage('site/form-without-switcher');

        $translations = (new Query)
                ->from('page_lang')
                ->where(['owner_id' => 2])
                ->all();

        PHPUnit_Framework_Assert::assertEquals([
                [
                'id' => 4,
                'owner_id' => 2,
                'language' => 'en-US',
                'title' => 'Updated Second English Title',
                'content' => 'Updated Second English Content',
            ],
                [
                'id' => 5,
                'owner_id' => 2,
                'language' => 'es',
                'title' => 'Updated Second Español Title',
                'content' => 'Updated Second Español Content',
            ],
                [
                'id' => 6,
                'owner_id' => 2,
                'language' => 'pt-BR',
                'title' => 'Updated Second Português Title',
                'content' => 'Updated Second Português Content',
            ],
                ], $translations);
    }
    
    public function generateMultilingualFormWithCustomInputId(\FunctionalTester $I)
    {
        $I->amOnRoute('site/form-with-input-id');
        
        $I->seeElement('form#multilang');

        $I->seeElement('.field-custom_id_en_us[data-lang="en-US"]');
        $I->seeElement('input#custom_id_en_us[name="Page[title_en_us]"]');
        $I->see('Title [English]', 'label[for="custom_id_en_us"]');

        $I->seeElement('.field-custom_id_es[data-lang="es"]');
        $I->seeElement('input#custom_id_es[name="Page[title_es]"]');
        $I->see('Title [Español]', 'label[for="custom_id_es"]');

        $I->seeElement('.field-custom_id_pt_br[data-lang="pt-BR"]');
        $I->seeElement('input#custom_id_pt_br[name="Page[title_pt_br]"]');
        $I->see('Title [Português]', 'label[for="custom_id_pt_br"]');

    }
    
    public function generateFormWithNotMultilingualModel(\FunctionalTester $I)
    {
        $I->amOnRoute('site/form-not-multilingual');

        $I->seeElement('form#multilang');
        $I->dontSeeElement('ul.form-language-switcher');

        $I->dontSeeElement('form#multilang a[data-lang="en-US"]');
        $I->dontSeeElement('form#multilang a[data-lang="es"]');

        $I->canSeeInFormFields('form#multilang', [
            'Image[src]' => 'http://www.mysite.com/image.jpg',
        ]);

        $I->fillField('input[name="Image[src]"]', 'Updated Image Source');
        $I->click('#multilang button[type="submit"]');

        $I->amOnPage('site/form-not-multilingual');

        $image = (new Query)->from('image')->where(['id' => 1])->all();

        PHPUnit_Framework_Assert::assertEquals([
                [
                'id' => 1,
                'src' => 'Updated Image Source',
            ]
        ], $image);
    }

//<form id="w0" action="/es/update" method="post" role="form">
//            
//<div class=" field-post-title_en_us required" data-lang="en-US" data-toggle="multilingual-field" style="display:none">
//<label class="control-label" for="post-title_en_us">Title En Us</label>
//<input type="text" id="post-title_en_us" class="form-control" name="Post[title_en_us]" value="EN-US Title" maxlength="255" aria-required="true">
//
//<p class="help-block help-block-error"></p>
//</div><div class="in field-post-title_es required" data-lang="es" data-toggle="multilingual-field" style="">
//<label class="control-label" for="post-title_es">Title Es</label>
//<input type="text" id="post-title_es" class="form-control" name="Post[title_es]" value="Español" maxlength="255" aria-required="true">
//
//<p class="help-block help-block-error"></p>
//</div>
//<div class="form-group field-post-slug">
//<label class="control-label" for="post-slug">Slug</label>
//<input type="text" id="post-slug" class="form-control" name="Post[slug]" value="en-us-title" maxlength="127">
//
//<p class="help-block help-block-error"></p>
//</div>
//            <div class=" field-post-content_en_us" data-lang="en-US" data-toggle="multilingual-field" style="display:none">
//<label class="control-label" for="post-content_en_us">Content En Us</label>
//<input type="text" id="post-content_en_us" class="form-control" name="Post[content_en_us]" value="">
//
//<p class="help-block help-block-error"></p>
//</div><div class="in field-post-content_es" data-lang="es" data-toggle="multilingual-field" style="">
//<label class="control-label" for="post-content_es">Content Es</label>
//<input type="text" id="post-content_es" class="form-control" name="Post[content_es]" value="">
//
//<p class="help-block help-block-error"></p>
//</div>
//            <button type="submit" class="btn btn-primary">Save</button>
//            </form>
//        </div>
}
