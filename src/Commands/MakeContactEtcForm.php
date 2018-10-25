<?php

namespace WebDevEtc\ContactEtc\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeContactEtcForm extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:contactetcform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new empty contact form for webdevetc/contactetc';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ContactEtcForm';

    public function handle()
    {
        if (!is_array(config("contactetc"))){
            $this->error("The config file contactetc.php does not exist, or is not returning an array. Have you done the vendor:publish command? Please see the docs on https://webdevetc.com/contactetc");
            return;
        }

        // create the file:
        $return= parent::handle();

        if (is_null($return)) {
            // success!
            // let's output some help/info

            // get the file that was created.
            $name = $this->qualifyClass($this->getNameInput());
            $path = $this->getPath($name);
            $filename = basename($path);

            $this->info("You can find the file in:");
            $this->line("/app/ContactEtcForms/$filename");

            $this->warn("Please edit that file as required.");

            $this->line("--------------------------");
            $this->warn(wordwrap(" ** Please update the 'contact_forms' array in your config/contactetc.php and include the full path of the new file. **"));
            $this->info(("It should be the following: "));

            $this->line("app_path('ContactEtcForms/$filename')");

            $this->line("--------------------------");

            $this->info(wordwrap("(please see https://webdevetc.com/contactetc for the docs that explain how the configs should look)"));

            $this->line("--------------------------");


            $this->info(wordwrap("If you have more than one contact form, please see the docs on my site (you will also have to add some custom routes."));

            $this->line("--------------------------");
            $this->warn("All done! Please scroll up and read the previous messages!");
            $this->line("Visit https://webdevetc.com/contactetc for docs/more help!");
            $this->line("BTW - Need to hire a php dev? contact me https://webdevetc.com/ :)");


        }


        return $return;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/ContactForm.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ContactEtcForms';
    }
}
