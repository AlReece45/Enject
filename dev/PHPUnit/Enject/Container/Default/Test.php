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
 * @see Enject_Container_Base
 */
class Test_Enject_Container_Default_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Container_Base
	 */
	protected function _getInstance()
	{
		return new Enject_Container_Default();
	}

	/**
	 * Tests to make sure the object exists
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Container_Default');
		$container = new Enject_Container_Default();
		$this->assertType('Enject_Container_Default', $container);
	}

	/**
	 * @depends testInstance
	 * @throws Enject_Exception
	 */
	function testEnableDefaultScopes()
	{
		$container = $this->_getInstance();
		$this->assertSame($container, $container->enableDefaultScopes());
	}

	/**
	 * @depends testInstance
	 * @throws Enject_Exception
	 */
	function testDisableDefaultScopes()
	{
		$container = $this->_getInstance();
		$this->assertSame($container, $container->disableDefaultScopes());
	}

	/**
	 * @depends testInstance
	 */
	function testEnabledDefaultScopeDefault()
	{
		$container = $this->_getInstance();
		$this->assertType('Enject_Scope', $container->getScope('default'));
	}

	/**
	 * @depends testInstance
	 */
	function testEnabledDefaultScopePrototype()
	{
		$container = $this->_getInstance();
		$this->assertType('stdClass', $container->getScope('prototype'));
	}
}
