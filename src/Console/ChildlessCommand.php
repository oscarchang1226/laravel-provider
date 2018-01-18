<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use App\Module;

class ChildlessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:childless
    						{--orgUnitType= : Optional. Filter to org units of this type.}
    						{--orgUnitCode= : Optional. Filter to org units with codes containing this substring.}
    						{--orgUnitName= : Optional. Filter to org units with names containing this substring.}
    						{--bookmark= : Optional. Bookmark to use for fetching next data set segment.}
    						{--S|sync : Sync to this database}';

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
		$params = [
			'orgUnitType' => $this->option('orgUnitType'),
			'orgUnitCode' => $this->option('orgUnitCode'),
			'orgUnitName' => $this->option('orgUnitName'),
			'bookmark' => $this->option('bookmark'),
		];
		$result = $d2l->getChildless($params);
		if ($result['PagingInfo']['HasMoreItems']) {
			$this->info('There are more items! Add \'--bookmark '. $result['PagingInfo']['Bookmark'] .'\'');
		}

		if ($sync) {
			foreach($result['Items'] as $i) {
				$moduleId = $i['Identifier'];
				$office = $d2l->getAncestors($moduleId, ['ouTypeId' => 105]);
				$this->info($i['Identifier'] . ' ' . $i['Name']);
			}
		}
    }
}
