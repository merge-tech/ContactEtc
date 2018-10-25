<?php
namespace WebDevEtc\ContactEtc\Tests;

use WebDevEtc\ContactEtc\ContactEtcServiceProvider;


class MiniTest extends \Tests\TestCase
{

    public function test_default_form_page_id_is_still_main_contact_form()
    {

        // this is a simple test to check that the const DEFAULT_FORM_PAGE_ID is still set to main_contact_form.
        // there is no reason why it should be set to anything else.
        $this->assertTrue(ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY == 'main_contact_form');
    }
}
