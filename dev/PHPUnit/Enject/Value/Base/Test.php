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
 * @see Enject_Value_Base
 */
class Test_Enject_Value_BaseTest
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Value_Builder
	 */
	protected function _getInstance()
	{
		return new Test_Enject_Container_Value_Base_Mock();
	}

	/**
	 * Ensure that a test instances matches expected type
	 */
	function testInstance()
	{
		$this->assertClassExists('Test_Enject_Container_Value_Base_Mock');
		$builder = new Test_Enject_Container_Value_Base_Mock();
	}

	/**
	 * Ensures that the target mock class exists
	 */
	function testTargetInstance()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$value = new Test_Enject_Target_Mock();
	}

	/**
	 * @depends testInstance
	 * @expectedException Enject_Container_Value_ContainerUndefinedException
	 */
	function testGetContainerException()
	{
		$builder = $this->_getInstance();
		$builder->getContainer();
	}

	/**
	 * @depends testInstance
	 */
	function testGetModeDefault()
	{
		$builder = $this->_getInstance();
		$this->assertEquals('default', $builder->getMode());
	}
	
	/**
	 * @depends testInstance
	 */
	function testSetContainer()
	{
		$this->assertClassExists('Enject_Container_Base');
		$builder = $this->_getInstance();
		$container = $builder->setContainer(new Enject_Container_Base());
		$this->assertSame($builder, $container);
	}

	/**
	 * @depends testInstance
	 */
	function testSetMode()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->setMode('value'));
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testResolve()
	{
		$value = new Test_Enject_Target_Mock();
		$builder = $this->_getInstance();
		$this->assertSame($value, $builder->resolve($value));
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testResolveValueDefaultMode()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$target = new Test_Enject_Target_Mock();
		$value = new Test_Enject_Value_Mock();
		$value->setValue($target);
		$builder = $this->_getInstance();
		$this->assertSame($target, $builder->resolve($value));
	}

	/**
	 * @depends testSetContainer
	 */
	function testGetContainer()
	{
		$builder = $this->_getInstance();
		$container = new Enject_Container_Base();
		$builder->setContainer($container);
		$this->assertSame($container, $builder->getContainer());
	}

	/**
	 * @depends testSetMode
	 */
	function testGetSetMode()
	{
		$builder = $this->_getInstance();
		$builder->setMode('builder');
		$this->assertEquals('builder', $builder->getMode());
	}

	/**
	 * @depends testSetMode
	 * @depends testTargetInstance
	 */
	function testResolveValueValueMode()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
		$builder = $this->_getInstance();
		$builder->setMode(Enject_Container_Value_Base::MODE_VALUE);
		$this->assertSame($value, $builder->resolve($value));
	}
}
