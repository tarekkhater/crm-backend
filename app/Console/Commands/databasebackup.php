<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


use Illuminate\Console\Command;

class databasebackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

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
     * @return int
     */
    public function handle()
    {
        $filename = "backup".Carbon::now()->format('y-m-d'). ".gz";
  
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);  
        $data["email"] = "bitrxsupcrm@gmail.com";
        $data["title"] = "web-terminal.online";
        $data["body"] = "Backup-Database";
 
        $files = [
            storage_path('app/backup/'.$filename),
        ];
  
        Mail::send('testmail', $data, function($message)use($data, $files) {
            $message->to($data["email"])
                    ->subject($data["title"]);
 
            foreach ($files as $file){
                $message->attach($file);
            }            
        });        }
}
