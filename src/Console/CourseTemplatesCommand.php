<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class CourseTemplatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve the list of parent org unit type constraints for course offerings built on this template.';

    /**
     * To call D2L valence api services
     *
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
        //
    }
}
