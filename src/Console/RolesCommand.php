<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class RolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve a list of all known user roles.';

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
    public function handle()
    {
		/**
		 * @var D2LHelper $d2l
		 */
        $d2l = resolve('D2LHelper');

        $result = $d2l->getRoles();

        foreach ($result as $role) {
        	$this->info(' ' . $role['Identifier'] . ' ' . $role['DisplayName']);
		}

        return $result;
    }
}
