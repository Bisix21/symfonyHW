<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
	/**
	 * Retrieve the array from the specified file.
	 *
	 * @param string $filePath The path to the file.
	 * @return array|null The retrieved array or null if file doesn't return an array.
	 */
	public static function getArrayFromFile(string $filePath): ?array
	{
		if (file_exists($filePath)) {
			return require $filePath;
		}

		return null;
	}
}
