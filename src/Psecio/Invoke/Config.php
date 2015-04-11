<?php

namespace Psecio\Invoke;

class Config
{
	private $config = array();

	public function __construct($path, $load = true)
	{
		$this->setPath($path);
		if ($load === true) {
			$this->load();
		}
	}
	public function setPath($path)
	{
		if (!is_file($path)) {
			throw new \InvalidArgumentException('Invalid path: '.$path);
		}
		$this->path = $path;
	}
	public function load()
	{
		$yaml = new \Symfony\Component\Yaml\Parser();
	    $config = $yaml->parse(file_get_contents($this->path));

	    foreach ($config as $route => $setup) {
	        $this->config[$route] = new \Psecio\Invoke\RouteContainer($route, $setup);
	    }
	    return $this->config;
	}
	public function getConfig($key = null)
	{
		return ($key !== null && isset($this->config[$key]))
			? $this->config[$key] : $this->config;
	}
}