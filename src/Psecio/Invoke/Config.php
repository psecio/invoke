<?php

namespace Psecio\Invoke;

class Config
{
	/**
	 * Current configuration settings
	 * @var array
	 */
	private $config = array();

	/**
	 * Init the object and set the path
	 * 	Loading happens by default unless second param is false
	 *
	 * @param string $path Path to configuration file
	 * @param boolean $load Turn autoloading on/off [optional]
	 */
	public function __construct($path, $load = true)
	{
		$this->setPath($path);
		if ($load === true) {
			$this->load();
		}
	}

	/**
	 * Set the path to the configuration file
	 *
	 * @param string $path Configuration file path
	 */
	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new \InvalidArgumentException('Invalid path: '.$path);
		}
		$this->path = $path;
	}

	/**
	 * Get the current path setting
	 *
	 * @return string Path to current configuration file
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Load the configuration data into the object
	 * 	from the defined YAML file
	 *
	 * @return array Set of configuration data
	 */
	public function load()
	{
		$yaml = new \Symfony\Component\Yaml\Parser();
	    $config = $yaml->parse(file_get_contents($this->path));

	    foreach ($config as $route => $setup) {
	        $this->config[$route] = new \Psecio\Invoke\RouteContainer($route, $setup);
	    }
	    return $this->config;
	}

	/**
	 * Get the current configuration data
	 * 	Optionally, if a key is provided and set, onyl that value is returned
	 *
	 * @param string $key Configuration key to locate [optional]
	 * @return array|string All config data or just key if found
	 */
	public function getConfig($key = null)
	{
		return ($key !== null && isset($this->config[$key]))
			? $this->config[$key] : $this->config;
	}
}