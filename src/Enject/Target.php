<?php

interface Enject_Target
{
	/**
	 * @param Mixed $object
	 * @return Mixed
	 */
	function inject($object);
}