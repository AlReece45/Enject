<?php
/*
 * Enject Library Tests
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */
require_once 'Enject/TestCase.php';

/*
 * @see Enject_Container_Base
 */
class Test_Enject_Container_ComponentUnavailableException_Test
	extends Test_Enject_TestCase
{
	/**
	 * Tests to make sure the object exists
	 */
	function testInstance()
	{
		$className = 'Enject_Container_ComponentUnavailableException';
		$this->assertClassExists($className);
		$container = new Enject_Container_ComponentUnavailableException('test');
		$this->assertType($className, $container);
	}
}
