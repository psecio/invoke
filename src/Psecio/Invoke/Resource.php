<?php

namespace Psecio\Invoke;

class Resource
{
	protected $uri;

	public function __construct($uri = null)
	{
		$this->setUri(
			($uri !== null) ? $uri : $_SERVER['REQUEST_URI']
		);
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