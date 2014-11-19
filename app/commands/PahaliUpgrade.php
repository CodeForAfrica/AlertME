<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PahaliUpgrade extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pahali:upgrade';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Upgrade the Pahali Platform.';

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
		//
		$this->info('Running "git pull"...');
		shell_exec('git pull');

		// DB Migrate
		$this->call('migrate');

		$this->call('clear-compiled');
		$this->call('dump-autoload');

		$this->call('pahali:update');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
