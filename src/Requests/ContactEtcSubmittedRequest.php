<?php

namespace WebDevEtc\ContactEtc\Requests;

use Illuminate\Foundation\Http\FormRequest;
use WebDevEtc\ContactEtc\ContactFormConfigurator;
use WebDevEtc\ContactEtc\FieldTypes\BaseFieldInterface;
use WebDevEtc\ContactEtc\FieldTypes\BaseFieldType;

/**
 * Class ContactEtcSubmittedRequest
 * @package WebDevEtc\ContactEtc\Requests
 */
class ContactEtcSubmittedRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     * A contact form would be a bit useless if guests could not contact you
     * so always return true for this...
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Generate the rules, based on the currently selected (requested) contact form
     *
     * In your config/contactetc.php, you can define different contact forms.
     * This is what $this->contactFormId is referencing.
     *
     * @param ContactFormConfigurator $contactFormConfigurator
     * @return array
     * @throws \Exception
     */
    public function rules(ContactFormConfigurator $contactFormConfigurator)
    {
        // $this->contactFormId should be set by the route (the             ->defaults('contactFormId', $contact_field_group_name); part)
        $contactForm = $contactFormConfigurator->getContactForm($this->contactFormId);
        // now we have the details (including where to send email to, and details about the fields for this contact form)
        // so lets generate the rules...
        $rules = [];
        /** @var BaseFieldInterface|BaseFieldType $field */
        foreach ($contactForm->fields() as $field) {
            $rules[$field->field_name] = $field->rules();
        }
        return $rules;
    }


}
