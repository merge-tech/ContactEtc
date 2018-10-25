<?php namespace WebDevEtc\ContactEtc;


/**
 * Class ContactEtcFieldConfigurator
 * This holds a list of the contact form configs.
 * @package WebDevEtc\ContactEtc
 */
/**
 * Class ContactFormConfigurator
 * @package WebDevEtc\ContactEtc
 */
class ContactFormConfigurator
{

    /**
     * An array of the contact forms
     * @var array
     */
    protected $contact_forms = [];

    /**
     * Create a new instance of this configurator.
     * Auto load the config files.
     *
     * @param bool $load_configs
     * @return ContactFormConfigurator
     * @throws \Exception
     */
    public static function createNew($load_configs=false)
    {

        /** @var array $configs */
        $configs = self::getArrayOfConfigFilepaths($load_configs);

        /** @var ContactFormConfigurator $configurator */
        $configurator = new self;

        /** @var string $contact_form_filepath */
        foreach ($configs as $contact_form_filepath) {
            self::checkConfigFileExists($contact_form_filepath);
            $configurator->addContactForm(require($contact_form_filepath));
        }


        return $configurator;
    }

    /**
     * Add a ContactForm to $this->contact_forms array
     *
     * @param ContactForm $contactForm
     * @return $this
     */
    public function addContactForm(ContactForm $contactForm)
    {
        $this->contact_forms[$contactForm->contact_form_name] = $contactForm->validate();
        return $this;
    }

    /**
     * Normally this will load config("contactetc.contact_forms"); which should be an array.
     * It checks if it is an array, and if not it will tell the user they probably have to run
     * php artisan vendor:publish
     *
     * @param $load_configs
     * @return array|\Illuminate\Config\Repository|mixed
     * @throws \Exception
     */
    protected static function getArrayOfConfigFilepaths($load_configs=false)
    {
        // default is to use config('contactetc.contact_forms'). But you can pass an array to $load_configs
        $configs = $load_configs && is_array($load_configs) ? $load_configs : config("contactetc.contact_forms");

        self::checkConfigIsValidArray($configs);

        return $configs;
    }

    /**
     * throws an exception if the config file doesn't exist
     * @param $contact_form_filepath
     * @throws \Exception
     */
    protected static function checkConfigFileExists(string $contact_form_filepath)
    {
        if (!file_exists($contact_form_filepath)) {
            throw new \Exception("ContactEtc: The contact form config file (defined in config/contactetc.php) does not exist. Please create it with `php artisan make:contactetcform MainContactForm`, then update that file with your contact form details. Could not find file: $contact_form_filepath");
        }
    }

    /**
     * Check the $configs is a valid array.
     * Throw exception if not!
     *
     * @param $configs
     * @throws \Exception
     */
    protected static function checkConfigIsValidArray($configs)
    {
        if (!is_array($configs) || !count($configs)) {
            throw new \Exception("The could not load config('contactetc.contact_forms'). Does config/contactetc.php file exist? Does it have the correct array items? See https://webdevetc.com/contactetc for docs. You probably have to run the `php artisan vendor:publish --tag=contactetc` command...");
        }
    }

    /**
     * Return an array of all of the contact forms
     * (Array of ContactForm items)
     *
     * @return array
     */
    public function allContactForms()
    {
        return $this->contact_forms;
    }

    /**
     * Returns  a ContactForm object (if it exists in $this->contact_forms
     *
     * @param string $contact_form_name
     * @return ContactForm
     * @throws \Exception
     */
    public function getContactForm(string $contact_form_name)
    {
        if (!isset($this->contact_forms[$contact_form_name])) {
            throw new \Exception("Contact form $contact_form_name does not exist in the configurator");
        }
        return $this->contact_forms[$contact_form_name];
    }
}