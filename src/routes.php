<?php

// you can disable this by setting the config('contactetc.include_default_routes') to false.
// then you can manually add your own routes to your web.php file.


use WebDevEtc\ContactEtc\ContactEtcServiceProvider;

Route::group([

    'middleware' => ['web'],
    'prefix' => config('contactetc.contact_us_slug', 'contact-us')],
    function () {
        // default form:
        $contact_field_group_name = ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY;

        // the contact form:
        Route::get("/", '\WebDevEtc\ContactEtc\Controllers\ContactEtcController@form')
            ->name('contactetc.form.' . $contact_field_group_name)//contactetc.form.main_contact_form
            ->defaults('contactFormId', $contact_field_group_name);

        // processing the submitted data:
        Route::post("/", '\WebDevEtc\ContactEtc\Controllers\ContactEtcController@send')
            ->name('contactetc.send.' . $contact_field_group_name)// contactetc.send.main_contact_form
            ->defaults('contactFormId', $contact_field_group_name);

});





// want to add more than one? Please go to https://webdevetc.com/contactetc to read the docs on how to do this.

