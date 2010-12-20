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
 * @see Enject_Injection_Collection_Default
 */
class Test_Enject_Injection_Collection_Default_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Injection_Collection
	 */
	protected function _createInstance()
	{
		$return = new Enject_Injection_Collection_Default();
		return $return;
	}

	/**
	 * Makes sure the class exists and initializes correctly
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Injection_Collection_Default');
		$injectionCollection = new Enject_Injection_Collection_Default();
	}

	/**
	 * @depends testInstance
	 */
	function testAddInjection($method, $parameters = array())
	{
		$injectionCollection = $this->_createInstance();

		// parameter to use for testing
		$method = 'testMethod';
		$parameters = array();

		$return = $injectionCollection->addInjection($method, $parameters);
		$this->assertSame($injectionCollection, $return);
	}

	/**
	 * @depends testAddInjection
	 */
	function testGetInjections()
	{
		$injectionCollection = $this->_createInstance();

		// parameters to use for testing
		$method = 'testMethod';
		$parameters = array();

		// check the default value
		$injections = $injectionCollection->getInjections();
		$this->assertEquals(array(), $injections);

		// check when we add one injection
		$injectionCollection->addInjection($method, $parameters);
		$injections = $injectionCollection->getInjections();
		$this->assertTraversable($injections);
		$injection = reset($injections);
		
		$this->assertType('Enject_Injection', $injection);
		$this->assertEquals($method, $injection->getMethod());
		$this->assertEquals($parameters, $injection->getParameters());
	}

	/**
	 * @depends testGetInjections
	 */
	function testGetInjectionsOrdered()
	{
		$injectionCollection = $this->_createInstance();

		// check the default value
		$injections = $injectionCollection->getInjections();
		$this->assertEquals(array(), $injections);

		// injections to add
		$_injections = array(
			 array('testMethod1', array('testParameter1')),
			 array('testMethod2', array('testP1', 'testP2')),
			 array('testMethod3', array($this, $injectionCollection)),
		);

		// expected values
		$expected = array();

		foreach($_injections as $_injection)
		{
			list($method, $parameters) = $expected[] = $_injection;
			$injectionCollection->addInjection($method, $parameters);
			$injections = $injectionCollection->getInjections();
			$this->assertTraversable($injections);
			foreach($expected as $eInjection)
			{
				list(, $injection) = each($injections);
				list($eMethod, $eParameters) = $eInjection;
				$this->assertType('Enject_Injection', $injection);
				$this->assertEquals($eMethod, $injection->getMethod());
				$this->assertEquals($eParameters, $injection->getParameters());
			}
		}
	}

	/**
	 * @depends testInstance
	 */
	function testRegisterProperty()
	{
		$injectionCollection = $this->_createInstance();

		// parameters to use for testing
		$name = 'testName123';
		$value = 'valueTest098';

		// perform the actual test
		$return = $injectionCollection->registerProperty($name, $value);
		$this->assertSame($injectionCollection, $return);

		$exists = false;
		foreach($injectionCollection->getInjections() as $injection)
		{
			if(strcmp($injection->getMethod(), $name))
			{
				$parameters = $injection->getParameters();
				$this->assertEquals(1, count($parameters));
				$this->assertEquals($value, reset($parameters));
				$exists = true;
			}
		}
		$this->assertTrue($exists, 'Injection was not created successfully');
	}
}
