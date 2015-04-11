<?php

namespace Psecio\Invoke;

interface PermissionInterface
{
	/**
	 * Get the name of the current permission
	 *
	 * @return string "Name" value for permission
	 */
	public function getName();
}