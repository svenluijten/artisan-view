<?php

namespace Sven\ArtisanView\Commands;

use Illuminate\Console\Command;
use Sven\ArtisanView\View;

class ListViewCommand extends
	Command
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
		$directory = base_path('resources/views');
		try
		{
			$items = $this->scanDirectory($directory);
			return $this->info($items);
		}
		catch (\Exception $e)
		{
			return $this->error($e->getMessage());
		}
	}

	/**
	 * Scans a directory for views and returns them in a tree structure.
	 *
	 * @param string $directory The directory to be scanned.
	 * @param int    $level The level of indentation to be applied.
	 *
	 * @return string Tree structured list of views.
	 */
	private function scanDirectory($directory, $level = 0)
	{
		if (!$this->directoryContainsViews($directory))
		{
			return '';
		}

		$items = scandir($directory);
		$result = '';

		$indent = function ($level)
		{
			$string = '';
			for ($i = 0; $i < $level; ++$i)
			{
				$string .= '  ';
			}

			return $string;
		};

		foreach ($items as $item)
		{
			if ($item != '.' && $item != '..')
			{
				if ($this->directoryContainsViews($directory . '/' . $item))
				{
					$result .= PHP_EOL . $indent($level) . $item;

					if (is_dir($directory . '/' . $item))
					{
						$result .= $this->scanDirectory($directory . '/' . $item, $level + 1);
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
		if (is_dir($directory))
		{
			$items = scandir($directory);
			foreach ($items as $item)
			{
				if ($item != '.' && $item != '..')
				{
					if ($this->directoryContainsViews($directory . '/' . $item))
					{
						return true;
					}
				}
			}
		}
		else if (file_exists($directory))
		{
			$match = fnmatch('*.blade.php', $directory);
			return $match == 1 ? true:false;
		}

		return false;
	}
}
