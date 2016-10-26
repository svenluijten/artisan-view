<?php

namespace Sven\ArtisanView\Shared;

class ListHelper
{
	/**
	 * @param string $directory The directory to scan.
	 *
	 * @param int $level The current level of indentation. Used in recursion.
	 *
	 * @return string Tree view as single string.
	 */
   public function getListAsString($directory, $level = 0)
   {
       if (!file_exists($directory) || !$this->directoryContainsViews($directory)) {
           return '';
       }

       $result = '';

	   $items = array_diff(scandir($directory), ['.', '..']);
	   foreach ($items as $item) {
		   if ($this->directoryContainsViews($directory.'/'.$item)) {
			   $result .= PHP_EOL.str_repeat('  ', $level).$item;
			   if (is_dir($directory.'/'.$item)) {
				   $result .= $this->getListAsString($directory.'/'.$item, $level + 1);
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
		   $items = array_diff(scandir($directory), ['.', '..']);
           foreach ($items as $item) {
			   if ($this->directoryContainsViews($directory.'/'.$item)) {
				   return true;
			   }
           }
       } elseif (file_exists($directory)) {
           return fnmatch('*.blade.php', $directory);
       }

       return false;
   }
}
