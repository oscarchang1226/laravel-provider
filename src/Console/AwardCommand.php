<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;
use App\Attempt;

class AwardCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:awards
    						{awardId? : Award Id for an award.}
    						{--orgUnitId= : Org Unit to check who have not received the award.}
    						{--assessmentId= : The assessment id that issue the award.}
    						{--issue : Flag to issue award.}
    						{--associate= : Org Unit Id to associate an award to.}
    						{--removeFrom= : Org Unit Id to remove award from.}
    						{--confirm : Flag to confirm action.}
    						{--credit= : Credit value for award. }
    						{--type= : Filter by award type, defaults to all.}
    						{--offset=0 : Number of records to skip, defaults to 0.}
    						{--search= : Filter results to those with matches between search string and org unit’s name, or award’s title or description.}
    						{--issueTo=* : User Id to issue awards to.}
    						{--criteria= : Criteria that triggered awarding.}
    						{--evidence= : Evidence to issue an award.}
    						{--orgUnitId= : Org Unit Id for issued context.}
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
        $result = null;
        $issueTo = $this->option('issueTo');
        if (count($issueTo) > 0 && $this->argument('awardId')) {
            $orgUnitId = $this->option('orgUnitId');
            $criteria = $this->option('criteria');
            $evidence = $this->option('evidence');
            $params = [
                'AwardId' => $this->argument('awardId'),
                'Criteria' => $criteria,
                'Evidence' => $evidence
            ];
            foreach ($issueTo as $userId) {
                $params['IssuedToUserId'] = $userId;
                $this->info('Issueing ' . $this->argument('awardId') . ' to user id ' . $userId . ' in ' . $orgUnitId . ' ' . $criteria . ' ' . $evidence);
                $this->d2l->issueAnAward($orgUnitId, $params);
            }
            $result = [];
        } else if ($this->option('associate')) {
            $data = [
                'AwardId' => $this->argument('awardId'),
                'Credit' => $this->option('credit'),
                'HiddenAward' => false
            ];

            $result = $this->d2l->associateAward($this->option('associate'), $data);

            if (isset($result['AssociationId'])) {
                $this->info('Award ' . $result['Award']['AwardId'] . ' added to ' . $result['OrgUnitId']);
            }
        } else if ($this->option('removeFrom')) {
            $result = [];
            $awardId = $this->argument('awardId');
            $removeFrom = $this->option('removeFrom');
            $associationsObject = $this->d2l->getOrgUnitAssociations($removeFrom);
            $association = collect($associationsObject['Objects'] ?? [])->first(function ($assoc) use ($awardId) {
                return $assoc['Award']['AwardId'] === (int)$awardId;
            });
            if ($association) {
                if ($this->option('confirm')) {
                    $result = $this->d2l->deleteAssociation($removeFrom, $association['AssociationId']);
                }
                $this->info("Assosiation with id {$association['AssociationId']} deleted from {$removeFrom}.");
            } else {
                $this->error("No award with id {$awardId} found in {$removeFrom}");
            }
		} else if ($this->option('orgUnitId')) {
    		$awardId = $this->argument('awardId');
    		$orgUnitId = $this->option('orgUnitId');
    		$classList = $this->d2l->getOrgClassAwards($orgUnitId);
    		while ($classList['Next']) {
    			$temp = $this->d2l->getOrgClassAwards($orgUnitId, ['offset' => 100]);
    			$classList['Next'] = $temp['Next'];
    			$classList['Objects'] = array_merge($classList['Objects'], $temp['Objects']);
			}
    		foreach ($classList['Objects'] as $learner) {
    			$learnerPreText = $learner['UserId'] . ' ' . $learner['FirstName'] . ' ' . $learner['LastName'];
				if (!$learner['TotalAwardCount']) {
    				if ($this->option('issue')) {
						$passed = Attempt::where([
							['assessment_id', $this->option('assessmentId')],
							['taker_id', $learner['UserId']],
							['points', '>', 0]
						])->first();
						if ($passed) {
							$this->d2l->issueAnAward($orgUnitId, [
								'AwardId' => $awardId,
								'IssuedToUserId' => $learner['UserId'],
								'Criteria' => 'Passing ' . $passed->assessment->name . '.',
								'Evidence' => 'Scored ' . $passed->percentage . ' of ' . $passed->assessment->percentage_to_pass
							]);
							$this->info($learnerPreText . ' awarded.');
						}
					} else {
    					$this->info($learnerPreText);
					}
				}
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
