<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\Shared\ListHelper;

class ListViewCommand extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'list:view';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Prints the directory structure of the views directory';

   /**
    * Execute the console command.
    *
    * @return mixed
    */
   public function handle()
   {
      $list = new ListHelper();
      $directory = base_path('resources/views');
      $items = $list->getListAsString($directory);
      if ($items !== false) {
         return $this->info($items);
      }

      return $this->error("$directory was not found.");
   }
}
