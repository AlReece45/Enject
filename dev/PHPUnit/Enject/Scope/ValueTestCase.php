<?php
/*
 * Enject Library Tests
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */
require_once 'Enject/TestCase.php';

/*
 * @see Enject_Value
 */
abstract class Test_Enject_Scope_ValueTestCase
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Value
	 */
	protected abstract function _getValue();

	/**
	 * Tests to make sure that {@link _getValue()} returns an
	 * {@link Enject_Value}
	 */

	function testValueInterface()
	{
		$this->assertInterfaceExists('Enject_Scope_Value');
		$this->assertType('Enject_Scope_Value', $this->_getValue());
	}

	/**
	 * @depends testValueInterface
	 * @see Enject_Value_Scope::getTypes()
	 */
	function testCloneScope()
	{
		$value = $this->_getValue();
		
	}

	/**
	 * @depends testValueInterface
	 * @see Enject_Value_Scope::getTypes()
	 */
	function testRemoveScope()
	{
	}
}