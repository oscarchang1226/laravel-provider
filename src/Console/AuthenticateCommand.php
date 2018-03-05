<?php

namespace SmithAndAssociates\LaravelValence\Console;

use Illuminate\Console\Command;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;

class AuthenticateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smithu:authenticate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var D2LHelper $d2l;
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
        $this->info($this->d2l->getUrlToAuthenticate('smithtest.brightspace.com'));
    }
}
