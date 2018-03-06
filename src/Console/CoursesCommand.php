<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class CoursesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:courses
                            {--create : Confirm flag to create course template and offering.}
                            {--departmentCode= : The code for the department.}
                            {--officeCode= : The code for the office.}
                            {--templateCode= : Code for the course template.}
                            {--templateName= : Name for the course template.}
                            {--offeringCode= : Code for the course offering.}
                            {--offeringName= : Name for the course offering.}';

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
        if ($this->option('create')) {
            $departmentCode = $this->option('departmentCode');
            $code = null;
            if ($departmentCode === 'MC') {
                $code = 'MC';
            } else if ($departmentCode === 'GS') {
                $code = 'GS';
            } else {
                $officeCode = $this->option('officeCode');
                if ($officeCode) {
                    $code = $departmentCode . '_' . $officeCode;
                }
            }
            if ($code) {
                $department = $this->d2l->getOrgStructure([
                        'orgUnitCode' => $code,
                        'orgUnitType' => 101]
                );
                if (isset($department['Items']) && count($department['Items']) === 1) {
                    $this->attemptToAddCourse($department['Items'][0]);

                } else if (!isset($department['error'])) {
                    $code = $departmentCode . ' ' . $officeCode;
                    $department = $this->d2l->getOrgStructure([
                            'orgUnitCode' => $code,
                            'orgUnitType' => 101]
                    );
                    if (isset($department['Items']) && count($department['Items']) === 1) {
                        $this->attemptToAddCourse($department['Items'][0]);

                    } else {
                        $this->logError($department);
                    }
                } else {
                    $this->logError($department);
                }
            } else {
                $this->error('Department code and office code are required.');
            }
        }
    }

    /**
     * Prepare resources to add course
     *
     * @param $department
     * @return array
     */
    protected function prepCourse ($department)
    {
        $departmentId = $department['Identifier'];
        $courseTemplate = $this->generateCourseTemplate(
            $this->option('templateName'),
            $this->option('templateCode'),
            [$departmentId]
        );
        $courseOffering = $this->generateCourseOffering(
            $this->option('offeringName'),
            $this->option('offeringCode')
        );
        if ($courseTemplate['is_valid'] && $courseOffering['is_valid']) {
            return [
                $courseTemplate['data'],
                $courseOffering['data']
            ];
        } else {
            if (!$courseTemplate['is_valid']) {
                $this->error('Course template requires name and code');
            }
            if (!$courseOffering['is_valid']) {
                $this->error('Course offering required name and code');
            }
        }
        return null;
    }

    /**
     * Add Course Template and Course Offering
     *
     * @param $courseTemplate
     * @param $courseOffering
     * @return mixed
     */
    protected function addCourse ($courseTemplate, $courseOffering) {
        $template = $this->d2l->getOrgStructure([
            'orgUnitCode' => $courseTemplate['Code'],
            'orgUnitType' => 2
        ]);
        if (isset($template['Items']) && count($template['Items']) === 1) {
            $template = $template['Items'][0];
        } else {
            $template = $this->d2l->addCourseTemplate($courseTemplate);
        }
        if (isset($template['Identifier'])) {
            $courseOffering['CourseTemplateId'] = $template['Identifier'];
            $offering = $this->d2l->addCourseOffering($courseOffering);
            if (isset($offering['Identifier'])) {
                $this->info('Course has been added successfully.');
            } else {
                $this->error('Unable to create offering.');
                $this->logError($offering);
            }
        } else {
            $this->error('Unable to create course. Failed to create/retrieve course template.');
            $this->logError($template);
        }
    }

    /**
     * Attempt to add template and offering.
     * Prep, validate then add.
     *
     * @param $department
     */
    protected function attemptToAddCourse ($department) {
        $a = $this->prepCourse($department);
        if ($a) {
            list($template, $offering) = $a;
            $this->addCourse($template, $offering);
        }
    }

    /**
     * Log error if there is an error key
     *
     * @param $a
     */
    protected function logError($a)
    {
        if (isset($a['error'])) {
            $this->error($a['error']);
        }
    }

    /**
     * Generate CreateCourseTemplate format
     *
     * @param $name
     * @param $code
     * @param array $parentOrgUnits
     * @param string $path
     * @return array
     */
    protected function generateCourseTemplate ($name, $code, array $parentOrgUnits = [], $path = '')
    {
        $isValid = true;
        $courseTemplate = [];
        if ($name && $code && count($parentOrgUnits) >= 1) {
            $courseTemplate['Name'] = $name;
            $courseTemplate['Code'] = $code;
            $courseTemplate['Path'] = $path;
            $courseTemplate['ParentOrgUnitIds'] = $parentOrgUnits;
        } else {
            $isValid = false;
        }
        return [
            'is_valid' => $isValid,
            'data' => $courseTemplate
        ];
    }

    /**
     * Generate CreateCourseOffering format
     *
     * @param $name
     * @param $code
     * @param $courseTemplateId
     * @param string $path
     * @return array
     */
    protected function generateCourseOffering ($name, $code, $courseTemplateId = null, $path = '')
    {
        $isValid = true;
        $courseOffering = [];
        if ($name && $code) {
            $courseOffering['Name'] = $name;
            $courseOffering['Code'] = $code;
            $courseOffering['Path'] = $path;
            $courseOffering['ForceLocale'] = false;
            $courseOffering['ShowAddressBook'] = false;
            $courseOffering['SemesterId'] = null;
            $courseOffering['StartDate'] = null;
            $courseOffering['EndDate'] = null;
            $courseOffering['LocaleId'] = null;
            if ($courseTemplateId) {
                $courseOffering['CourseTemplateId'] = $courseTemplateId;
            }
        } else {
            $isValid = false;
        }
        return [
            'is_valid' => $isValid,
            'data' => $courseOffering
        ];
    }
}
