<?php

namespace Psecio\Invoke\Match\Route;

class Regex implements \Psecio\Invoke\MatchInterface
{
	private $config;

	public function __construct(array $config = array(), $negate = false)
	{
		$this->config = $config;
	}

	public function evaluate($data)
	{
		$regex = $this->config['route'];
		$url = ($data instanceof \Psecio\Invoke\Resource)
			? $data->getUri() : $data;

		$found = preg_match('#'.$regex.'#', $url);
		return ($found >= 1);
	}
}