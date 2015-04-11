<?php

namespace Psecio\Invoke;

interface MatchInterface
{
	public function __construct(array $config = array(), $negate = false);
	public function evaluate($data);
}