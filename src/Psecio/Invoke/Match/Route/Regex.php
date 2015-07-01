<?php

namespace Psecio\Invoke\Match\Route;

class Regex extends \Psecio\Invoke\MatchInstance
{
	/**
	 * Evaluate the provided resource for a match on the provided URI
	 *
	 * @param string|\Psecio\Invoke\Resource $data URI to match against
	 * @return boolean Pass/fail status
	 */
	public function evaluate($data)
	{
		$regex = $this->getConfig('route');
		$url = ($data instanceof \Psecio\Invoke\Resource)
			? $data->getUri() : $data;

		// Find any placeholders and replace them
		$split = explode('/', $regex);
		$placeholders = [];
		foreach ($split as $index => $item) {
			if (strpos($item, ':') === 0) {
				$placeholders[] = str_replace(':', '', $item);
			}
		}

		// replace the placeholders for regex location
		foreach ($placeholders as $item) {
			$regex = str_replace(':'.$item, '(.+?)', $regex);
		}
		$found = preg_match('#^'.$regex.'$#', $url, $matches);

		if ($found >= 1) {
			// first one is the URL itself, shift off
			array_shift($matches);
			$params = [];

			// Now match up the placeholders
			foreach ($matches as $index => $match) {
				if (isset($placeholders[$index])) {
					$params[$placeholders[$index]] = $match;
				}
			}

			$this->setParams($params);
		}

		return ($found >= 1);
	}

	/**
	 * Set the current URI paramaters
	 *
	 * @param array $params Paramster set
	 */
	public function setParams(array $params)
	{
		$this->params = $params;
	}

	/**
	 * Get the current parameter set
	 *
	 * @return array Parameter set
	 */
	public function getParams()
	{
		return $this->params;
	}
}