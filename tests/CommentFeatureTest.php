<?php
namespace WebDevEtc\ContactEtc\Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Config;
use Mail;
use WebDevEtc\ContactEtc\ContactFormConfigurator;
use WebDevEtc\ContactEtc\ContactEtcServiceProvider;
use WebDevEtc\ContactEtc\ContactForm;
use WebDevEtc\ContactEtc\FieldTypes\Email;
use WebDevEtc\ContactEtc\FieldTypes\Text;
use WebDevEtc\ContactEtc\FieldTypes\Textarea;
use WebDevEtc\ContactEtc\Mail\ContactEtcMail;


class CommentFeatureTest extends \Tests\TestCase
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

    public function test_add_comment_form()
    {

        /** @var ContactFormConfigurator $config */
        $config = app()->make(ContactFormConfigurator::class);
        $config->addContactForm(ContactForm::newContactForm(ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY)
            ->sendTo("test@example.com")
            ->addFields([
                    new Email('testemail'),
                    new Text('testname'),
                    new Textarea('testmessage'),
                ]
            )
        );

        $resp = $this->call("GET", route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY));

        $this->assertContains(route("contactetc.send." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $resp->getContent());
        $this->assertContains('testemail', $resp->getContent());
        $this->assertContains('testname', $resp->getContent());
        $this->assertContains('testmessage', $resp->getContent());


    }

    public function test_submit_correct_contact_form_data()
    {


        \Mail::fake();


        /** @var ContactFormConfigurator $config */
        $config = app()->make(ContactFormConfigurator::class);
        $config->addContactForm(ContactForm::newContactForm(ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY)
            ->sendTo("test@example.com")
            ->addFields([
                    Email::newNamed('testemail')->markAsRequiredField(),
                    (new Text('testname'))->markAsRequiredField(),
                    (new Textarea('testmessage'))->markAsRequiredField(),
                ]
            )
        );


        $this->withoutMiddleware(VerifyCsrfToken::class);


        $data = [
            'testemail' => "test@example.com",
            'testname' => "testname",
            "testmessage" => "testmessage",
        ];

        /** @var \Illuminate\Foundation\Testing\TestResponse $resp */
        $resp = $this->post(route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $data);

        $this->assertTrue($resp->isOk());

        \Mail::assertSent(ContactEtcMail::class);


        $data = [
            'testemail' => "",
            'testname' => "testname",
            "testmessage" => "testmessage",
        ];

        /** @var \Illuminate\Foundation\Testing\TestResponse $resp */
        $resp = $this->post(route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $data);
        $this->assertTrue(!$resp->isOk());
        $resp->assertSessionHasErrors();

        $data = [
            'testemail' => "test@example.com",
            'testname' => "",
            "testmessage" => "testmessage",
        ];

        /** @var \Illuminate\Foundation\Testing\TestResponse $resp */
        $resp = $this->post(route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $data);
        $this->assertTrue(!$resp->isOk());
        $resp->assertSessionHasErrors();

        $data = [
            'testemail' => "test@example.com",
            'testname' => "testname",
            "testmessage" => "",
        ];

        /** @var \Illuminate\Foundation\Testing\TestResponse $resp */
        $resp = $this->post(route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $data);
        $this->assertTrue(!$resp->isOk());
        $resp->assertSessionHasErrors();


        $data = [
            'testemail' => "NOT AN EMAIL",
            'testname' => "testname",
            "testmessage" => "testmessage",
        ];

        /** @var \Illuminate\Foundation\Testing\TestResponse $resp */
        $resp = $this->post(route("contactetc.form." . ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY), $data);
        $this->assertTrue(!$resp->isOk());


    }

}
