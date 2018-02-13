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
    protected $signature = 'smithu:awards
    						{awardId? : Award Id for an award.}
    						{--associate= : Org Unit Id to associate an award to.}
    						{--credit= : Credit value for award. }
    						{--type= : Filter by award type, defaults to all.}
    						{--offset=0 : Number of records to skip, defaults to 0.}
    						{--search= : Filter results to those with matches between search string and org unit’s name, or award’s title or description.}
    						{--limit=100 : Number of records to return between 1 and 200.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve awards available across the organization.';

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

    	if ($this->option('associate')) {
			$data = [
				'AwardId' => $this->argument('awardId'),
				'Credit' => $this->option('credit'),
				'HiddenAward' => false
			];

			$result = $this->d2l->associateAward($this->option('associate'), $data);

			if (isset($result['AssociationId'])) {
				$this->info('Award ' . $result['Award']['AwardId'] . ' added to ' . $result['OrgUnitId']);
			}

		} else {
			$params = [
				'awardType' => $this->option('type'),
				'limit' => $this->option('limit'),
				'offset' => $this->option('offset'),
				'search' => $this->option('search')
			];

			$result = $this->d2l->getAwards($params);
			$result['Objects'] = array_filter($result['Objects'], function($award) {
				return !$award['IsDeleted'];
			});

			$this->info('---------------------------------------------------');

			foreach($result['Objects'] as $award) {
				$this->info($award['AwardId'] . ' ' . $award['Title'] . ' ' . $award['AwardType'] . ' ' . $award['Description']);
				$this->info('---------------------------------------------------');
			}

		}

		return $result;
	}
}
