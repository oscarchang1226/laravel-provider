<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use App\Office;
use Illuminate\Support\Facades\DB;

class OrgStructureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:orgstructure
    						{--type= : Filter to org units with type matching this org unit type ID.}
    						{--code= : Filter to org units with codes containing this substring.}
    						{--name= : Filter to org units with names containing this substring.}
    						{--bookmark= : Bookmark to use for fetching next data set segment.}
    						{--A|all : All results.}
    						{--S|sync : Sync with this database.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve properties for all org units.';

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
		 * @var $d2l \SmithAndAssociates\LaravelValence\Helper\D2LHelper
		 */
        $d2l = resolve('D2LHelper');
        $params = [
        	'orgUnitType' => $this->option('type'),
        	'orgUnitCode' => $this->option('code'),
        	'orgUnitName' => $this->option('name'),
        	'bookmark' => $this->option('bookmark'),
		];
        $result = $d2l->getOrgStructure($params);
		if ($this->option('all')) {
			while ($result['PagingInfo']['HasMoreItems']) {
				$params['bookmark'] = $result['PagingInfo']['Bookmark'];
				$temp = $d2l->getOrgStructure($params);
				$result['PagingInfo'] = $temp['PagingInfo'];
				$result['Items'] = array_merge($result['Items'], $temp['Items']);
			}
		}

		foreach($result['Items'] as $item) {
			$code = $item['Code'];
			$name = $item['Name'];
			$id = $item['Identifier'];
			if ($this->option('sync')) {
				// 105 is the Regional Office type id for SmithU
				if ( $this->option( 'type' ) === '105' ) {
					$s      = strtoupper( str_replace( ' ', '%', $name ) );
					$office = Office::where( DB::raw( 'replace(upper(name), \' \', \'\')' ), 'like', $s )->first();
					if ( $office ) {
						$office->name = $name;
						$office->code = $code;
						$office->save();
						$this->info( $office->id . ' ' . $office->name . ' updated.' );
					}
				}
			}
			$this->info($id . ' ' . $code . ' ' . $name);
		}

		if ($result['PagingInfo']['HasMoreItems']) {
			$this->info('There are more items add \'--bookmark '. $result['PagingInfo']['Bookmark'] .'\' to the previous command.');
		}

		return $result['Items'];
	}
}
