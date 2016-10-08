<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\View;

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
    * Create a new command instance.
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
       $directory = base_path('resources/views');
       $items = $this->getListAsString($directory);
       if ($items !== false) {
           return $this->info($items);
       }

       return $this->error("$directory was not found.");
   }

   /**
    * @param $directory
    *
    * @return string Tree view as single string.
    */
   public function getListAsString($directory, $level = 0)
   {
       if (!file_exists($directory)) {
           return false;
       }

       $result = '';

       if ($this->directoryContainsViews($directory)) {
           $items = array_diff(scandir($directory), ['.', '..']);
           foreach ($items as $item) {
               if ($this->directoryContainsViews($directory.'/'.$item)) {
                   $result .= PHP_EOL.$this->getIndentation($level).$item;
                   if (is_dir($directory.'/'.$item)) {
                       $result .= $this->getListAsString($directory.'/'.$item, $level + 1);
                   }
               }
           }
       }

       return $result;
   }

   /**
    * @param string $directory The directory to check.
    *
    * @return bool true if the directory or any of it's children contain views.
    */
   private function directoryContainsViews($directory)
   {
       if (is_dir($directory)) {
           $items = scandir($directory);
           foreach ($items as $item) {
               if ($item != '.' && $item != '..') {
                   if ($this->directoryContainsViews($directory.'/'.$item)) {
                       return true;
                   }
               }
           }
       } elseif (file_exists($directory)) {
           return fnmatch('*.blade.php', $directory);
       }

       return false;
   }

    private function getIndentation($level)
    {
        $indentation = '';

        for ($i = 0; $i < $level; ++$i) {
            $indentation .= '  ';
        }

        return $indentation;
    }
}
