<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteEsIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-es';

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
        var_dump(shell_exec("curl -XDELETE 'localhost:9200/plans?pretty'"));
    }
}
