<?php

namespace Psecio\Invoke;

abstract class MatchInstance
{
	protected $config = array();
	protected $negate = false;

	public function __construct(array $config = array(), $negate = false)
	{
		$this->setConfig($config);
	}

	public function setConfig(array $config)
	{
		$this->config = $config;
	}
	public function getConfig($key = null)
	{
		if ($key !== null) {
			return (isset($this->config[$key])) ? $this->config[$key] : null;
		} else {
			return $this->config;
		}
	}

	abstract public function evaluate($data);
}