<?php
/*
 * Enject Library
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
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
	 * Test the internal API
	 * @param Mixed $object
	 * @return Mixed
	 */
	function resolve($object)
	{
		return $this->_resolve($object);
	}
}
