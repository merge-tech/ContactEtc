<?php

use WebDevEtc\ContactEtc\ContactFormConfigurator;
use WebDevEtc\ContactEtc\ContactEtcServiceProvider;
use WebDevEtc\ContactEtc\ContactForm;
use WebDevEtc\ContactEtc\FieldGenerator\GetContactFormFieldData;
use WebDevEtc\ContactEtc\FieldTypes\Email;
use WebDevEtc\ContactEtc\FieldTypes\Textarea;
use WebDevEtc\ContactEtc\FieldTypes\Text;

class RulesTest extends \Tests\TestCase
{


    /** Setup the contact form config */
    public function setUp()
    {
        parent::setUp();
        $this->app->singleton(ContactFormConfigurator::class, function () {
            // send a custom array of what config files we want to (by default) include
            // this stops errors being thrown that are not relevant to any testing
            return ContactFormConfigurator::createNew([
                __DIR__ . "/TestConfigs/main_contact_form_config.php"
            ]);
        });
    }

    /** Make some basic tests that the ContactEtcSubmittedRequest request returns some rules */
    public function test_basic_rules()
    {
        $request = new \WebDevEtc\ContactEtc\Requests\ContactEtcSubmittedRequest();
        $request->contactFormId = ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY;
        $resp = $request->rules(app()->make(ContactFormConfigurator::class));

        $this->assertTrue(is_array($resp));

        // while we are here, quickly test this:
        $this->assertTrue($request->authorize());

    }

}
