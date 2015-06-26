<?php

namespace Psecio\Invoke;

class Resource
{
	protected $uri;
	protected $method;

	public function __construct($uri = null, $httpMethod = null)
	{
		$this->setUri(
			($uri !== null) ? $uri : $_SERVER['REQUEST_URI']
		);
		$this->setHttpMethod(
			($httpMethod !== null) ? $httpMethod : $_SERVER['REQUEST_METHOD']
		);
	}

	public function setHttpMethod($method)
	{
		$this->method = strtoupper($method);
	}

	public function getHttpMethod()
	{
		return $this->method;
	}

	public function setUri($uri)
	{
		$this->uri = $uri;
	}
	public function getUri()
	{
		return $this->uri;
	}
}