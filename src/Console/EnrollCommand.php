<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class EnrollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:enroll
    						{--orgUnitId=* : Org Unit Id to enroll into.}
    						{userId* : User Id to enroll with.}
    						{--dismiss : Dismiss user from org unit by user id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enrollment for a user.';

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
    	$userIds = $this->argument('userId');
    	$orgUnitIds = $this->option('orgUnitId');
        if (count($userIds) > 0 && count($orgUnitIds) > 0) {
			$data = array(
				'RoleId' => 103
			);
        	foreach ($orgUnitIds as $orgUnit) {
        		$data['OrgUnitId'] = $orgUnit;
				foreach ($userIds as $idx => $userId) {
					$data['UserId'] = $userId;
					if ($this->option('dismiss')) {
						$verb = 'dismiss';
						$failedVerb = 'dismissed from';
						$result = $this->d2l->dismissUser($userId, $orgUnit);
					} else {
						$verb = 'enroll';
						$failedVerb = 'enrolled to';
						$result = $this->d2l->enrollUser($data);
					}
					if (isset($result['error'])) {
						$this->info(
							$idx + 1 . '. Failed to ' . $verb . ' user id ' . $userId . ' to ' . $data['OrgUnitId']
						);
					} else {
						$this->info(
							$idx + 1 . '. User Id ' . $userId . ' ' . $failedVerb . ' ' . $data['OrgUnitId']
						);
					}
				}
			}
		}

        return;
    }
}
