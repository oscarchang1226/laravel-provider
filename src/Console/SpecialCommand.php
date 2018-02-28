<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class SpecialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:special
    						{orgUnits*}
    						{--userId=}
    						{--roleId=}';

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
        $orgUnits = $this->argument('orgUnits');
        $userId = $this->option('userId');
        $roleId = $this->option('roleId') ? $this->option('roleId') : 103;
        if ($userId) {
        	foreach ($orgUnits as $orgUnit) {
        		if ($roleId === 101) {
        			$data = [
        				'RoleId' => $roleId,
						'OrgUnitId' => $orgUnit,
						'UserId' => $userId
					];
					$result = $this->d2l->enrollUser($data);
				} else {
					$result = $this->d2l->dismissUser($userId, $orgUnit);
				}
				if (isset($result['error'])) {
					$this->info('Failed ' . $userId . ' ' . $orgUnit);
				} else {
					$this->info('Success ' . $userId . ' ' . $orgUnit);
				}
			}
		}
    }
}
