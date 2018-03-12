<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;

class CopyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:copy 
    						{--orgUnitId= : Org unit ID of the target course offering.}
    						{--sourceOrgUnitId= : Org unit ID of the source course offering.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue up a new course copy job request.';

	/**
	 * @var \SmithAndAssociates\LaravelValence\Helper\D2LHelper $d2l
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
     */
    public function handle()
    {
        $orgUnit = $this->option('orgUnitId');
        $sourceOrgUnit = $this->option('sourceOrgUnitId');
        if ($orgUnit && $sourceOrgUnit) {
        	$params = array(
        		'SourceOrgUnitId' => $sourceOrgUnit,
				'Components' => null,
				'CallbackUrl' => null
			);
        	$result = $this->d2l->copyOrgUnitComponent($orgUnit, $params);
        	if (isset($result['JobToken'])) {
        		$this->info($sourceOrgUnit . ' => ' . $orgUnit . ' ( '. $result['JobToken'] .' )');
			} else {
        		dd($result);
        		$this->error('Unable to perform copy from ' . $sourceOrgUnit . ' to ' . $orgUnit);
			}
		} else {
        	$this->error('Org Unit Id and Source Org Unit Id are required.');
		}
    }
}
