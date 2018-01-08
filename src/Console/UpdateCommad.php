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
		 * @var $d2l \SmithAndAssociates\LaravelValence\D2L
		 */
        $d2l = resolve('D2L');
        $entity = $this->argument('entity');
        if ($entity === 'offices') {
			$url = $d2l->generateUrl('/orgstructure/6606/children/?ouTypeId=105', 'lp');
			$response = collect($d2l->callAPI($url));
			$response->each(function($office) {
				$s = strtoupper(str_replace(' ', '%', $office['Name']));
				$model = Office::where(DB::raw('replace(upper(name), \' \', \'\')'), 'like', $s)
							   ->first();
				if ($model) {
					$model->name = $office['Name'];
					$model->code = $office['Code'];
					$model->save();
				}
			});
		}
	}
}
