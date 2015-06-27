<?php

namespace Psecio\Invoke;

class Match
{
	/**
	 * Create a new match instance
	 *
	 * @param string $type Match type to create (Ex: "user.hasGroup")
	 * @param array $config Configuration items
	 * @param boolean $negate Reverse the check
	 * @throws \InvalidArgumentException If the match type provided is invalid
	 * @return \Psecio\Invoke\Match instance
	 */
	public static function create($type, $config, $negate = false)
	{
		$typeNs = self::formatNamespace($type);
		if (!class_exists($typeNs) === true) {
			throw new \InvalidArgumentException('Invalid match type: '.$type);
		}
		$config = (!is_array($config)) ? array($config) : $config;
		return new $typeNs($config, $negate);
	}

	/**
	 * Format the namespace for the Match type given
	 *
	 * @param string $type Match type (ex: "user.hasGroup")
	 * @return string Namespaced version of the class for given group
	 */
	public static function formatNamespace($type)
	{
		$typeNs = "\\Psecio\\Invoke\\Match";
		foreach (explode('.', $type) as $part) {
			$typeNs	.= "\\".ucwords($part);
		}
		return $typeNs;
	}
}