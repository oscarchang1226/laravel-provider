<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class AwardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:award
    						{--org=:Organization Unit}
    						{--R=:Organization Unit and Descendants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

	/**
	 * @var D2LHelper $d2l
	 */
    protected $d2l;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->d2l = resolve('D2LHelper');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orgUnit = $this->option('org');
        $recursive = $this->option('R');
        if ($orgUnit) {
			$classlist = collect($this->d2l->getOrgClassAwards($orgUnit)['Objects'])->reduce(function($acc, $item) {
				return $acc + count($item['IssuedAwards']['Objects']);
			}, 0);
			if ($recursive) {

			}
//			dd($classlist);
		}
    }
}
