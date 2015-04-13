<?php namespace Greenalert\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PahaliUpdate extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pahali:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Pahali platform.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Updating composer components...');
        shell_exec('composer install');

        $this->info('Updating bower components...');
        shell_exec('bower update');

        $this->info('Updating node components...');
        shell_exec('npm update');

        $this->info('Running "grunt bower_concat"...');
        shell_exec('grunt bower_concat');

        // Clear Cache
        $this->call('cache:clear');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

}
