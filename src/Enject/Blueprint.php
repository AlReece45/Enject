<?php

interface Enject_Blueprint
{
	/**
	 * Gets the target parameters
	 * @return Mixed[]
	 */
	function getParameters();

	/**
	 * Gets the target properties
	 * @return Mixed[]
	 */
	function getProperties();
}