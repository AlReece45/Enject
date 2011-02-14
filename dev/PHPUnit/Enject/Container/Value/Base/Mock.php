<?php
/*
 * Enject Library
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Container/Value/Base.php';

/**
 * Dummy class used to test {@link Enject_Value_Base}
 */
class Test_Enject_Container_Value_Base_Mock
	extends Enject_Container_Value_Base
{
	/**
	 * Dummy getTypes() method
	 */
	function getTypes()
	{
		return array();
	}

	/**
	 * Test the internal API
	 * @param Mixed $object
	 * @return Mixed
	 */
	function resolve($object = null)
	{
		if($object === false)
		{
			$return = $object;
		}
		else
		{
			$return = $this->_resolve($object);
		}
		return $return;
	}
}
