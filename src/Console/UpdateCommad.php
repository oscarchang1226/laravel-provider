<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use App\Office;
use Illuminate\Support\Facades\DB;

class UpdateCommad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'd2l:update {entity=offices}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $entity = $this->argument('entity');
        if ($entity === 'offices') {
//			$url = $d2l->generateUrl('/orgstructure/6606/children/?ouTypeId=105', 'lp');
//			$response = collect($d2l->callAPI($url));
//			$response->each(function($office) {
//				$s = strtoupper(str_replace(' ', '%', $office['Name']));
//				$model = Office::where(DB::raw('replace(upper(name), \' \', \'\')'), 'like', $s)
//							   ->first();
//				if ($model) {
//					$model->name = $office['Name'];
//					$model->code = $office['Code'];
//					$model->save();
//				}
//			});
			$ouId = 6606;
			$params = ['ouTypeId' => 105];
			$response = $d2l->getDescendants($ouId, $params);
			while ($response['PagingInfo']['HasMoreItems']) {
				$params['bookmark'] = $response['PagingInfo']['Bookmark'];
				$res = $d2l->getDescendants($ouId, $params);
				$response['PagingInfo'] = $res['PagingInfo'];
				$response['Items'] = array_merge($response['Items'], $res['Items']);
			}
			foreach ($response['Items'] as $office) {
				$s = strtoupper(str_replace(' ', '%', $office['Name']));
				$model = Office::where(DB::raw('replace(upper(name), \' \', \'\')'), 'like', $s)
							   ->first();
				$this->info($model->code . ' ' . $office['Identifier'] . "\n");
			}
		}
	}
}
