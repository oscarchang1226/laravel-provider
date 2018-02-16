<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class DataExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:dataexport
    						{--C|create= : Create job with data set id.}
    						{--S|startdate= : Start Date filter. [2016-02-24]}
    						{--E|enddate= : End Date filter. [2016-02-24]}
    						{--O|orgunit= : Parent Org Unit Id filter.}
    						{--R|roles= : User roles filter.}
    						{--l|list : Lists all available export jobs that you have previously submitted.}
    						{--D|download= : Download the given export job id or plugin id.}
    						{--B|bds : Retrieves a list of BDS plugins that you have permission to see.}
    						{--name= : The name of the job or plugin id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all available data sets.';

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

    	if ($this->option('download')) {
    		if ($this->option('bds')) {
    			$result = $d2l->downloadDataExportBds($this->option('download'));
			} else {
    			$result = $d2l->downloadDataExport($this->option('download'));
			}

			$this->info($result);

		} elseif ($this->option('create')) {
    		$filters = [];
    		if ($this->option('startdate')) {
    			array_push($filters, [
    				'Name' => 'startDate',
					'Value' => $this->option('startdate')
				]);
			}
			if ($this->option('enddate')) {
				array_push($filters, [
					'Name' => 'endDate',
					'Value' => $this->option('enddate')
				]);
			}
			if ($this->option('orgunit')) {
				array_push($filters, [
					'Name' => 'parentOrgUnitId',
					'Value' => $this->option('orgunit')
				]);
			}
			if ($this->option('roles')) {
				array_push($filters, [
					'Name' => 'roles',
					'Value' => $this->option('roles')
				]);
			}
    		$data = [
    			'DataSetId' => $this->option('create'),
				'Filters' => $filters
			];
    		$result = $d2l->createDataExport($data);

    		if (isset($result['error'])) {
    			$this->info('Something went wrong. ' . $result['error']);
    			$this->info('Path: ' . $result['path']);
			} else {
    			$this->info('ExportJobId: ' . $result['ExportJobId']);
    			$this->info('Name: ' . $result['Name']);
			}

		} else {
    		$extraKey = null;
			if ($this->option('bds')) {
				$result = $d2l->getDataExportBdsList();
				$idKey = 'PluginId';
			} else {
				if ($this->option('list')) {
					$result = $d2l->getDataExportJobs();
					$idKey = 'ExportJobId';
					$extraKey = 'SubmitDate';
				} else {
					$result = $d2l->getDataExportList();
					$idKey = 'DataSetId';
				}
			}

			if ($this->option('name')) {
				$result = array_filter($result, function ($item) use ($result) {
					$temp = preg_match('/'. $this->option('name') .'$/i', $item['Name']);
					return $temp;
				});
			}

			dd($result);

			foreach($result as $dataSet) {
				$this->info(
					$dataSet[$idKey] . "\t" . $dataSet['Name'] . ($extraKey ? "\t" . $dataSet[$extraKey] : '')
				);
			}
		}

		return $result;
    }
}
