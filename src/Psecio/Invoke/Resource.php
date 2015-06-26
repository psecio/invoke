<?php

namespace Psecio\Invoke;

class Resource
{
	/**
	 * Curernt URI location
	 * @var string
	 */
	protected $uri;

	/**
	 * Current HTTP method/verb
	 * @var string
	 */
	protected $method;

	/**
	 * Create the resource, set the URL and HTTP method manually if desired
	 * 	URI will default to REQUEST_URI
	 *  HTTP method will default to REQUEST_METHOD
	 *
	 * @param string $uri URI for the resource
	 * @param string $httpMethod HTTP method for the resource
	 */
	public function __construct($uri = null, $httpMethod = null)
	{
		$this->setUri(
			($uri !== null) ? $uri : $_SERVER['REQUEST_URI']
		);
		$this->setHttpMethod(
			($httpMethod !== null) ? $httpMethod : $_SERVER['REQUEST_METHOD']
		);
	}

	/**
	 * Set the curren tHTTP method
	 *
	 * @param string $method HTTP method (ex: GET, POST)
	 */
	public function setHttpMethod($method)
	{
		$this->method = strtoupper($method);
	}

	/**
	 * Get the current HTTP method
	 *
	 * @return string HTTP method currently set
	 */
	public function getHttpMethod()
	{
		return $this->method;
	}

	/**
	 * Set the current URI for the resource
	 *
	 * @param string $uri URI path
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;
	}

	/**
	 * Get the curent URI setting
	 *
	 * @return string Current URI setting
	 */
	public function getUri()
	{
		return $this->uri;
	}
}