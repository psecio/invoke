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

		$found = preg_match('#'.$regex.'#', $url, $matches);
		if ($found >= 1) {
			$this->setParams($matches);
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