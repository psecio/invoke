<?php

namespace Psecio\Invoke;

abstract class MatchInstance
{
	/**
	 * Current configuration details
	 * @var array
	 */
	protected $config = array();

	/**
	 * Flag to negate the check
	 * @var boolean
	 */
	protected $negate = false;

	/**
	 * Init the object with the given confgiuration and negation flag
	 *
	 * @param array $config Configuration information
	 * @param boolean $negate Negation flag, default false [optional]
	 */
	public function __construct(array $config = array(), $negate = false)
	{
		$this->setConfig($config);
	}

	/**
	 * Set the current configuration values
	 *
	 * @param array $config Configuration information
	 */
	public function setConfig(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Get the current configuration, or if a key is provided
	 * 	try to locate that one value
	 *
	 * @param string $key Key to locate [optional]
	 * @return mixed Either entire config or key is found
	 */
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

	/**
	 * Evaluate the match
	 *
	 * @param \Psecio\Invoke\Data $data Object instance
	 * @return boolean Result of evaluation
	 */
	abstract public function evaluate($data);
}