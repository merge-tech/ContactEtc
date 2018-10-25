<?php

namespace WebDevEtc\ContactEtc\FieldGenerator;


interface FieldGeneratorInterface
{
    /**
     * Returns the requested ContactForm object
     *
     * @param $contact_form_name
     * @return ContactForm
     * @throws \Exception
     */
    public function contactFormNamed($contactFieldId);

}