<?php

namespace WebDevEtc\ContactEtc;

use Illuminate\Support\ServiceProvider;
use WebDevEtc\ContactEtc\Commands\MakeContactEtcForm;
use WebDevEtc\ContactEtc\FieldGenerator\GetContactFormFieldData;
use WebDevEtc\ContactEtc\FieldGenerator\FieldGeneratorInterface;
use WebDevEtc\ContactEtc\Handlers\HandleContactSubmission;

/**
 * Class ContactEtcMainServiceProvider
 * @package WebDevEtc\ContactEtc
 */
class ContactEtcServiceProvider extends ServiceProvider
{
    /**
     * The default contact form key.
     * This is used so we can set up the routes for a contact form.
     */
    const DEFAULT_CONTACT_FORM_KEY = 'main_contact_form';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutes();
        $this->publishesFiles();
        $this->loadViewsFrom(__DIR__ . "/Views/contactetc", 'contactetc');
        $this->commands([
            MakeContactEtcForm::class
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->makeBindings();
    }


    /**
     * Load the routes (if enabled in config)
     * If you want to have more than the default contact form you must edit your own web.php routes file. See the docs for details.
     */
    protected function loadRoutes()
    {
        if (config("blogetc.include_default_routes", true)) {
            // load default routes
            include(__DIR__ . "/routes.php");
        }
    }

    /**
     * Views + ContactEtc config (contactetc.php)
     *
     * tagged with 'contactetc'
     *
     * Use it with:
     * php artisan make:contactetcform MainContactForm
     */
    protected function publishesFiles()
    {
        $tag = 'contactetc';
        $this->publishes([
            __DIR__ . '/Views/contactetc' => base_path('resources/views/vendor/contactetc'),
            __DIR__ . '/Config/contactetc.php' => config_path('contactetc.php'),
        ], $tag);
    }

    /**
     * make bindings
     */
    protected function makeBindings()
    {
        $this->app->bind(FieldGeneratorInterface::class, function () {
            // this is a bit of a helper really. Not ideal. todo: refactor
            return new GetContactFormFieldData();
        });
        $this->app->bind(Handlers\HandlerInterface::class, function () {
            // the class that takes the input, and does what it needs to (email it!)
            return new HandleContactSubmission();
        });

        $this->app->singleton(ContactFormConfigurator::class, function () {
            // the configurator - it holds a collection of all ContactForm's (which have all the details about who to send to, what fields to use, etc)
            return ContactFormConfigurator::createNew();
        });
    }
}


