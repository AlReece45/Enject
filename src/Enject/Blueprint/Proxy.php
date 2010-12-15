<?php

require_once 'Enject/Blueprint.php';

interface Enject_Blueprint_Proxy
	extends Enject_Blueprint
{
	/**
	 * @return Enject_Blueprint
	 */
	function getBlueprint();
}