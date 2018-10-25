<?php namespace WebDevEtc\ContactEtc\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Mail\Mailer;
use WebDevEtc\ContactEtc\ContactEtcServiceProvider;
use WebDevEtc\ContactEtc\ContactForm;
use WebDevEtc\ContactEtc\Events\ContactFormError;
use WebDevEtc\ContactEtc\Events\ContactFormSent;
use WebDevEtc\ContactEtc\Events\ContactFormSubmitted;
use WebDevEtc\ContactEtc\FieldGenerator\FieldGeneratorInterface;
use WebDevEtc\ContactEtc\Handlers\HandlerInterface;
use WebDevEtc\ContactEtc\Requests\ContactEtcSubmittedRequest;

/**
 * Class ContactEtcController
 * @package WebDevEtc\ContactEtc\Controllers
 */
class ContactEtcController extends Controller
{
    /** @var  ContactForm */
    protected $contactForm;

    /**
     * Show the contact form.
     *
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     * @return mixed
     */
    public function form(FieldGeneratorInterface $fieldGenerator, $contact_form_name = ContactEtcServiceProvider::DEFAULT_CONTACT_FORM_KEY)
    {
        $this->getContactForm($fieldGenerator, $contact_form_name);

        return view("contactetc::form", $this->contactForm->view_params['form_view_vars'])
            ->withFormUrl(route('contactetc.send.' . $contact_form_name))
            ->withContactFormDetails($this->contactForm)
            ->withFields($this->contactForm->fields());
    }

    /**
     * Send the message, and show the confirmation view.
     *
     * @param ContactEtcSubmittedRequest $request
     * @param Mailer $mail
     * @param FieldGeneratorInterface $fieldGenerator
     * @param HandlerInterface $handler
     * @param $contact_form_name
     *
     * @return \Illuminate\View\View
     */
    public function send(ContactEtcSubmittedRequest $request, Mailer $mail, FieldGeneratorInterface $fieldGenerator, HandlerInterface $handler, string $contact_form_name)
    {

        $this->getContactForm($fieldGenerator, $contact_form_name);

        event(new ContactFormSubmitted($request->all(), $this->contactForm));

        // handle the submission (i.e. send the email!)
        if (!$handler->handleContactSubmission($mail, $request->all(), $this->contactForm)) {
            // there was an error...
            return $this->error($request, $handler);
        }

        event(new ContactFormSent($request->all(), $this->contactForm));

        return view("contactetc::sent", $this->contactForm->view_params['sent_view_vars']);

    }

    /**
     * @param ContactEtcSubmittedRequest $request
     * @param HandlerInterface $handler
     * @param $contactFormDetails
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function error(ContactEtcSubmittedRequest $request, HandlerInterface $handler)
    {
// uh oh, an error occurred
        event(new ContactFormError($request->all(), $this->contactForm, $handler->getErrors()));
        return back()
            ->withInput()
            ->withErrors($handler->getErrors());
    }

    /**
     * @param FieldGeneratorInterface $fieldGenerator
     * @param string $contact_form_name
     * @return mixed
     */
    protected function getContactForm(FieldGeneratorInterface $fieldGenerator, string $contact_form_name)
    {
        $this->contactForm = $fieldGenerator->contactFormNamed($contact_form_name);
    }

}