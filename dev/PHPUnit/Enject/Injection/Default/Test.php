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
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Injection_Default_Test
	extends Test_Enject_TestCase
{
	/**
	 * Makes sure the class exists and initializes correctly
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Injection_Default');
		$injection = new Enject_Injection_Default();
	}

	/**
	 * @depends testInstance
	 */
	function testSetMethod()
	{
		$injection = new Enject_Injection_Default();
		$this->assertSame($injection, $injection->setMethod('testMethod'));
	}

	/**
	 * @depends testInstance
	 */
	function testSetParameters()
	{
		$injection = new Enject_Injection_Default();
		$parameters = array($this, 'testParameter');
		$this->assertSame($injection, $injection->setParameters($parameters));
		$this->assertEquals($parameters, $injection->getParameters());
	}

	/**
	 * @depends testSetMethod
	 */
	function testGetMethod()
	{
		$injection = new Enject_Injection_Default();
		$method = 'testMethod';
		$injection->setMethod($method);
		$this->assertEquals($method, $injection->getMethod());
	}

	/**
	 * @depends testSetParameters
	 */
	function testGetParameters()
	{
		$injection = new Enject_Injection_Default();
		$parameters = array($this, 'testParameter');
		$injection->setParameters($parameters);
	}
}