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

	/**
	 * Get the current formatted error message
	 *
	 * @return string Error message contents
	 */
	public function getError()
	{
		$config = $this->getConfig()['data'];
		$message = (isset($this->error)) ? $this->error : 'There was an error on match for '.__CLASS__;
		if (strpos($message, ':data') !== false) {
			$data = '';
			$data = (is_array($config)) ? '['.implode(',', array_values($config)).']' : $config;
			$message = str_replace(':data', $data, $message);
		}
		return $message;
	}

	abstract public function evaluate($data);
}