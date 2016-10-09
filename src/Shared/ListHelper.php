<?php

namespace Sven\ArtisanView\Shared;

class ListHelper
{
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
   public function directoryContainsViews($directory)
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

   public function getIndentation($level)
   {
      $indentation = '';

      for ($i = 0; $i < $level; ++$i) {
         $indentation .= '  ';
      }

      return $indentation;
   }
}
