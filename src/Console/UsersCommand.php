<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;
use App\Taker;

class UsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:users
    						{userIds* : Brightspace User Ids.}
    						{--orgId= : Org-defined identifier to look for.}
    						{--username= : User name to look for.}
    						{--email= : External email address to look for.}
    						{--bookmark= : Bookmark to use for fetching next data set segment.}
    						{--A|all : Retrieve all.}
    						{--S|sync : Sync to this database.}
    						{--activate : Activate account.}
    						{--deactivate : Deactivate account.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve data for one or more users.';

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
        $userIds = $this->argument('userIds');
        if (count($userIds) > 0) {
        	$activate = $this->option('activate');
        	$deactivate = $this->option('deactivate');
        	foreach ($userIds as $idx => $userId) {
        		$user = $d2l->getUserData($userId);
        		if (isset($user['UserId'])) {
					if ($activate) {
						$d2l->updateUserActivation($userId, true);
						$this->info('Activated User ' . $userId . ' : ' . $user['DisplayName']);
					} elseif ($deactivate) {
						$d2l->updateUserActivation($userId, false);
						$this->info('Deactivated User ' . $userId . ' : ' . $user['DisplayName']);
					} else {
						$this->info('User ' . $userId . ' : ' . $user['DisplayName']);
					}
				} else {
        			$this->info('Unable to find user with id ' . $userId);
				}
			}

		} else {
			$params = [
				'orgDefinedId' => $this->option('orgId'),
				'userName' => $this->option('username'),
				'externalEmail' => $this->option('email'),
				'bookmark' => $this->option('bookmark'),
			];

			$result = $d2l->getUsers($params);

			if ($this->option('all')) {
				if (isset($result['PagingInfo'])) {
					while($result['PagingInfo']['HasMoreItems']) {
						$params['bookmark'] = $result['PagingInfo']['Bookmark'];
						$temp = $d2l->getUsers($params);
						$result['PagingInfo'] = $temp['PagingInfo'];
						$result['Items'] = array_merge($result['Items'], $temp['Items']);
					}
				}
			}

			if ($result && !isset($result['error'])) {
				if (isset($result['Items'])) {
					if ($this->option('all')) {
						$this->info('Found ' . count($result['Items']) . ' users.');
					} else {
						foreach ($result['Items'] as $user) {
							$taker = Taker::firstOrNew(['id' => $user['UserId']]);
							$taker->first_name = $user['FirstName'];
							$taker->last_name = $user['LastName'];
							$this->info($taker->id . ' ' . $taker->full_name);
							if ($this->option('sync')) {
								$taker->save();
								$this->info($taker->full_name . ' updated.');
							}
						}
					}
				} elseif (isset($result['UserId'])) {
					$isActive = $result['Activation']['IsActive'];
					$this->info(
						$result['UserId'] . ' ' . $result['DisplayName'] . ' ' . ($isActive ? 'Active' : 'Inactive')
					);
					if ($this->option('sync')) {
						$taker = Taker::firstOrNew(['id' => $result['UserId']]);
						$taker->first_name = $result['FirstName'];
						$taker->last_name = $result['LastName'];
						$taker->save();
						$this->info($taker->full_name . ' updated.');
					}
				}
			} else {
				$this->info('Found 0 users.');
			}

			if (isset($result['PagingInfo']) && $result['PagingInfo']['HasMoreItems']) {
				$this->info('There are more items! Add \'--bookmark '. $result['PagingInfo']['Bookmark'] .'\'');
			}

			return $result;

		}
    }
}
