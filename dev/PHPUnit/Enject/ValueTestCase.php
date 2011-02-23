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
abstract class Test_Enject_ValueTestCase
	extends Test_Enject_TestCase
{
	/**
	 * @param Enject_Container_Default
	 */
	protected function _getContainer()
	{
		return new Enject_Container_Default();
	}

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
		$this->assertInterfaceExists('Enject_Value');
		$this->assertType('Enject_Value', $this->_getValue());
	}

	/**
	 * @param Enject_Value $value Value to use as a value.
	 *  {@link _getValue()} will be used instead if this is null. (PHPUnit
	 *  will likely run the test with no parameters). If you want to do additional
	 *  testing on return parameters, you can use this parameter.
	 * @depends testValueInterface
	 * @see Enject_Value::getTypes()
	 */
	function testValueGetTypes($value = null)
	{
		if($value === null)
		{
			$value = $this->_getValue();
		}
		if(is_array($value))
		{
			foreach($value as $value)
			{
				$this->testValueGetTypes($value);
			}
		}
		else
		{
			$types = $value->getTypes();
			$this->assertTraversable($types);
			foreach($types as $v)
			{
				$this->assertTrue(class_exists($v) || interface_exists($v), $v);
			}
		}
	}

	/**
	 * @param Enject_Value $value Value to use as a value.
	 *  {@link _getValue()} will be used instead if this is null. (PHPUnit
	 *  will likely run the test with no parameters). If you want to do additional
	 *  testing on return parameters, you can use this parameter.
	 * @depends testValueInterface
	 * @see Enject_Value::getTypes()
	 */
	function testResolve($value = null)
	{
		if($value === null)
		{
			$value = $this->_getValue();
		}
		if(is_array($value))
		{
			foreach($value as $value)
			{
				$this->testValueGetTypes($value);
			}
		}
		else
		{
			$container = $this->_getContainer();
			$value->resolve($container);
		}
	}
}
