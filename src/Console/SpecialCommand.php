<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class SpecialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:special
    						{--copyClassMode : Use copy classlist feature}
    						{--sourceOrgUnitInfo=* : Org Unit info to copy class list from. Ex: code,name}
    						{--toOrgUnitInfo= : Org Unit info to copy class list to.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    	if ($this->option('copyClassMode')) {
			$sourceOrgUnitInfo = $this->option('sourceOrgUnitInfo');
			$toOrgUnitInfo = $this->option('toOrgUnitInfo');
			if (count($sourceOrgUnitInfo) > 0 && $toOrgUnitInfo) {
				$toOrgUnits = $this->getChildless($toOrgUnitInfo, [SpecialCommand::class, 'extractOfficeCode']);
				$sourceOrgUnits = array_map(function ($info) {
					return $this->getChildless($info,  [SpecialCommand::class, 'extractOfficeCode']);
				}, $sourceOrgUnitInfo);
				$master = [];
				foreach ($toOrgUnits as $orgUnit) {
					$officeCode = $orgUnit['office_code'];
					if ($officeCode) {
						$sources = array_map(function ($orgUnits) use ($officeCode) {
							$filtered = array_filter($orgUnits, function ($orgUnit) use ($officeCode) {
									return 	$orgUnit['office_code'] === $officeCode;
								}) ?? [];
							return 	array_pop($filtered);
						}, $sourceOrgUnits);
						$master[$officeCode] = [
							'to' => $orgUnit,
							'from' => $sources
						];
					}
				}
				foreach ($master as $value) {
					$params = [
						'--orgUnitId' => [$value['to']['Identifier']]
					];
					foreach ($value['from'] as $source) {
						if ($source) {
							$params['--copyClassFrom'] = $source['Identifier'];
							$this->call('smithu:enroll', $params);
						}
					}
				}
			}
		}
		return;
    }

	/**
	 * Get Childless org unit based on given information
	 *
	 * @param $info
	 * @param callable $mapper
	 *
	 * @return array
	 */
    protected function getChildless ($info, callable $mapper = null)
	{
		list($code, $name) = explode(',', $info);
		$orgUnits = $this->d2l->getChildless([
			'orgUnitCode' => $code,
			'orgUnitName' => $name,
			'orgUnitType' => null,
			'bookmark' => null
		])['Items'] ?? [];
		if ($mapper) {
			return array_map( $mapper, $orgUnits );
		}
		return $orgUnits;
	}

	/**
	 * Extract office code from org unit code
	 *
	 * @param $item
	 *
	 * @return mixed
	 */
	protected function extractOfficeCode ($item)
	{
		list($code, $officeCode, $version) = explode('_', $item['Code']);
		$item['office_code'] = $officeCode ?? null;
		return $item;
	}
}
