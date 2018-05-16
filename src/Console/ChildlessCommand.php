<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use App\Module;
use App\Office;

class ChildlessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:childless
    						{--type= : Filter to org units of this type.}
    						{--code= : Filter to org units with codes containing this substring.}
    						{--name= : Filter to org units with names containing this substring.}
    						{--bookmark= : Bookmark to use for fetching next data set segment.}
    						{--award= : Add award to the listed items.}
    						{--assessment= : Assessment id that awards the specified award.}
    						{--checkAward : Flag to fix award on course list.}
    						{--enroll= : Enroll user to the listed items.}
    						{--credit= : Credit value for the award.}
    						{--copyFrom= : Org unit ID of the source course offering.}
    						{--copyClassFrom= : Org Unit ID of the class list to copy.}
    						{--A|all : Get all items.}
    						{--S|sync : Sync to this database.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve all org units that have no children.';

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
		 * @var \SmithAndAssociates\LaravelValence\Helper\D2LHelper $d2l
		 */
        $d2l = resolve('D2LHelper');
		$sync = $this->option('sync');
		$award = $this->option('award');
		$credit = $this->option('credit');
		$enroll = $this->option('enroll');
		$copyFrom = $this->option('copyFrom');
		$copyClassFrom = $this->option('copyClassFrom');
		$params = [
			'orgUnitType' => $this->option('type'),
			'orgUnitCode' => $this->option('code'),
			'orgUnitName' => $this->option('name'),
			'bookmark' => $this->option('bookmark'),
		];
		$assessment = $this->option('assessment');
		$checkAward = $this->option('checkAward');
		$result = $d2l->getChildless($params);

		if ($this->option('all')) {
			while ($result['PagingInfo']['HasMoreItems']) {
				$params['bookmark'] = $result['PagingInfo']['Bookmark'];
				$temp = $d2l->getChildless($params);
				$result['PagingInfo'] = $temp['PagingInfo'];
				$result['Items'] = array_merge($result['Items'], $temp['Items']);
			}
		}

		foreach($result['Items'] as $i) {
			$name = $i['Name'];
			$code = $i['Code'];
			$id = $i['Identifier'];

			$this->info($id . ' ' . $name . ' ' . $code);

			if ($sync) {
				$office = $d2l->getAncestors($id, ['ouTypeId' => 105]);
				if (count($office) > 0) {
					$officeId = Office::where('code', $office[0]['Code'])->first();
					$officeId = $officeId ? $officeId->id : null;
					if ($officeId) {
						$module = Module::firstOrNew(['id' => $id]);
						$module->name = $name;
						$module->office_id = $officeId;
						$module->save();
						$this->info($id . ' ' . $name . ' updated.');
					}
				}
			}

			if ($award || $credit) {
				if ($award && $credit) {
					$this->call('smithu:awards', [
						'awardId' => $award,
						'--associate' => $id,
						'--credit' => $credit
					]);
				} else if ($award && $assessment) {
					$this->call('smithu:awards', [
						'awardId' => $award,
						'--orgUnitId' => $id,
						'--assessmentId' => $assessment,
						'--issue' => $checkAward
					]);
				} else {
					$this->info('Required --award and --credit options are required to associate an award.');
				}
			}

			if ($copyClassFrom) {
			    $this->call('smithu:enroll', [
			        '--copyClassFrom' => $copyClassFrom,
                    '--orgUnitId' => [$id]
                ]);
            } else if ($enroll) {
				$this->call('smithu:enroll', [
					'userId' => [$enroll],
					'--orgUnitId' => [$id]
				]);
			}

			if ($copyFrom) {
				$this->call('smithu:copy', [
					'--orgUnitId' => $id,
					'--sourceOrgUnitId' => $copyFrom
				]);
			}
		}

		if ($result['PagingInfo']['HasMoreItems']) {
			$this->info('There are more items! Add \'--bookmark '. $result['PagingInfo']['Bookmark'] .'\'');
		}

		return $result;
    }
}
