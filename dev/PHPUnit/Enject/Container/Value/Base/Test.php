<?php
/*
 * Enject Library Tests
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */

require_once 'Enject/TestCase.php';
/*
 * @see Enject_Value_Container
 */
class Test_Enject_Container_Value_Base_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Container_Value_Builder
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
		$value = new Test_Enject_Container_Value_Base_Mock();
	}

	/**
	 * @depends testInstance
	 * @expectedException Enject_Container_Value_ContainerUndefinedException
	 */
	function testGetContainerException()
	{
		$value = $this->_getInstance();
		$value->getContainer();
	}
	
	/**
	 * @depends testInstance
	 */
	function testSetContainer()
	{
		$this->assertClassExists('Enject_Container_Base');
		$value = $this->_getInstance();
		$container = $value->setContainer(new Enject_Container_Base());
		$this->assertSame($value, $container);
	}

	/**
	 * @depends testInstance
	 */
	function testGetModeResolver()
	{
		$value = $this->_getInstance();
		$resolver = $value->getModeResolver();
		$this->assertType('Enject_Mode_Resolver', $resolver);
		$expectedMode = Enject_Mode_Resolver::MODE_RESOLVE;
		$this->assertEquals($resolver->getMode(), $expectedMode);
	}

	/**
	 * @depends testInstance
	 */
	function testGetScopeResolver()
	{
		$value = $this->_getInstance();
		$resolver = $value->getScopeResolver();
		$this->assertType('Enject_Scope_Resolver', $resolver);
	}

	/**
	 * @depends testSetContainer
	 */
	function testGetContainer()
	{
		$value = $this->_getInstance();
		$container = new Enject_Container_Base();
		$value->setContainer($container);
		$this->assertSame($container, $value->getContainer());
	}

	/**
	 * @depends testGetScopeResolver
	 * @depends testSetContainer
	 */
	function testScopeResolverContainer()
	{
		$value = $this->_getInstance();
		$container = new Enject_Container_Base();
		$value->setContainer($container);
		$resolver = $value->getScopeResolver();
		$this->assertSame($container, $resolver->getContainer());
	}

	/**
	 * @depends testSetContainer
	 * @depends testScopeResolverContainer
	 */
	function testSetContainerScopeResolver()
	{
		$value = $this->_getInstance();
		$container = new Enject_Container_Base();
		$value->setContainer($container);
		$resolver = $value->getScopeResolver();
		$value->setContainer(new Enject_Container_Base());
		$this->assertNotSame($container, $resolver->getContainer());
	}
}
