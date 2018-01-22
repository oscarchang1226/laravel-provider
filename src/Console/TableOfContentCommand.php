<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class TableOfContentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:toc
    						{orgUnitId : Org unit ID.}
    						{--restrict : Include content modules where date restriction would otherwise hide them from view.}
    						{--user : Fetch the table of course content as it would be viewed by this user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve the table of course content for an org unit.';

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
        $id = $this->argument('orgUnitId');
        $params = [];
		/**
		 * @var D2LHelper $d2l
		 */
        $d2l = resolve('D2LHelper');
        $result = $d2l->getCourseTOC($id, $params);
        foreach($result['Modules'] as $module) {
        	$this->info('Module ' . $module['ModuleId'] . ' ' . $module['Title']);
        	foreach($module['Topics'] as $topic) {
        		$this->info(" Topic " . $topic['TopicId'] . ' ' . $topic['Title']);
			}
			$this->info('----------------------------------------');
		}
		return $result;
    }
}
