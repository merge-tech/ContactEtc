<?php


use Illuminate\Contracts\Mail\Mailer;
use WebDevEtc\ContactEtc\ContactFormConfigurator;
use WebDevEtc\ContactEtc\ContactEtcServiceProvider;
use WebDevEtc\ContactEtc\ContactForm;
use WebDevEtc\ContactEtc\FieldTypes\Email;
use WebDevEtc\ContactEtc\FieldTypes\Text;
use WebDevEtc\ContactEtc\FieldTypes\Textarea;
use WebDevEtc\ContactEtc\Handlers\HandleContactSubmission;

class HandleContactSubmissionTest extends \Tests\TestCase
{


    public function setUp()
    {
        parent::setUp();
        app()->singleton(ContactFormConfigurator::class, function () {
            // send a custom array of what config files we want to (by default) include
            // this stops errors being thrown that are not relevant to any testing
            return ContactFormConfigurator::createNew([
                __DIR__."/TestConfigs/main_contact_form_config.php"
            ]);
        });
    }

    public function test_basic_submission()
    {


        /** @var ContactFormConfigurator $config */
        $config = app()->make(ContactFormConfigurator::class);
        $config->addContactForm(ContactForm::newContactForm(ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY)
            ->sendTo("test@example.com")
            ->addFields([
                    new Email('testemail'),
                    new Email('testemail2'),
                    new Email('testemail3'),
                    new Email('testemail4'),
                ]
            )
        );

        $input = [
            'test_textarea' => str_random(),
            'test_text' => str_random(),
            'test_email' => str_random() . "@example.com",
        ];
        $mail = app()->make(   Mailer::class);
        $handler = new HandleContactSubmission();
        $resp = $handler->handleContactSubmission(
            $mail,
            $input,
            $config->getContactForm(ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY) ,
            true);

        $this->assertTrue($resp);
        $this->assertTrue(is_array($handler->getErrors()));
        $this->assertTrue(count($handler->getErrors()) == 0);

    }


}

