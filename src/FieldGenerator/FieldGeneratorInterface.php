<?php

namespace WebDevEtc\ContactEtc\FieldGenerator;


interface FieldGeneratorInterface
{
    /**
     * Returns the requested ContactForm object
     *
     * @param $contactFieldId
     * @return ContactForm
     * @throws \Exception
     */
    public function contactFormNamed($contactFieldId);

}