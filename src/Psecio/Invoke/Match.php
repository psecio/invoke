<?php

namespace Psecio\Invoke;

class Match
{
	public static function create($type, $config, $negate = false)
	{
		$typeNs = self::formatNamespace($type);
		if (!class_exists($typeNs) === true) {
			throw new \InvalidArgumentException('Invalid match type: '.$type);
		}
		$config = (!is_array($config)) ? array($config) : $config;
		return new $typeNs($config, $negate);
	}

	public static function formatNamespace($type)
	{
		$typeNs = "\\Psecio\\Invoke\\Match";
		foreach (explode('.', $type) as $part) {
			$typeNs	.= "\\".ucwords(strtolower($part));
		}
		return $typeNs;
	}
}