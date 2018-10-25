<?php

namespace WebDevEtc\ContactEtc\FieldGenerator;

use WebDevEtc\ContactEtc\ContactFormConfigurator;
use WebDevEtc\ContactEtc\ContactForm;

/**
 * Class FieldGenerator
 * @todo this is a bit of a helper class really. Maybe refactor.
 *
 * @package WebDevEtc\ContactEtc\FieldGenerator
 */
class GetContactFormFieldData implements FieldGeneratorInterface
{
    /**
     * Returns the requested ContactForm object
     *
     * @param $contact_form_name
     * @return ContactForm
     * @throws \Exception
     */
    public function contactFormNamed($contact_form_name)
    {
        /** @var ContactFormConfigurator $configurator */
        $configurator = app()->make(ContactFormConfigurator::class);

        /** @var ContactForm */
        return $configurator->getContactForm($contact_form_name);
    }
}