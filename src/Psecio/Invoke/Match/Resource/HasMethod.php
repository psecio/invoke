<?php

namespace Psecio\Invoke\Match\Resource;

class HasMethod implements \Psecio\Invoke\MatchInterface
{
	private $config;

	public function __construct(array $config = array(), $negate = false)
	{
		$this->config = $config;
	}

	public function evaluate($data)
	{
		$httpMethod = strtoupper($data->getHttpMethod());
		return ($httpMethod === strtoupper($this->config['method']));
	}
}