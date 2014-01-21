<?php
/**
 * Laravel 4 - Persistant Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace anlutro\LaravelSettings;

use Illuminate\Filesystem\Filesystem;

class JsonSettingStore extends SettingStore
{
	public function __construct(Filesystem $files, $path = null)
	{
		$this->files = $files;
		$this->setPath($path ?: storage_path() . '/settings.json');
	}

	public function setPath($path)
	{
		if (!$this->files->isDirectory($dir = dirname($path))) {
			throw new \InvalidArgumentException("Invalid path - $dir is not a directory.");
		}

		$this->path = $path;
	}

	protected function read()
	{
		if (!$this->files->exists($this->path)) {
			return array();
		}
		
		$contents = $this->files->get($this->path);

		$data = json_decode($contents, true);

		if ($data === null) {
			throw new \RuntimeException("Invalid JSON in {$this->path}");
		}

		return $data;
	}

	protected function write(array $data)
	{
		$contents = json_encode($data);

		$this->files->put($this->path, $contents);
	}
}
