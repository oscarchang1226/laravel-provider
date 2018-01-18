<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;

class OuTypesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:outypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all the known and visible org unit types.';

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
     * @return void
     */
    public function handle()
    {
		/**
		 * @var \SmithAndAssociates\LaravelValence\Helper\D2LHelper $d2l
		 */
        $d2l = resolve('D2LHelper');
        $result = $d2l->getOuTypes();
        foreach($result as $item) {
        	$this->info($item['Id'] . ' ' . $item['Name']);
		}
    }
}
