<?php

namespace Psecio\Invoke\Match\Route;

class Regex implements \Psecio\Invoke\MatchInterface
{
	private $config;
	private $params = array();

	public function __construct(array $config = array(), $negate = false)
	{
		$this->config = $config;
	}

	public function evaluate($data)
	{
		$regex = $this->config['route'];
		$url = ($data instanceof \Psecio\Invoke\Resource)
			? $data->getUri() : $data;

		$found = preg_match('#'.$regex.'#', $url, $matches);
		if ($found >= 1 && isset($matches[1]) && !empty($matches[1])) {
			$this->setParams($matches);
		}
		return ($found >= 1);
	}

	public function setParams(array $params)
	{
		$this->params = $params;
	}
	public function getParams()
	{
		return $this->params;
	}
}