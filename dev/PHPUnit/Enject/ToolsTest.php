<?php
/*
 * Enject Library
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Enject/TestCase.php';

class Test_Enject_ToolsTest
	extends Test_Enject_TestCase
{
	protected $_injections = array();

	function __call($method, $parameters)
	{
		$this->_injections[] = $parameters;
	}

	function method($argumentA, $argumentB, $argumentC, $argumentD = 'yes')
	{}

	function singleMethod($single)
	{}

	function inject()
	{
		$this->_injections[] = func_get_args();
	}

	function testClass()
	{
		$this->assertClassExists('Enject_Tools');
	}

	/**
	 * @depends testClass
	 */
	function testPrepareArguments()
	{
		$parameters = array(
			 'argumentC' => 'testC',
			 'argumentB' => 'testB',
			 'argumentA' => 'testA',
		);
		$method = new ReflectionMethod($this, 'method');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals(3, count($results));
		$this->assertEquals($parameters['argumentA'], $results[0]);
		$this->assertEquals($parameters['argumentB'], $results[1]);
		$this->assertEquals($parameters['argumentC'], $results[2]);
	}

	/**
	 * @depends testClass
	 */
	function testPrepareArgumentsOptional()
	{
		$parameters = array(
			 'argumentC' => 'testC',
			 'argumentB' => 'testB',
			 'argumentA' => 'testA',
			 'argumentD' => 'testD',
		);
		$method = new ReflectionMethod($this, 'method');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals(4, count($results));
		$this->assertEquals($parameters['argumentA'], $results[0]);
		$this->assertEquals($parameters['argumentB'], $results[1]);
		$this->assertEquals($parameters['argumentC'], $results[2]);
		$this->assertEquals($parameters['argumentD'], $results[3]);
	}

	/**
	 * @depends testClass
	 */
	function testPrepareArgumentsSingle()
	{
		$parameters = array('single' => 'testFF');
		$method = new ReflectionMethod($this, 'singleMethod');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals('testFF', $results[0]);
	}

	/**
	 * @depends testClass
	 */
	function testPrepareArgumentsPassthrough()
	{
		$parameters = array('testC');
		$method = new ReflectionMethod($this, 'method');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($parameters, $results);
	}

	/**
	 * @depends testClass
	 * @expectedException Enject_Exception
	 */
	function testPrepareArgumentsException()
	{
		$parameters = array(
			 'argumentC' => 'testC',
			 'argumentB' => 'testB',
		);
		$method = new ReflectionMethod($this, 'method');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($parameters, $results);
	}

	/**
	 * @depends testClass
	 */
	function testInject()
	{
		$this->assertClassExists('Enject_Container_Default');
		$this->assertClassExists('Enject_Injection_Default');
		$this->_injections = array();
		$container = new Enject_Container_Default();
		$injection = new Enject_Injection_Default();;
		$parameters = array('test1', 'test2');
		$injection->setMethod('inject')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $this, array($injection));
		$expected = array($parameters);
		$this->assertEquals($expected, $this->_injections);
	}

	/**
	 * @depends testClass
	 */
	function testInjectMagic()
	{
		$this->assertClassExists('Enject_Container_Default');
		$this->assertClassExists('Enject_Injection_Default');
		$this->_injections = array();
		$container = new Enject_Container_Default();
		$injection = new Enject_Injection_Default();
		$parameters = array('test1', 'test2');
		$injection->setMethod('injectMagic')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $this, array($injection));
		$expected = array($parameters);
		$this->assertEquals($expected, $this->_injections);
	}

	/**
	 * @depends testClass
	 * @expectedException ReflectionException
	 */
	function testInjectException()
	{
		$this->assertClassExists('Enject_Container_Default');
		$this->assertClassExists('Enject_Injection_Default');
		$this->_injections = array();
		$container = new Enject_Container_Default();
		$injection = new Enject_Injection_Default();
		$parameters = array('test1', 'test2');
		$injection->setMethod('injectMagic')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $injection, array($injection));
		$expected = array($parameters);
		$this->assertEquals($expected, $this->_injections);
	}

	/**
	 * @depends testInject
	 */
	function testInjectValue()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$this->_injections = array();
		$container = new Enject_Container_Default();
		$injection = new Enject_Injection_Default();
		$value = new Test_Enject_Value_Mock();
		$value->setValue($container);
		$parameters = array($value);
		$injection->setMethod('inject')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $this, array($injection));
		$expected = array(array($container));
		$this->assertEquals($expected, $this->_injections);
	}


	/**
	 * @depends testInject
	 */
	function testGetTypesClass()
	{
		$types = Enject_Tools::getTypes(get_class($this));
		$expected = array(
			 'Countable' => 'Countable',
			 'Test_Enject_TestCase' => 'Test_Enject_TestCase',
			 'Test_Enject_ToolsTest' => 'Test_Enject_ToolsTest',
		);
		foreach($types as $type)
		{
			if(strncmp('PHPUnit_', $type, 8) != 0)
			{
				$this->assertTrue(isset($expected[$type]), $type);
			}
		}
	}

	/**
	 * @depends testInject
	 */
	function testGetTypesObject()
	{
		$types = Enject_Tools::getTypes($this);
		$expected = array(
			 'Countable' => 'Countable',
			 'Test_Enject_TestCase' => 'Test_Enject_TestCase',
			 'Test_Enject_ToolsTest' => 'Test_Enject_ToolsTest',
		);
		foreach($types as $type)
		{
			if(strncmp('PHPUnit_', $type, 8) != 0)
			{
				$this->assertTrue(isset($expected[$type]), $type);
			}
		}
	}

	/**
	 * @depends testInject
	 */
	function testGetTypesValue()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
		$value->setValue($this);
		$types = Enject_Tools::getTypes($value);
		$expected = array(
			 'Countable' => 'Countable',
			 'Test_Enject_TestCase' => 'Test_Enject_TestCase',
			 'Test_Enject_ToolsTest' => 'Test_Enject_ToolsTest',
		);
		foreach($types as $type)
		{
			if(strncmp('PHPUnit_', $type, 8) != 0)
			{
				$this->assertTrue(isset($expected[$type]), $type);
			}
		}
	}
	
	/**
	 * Returns an array of all the types that apply to an object (classes and
	 * interfaces) in one list.
	 * @param Mixed|String $object Object or classname to get the types of
	 * @return String[]
	 */
	static function getTypes($object)
	{
		$return = array();
		// if the object is a Enject_Value, use getTypes to get its type
		// just ensure that the array returned is an associative array
		if($object instanceOF Enject_Value)
		{
			foreach($object->getTypes() as $type)
			{
				$return[$type] = $type;
			}
		}
		else
		{
			$reflector = $object;
			// if the object isn't already a reflector, make it one
			if(!$reflector instanceOf ReflectionClass)
			{
				if(is_string($reflector))
				{
					$reflector = new ReflectionClass($reflector);
				}
				else
				{
					$reflector = new ReflectionObject($object);
				}
			}

			// get the list of interfaces and add it to the return
			foreach($reflector->getInterfaceNames() as $interface)
			{
				$return[$interface] = $interface;
			}

			// finally, use the reflector to go up the list of parents
			do
			{
				$className = $reflector->getName();
				$return[$className] = $className;
			} while($reflector = $reflector->getParentClass());
		}
		return $return;
	}
}
