<?php
/*
 * Enject Library
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Enject/TestCase.php';

class Test_Enject_ToolsTest
	extends Test_Enject_TestCase
{
	/**
	 * Ensures the class Enject_Tools exists
	 */
	function testClass()
	{
		$this->assertClassExists('Enject_Tools');
	}

	/**
	 * Ensures that the container class is available for other tests
	 */
	function testContainerInstance()
	{
		$this->assertClassExists('Enject_Container_Base');
		$container = new Enject_Container_Base();
	}

	/**
	 * Ensures that the target class is available for other tests
	 */
	function testTargetInstance()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$target = new Test_Enject_Target_Mock();
	}

	/**
	 * Ensures the injection class is available for other tests
	 */
	function testInjectionInstance()
	{
		$this->assertClassExists('Enject_Injection_Default');
		$injection = new Enject_Injection_Default();
	}

	/**
	 * Ensures the value class is available for other tests
	 */
	function testValueInstance()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
	}

	/**
	 * @depends testClass
	 * @depends testTargetInstance
	 */
	function testPrepareArguments()
	{
		$expected = array('testA', 'testB', 'testC');
		$parameters = array(
			 'c' => 'testC',
			 'b' => 'testB',
			 'a' => 'testA',
		);
		$target = new Test_Enject_Target_Mock();
		$method = new ReflectionMethod($target, 'testMethod');
		$return = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($expected, $return);
	}

	/**
	 * @depends testClass
	 * @depends testTargetInstance
	 */
	function testPrepareArgumentsOptional()
	{
		$expected = array('testA', 'testB');
		$parameters = array(
			 'b' => 'testB',
			 'a' => 'testA',
		);
		$target = new Test_Enject_Target_Mock();
		$method = new ReflectionMethod($target, 'testMethod2');
		$return = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($expected, $return);
	}

	/**
	 * @depends testClass
	 * @depends testTargetInstance
	 */
	function testPrepareArgumentsSingle()
	{
		$parameters = array('single' => 'testFF');
		$target = new Test_Enject_Target_Mock();
		$expected = array_values($parameters);
		$method = new ReflectionMethod($target, 'isProperty');
		$return = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($expected, $return);
	}

	/**
	 * @depends testClass
	 * @depends testTargetInstance
	 */
	function testPrepareArgumentsPassthrough()
	{
		$parameters = array('testC');
		$target = new Test_Enject_Target_Mock();
		$method = new ReflectionMethod($target, 'testMethod');;
		$results = Enject_Tools::prepareArguments($method, $parameters);
		$this->assertEquals($parameters, $results);
	}

	/**
	 * @depends testClass
	 * @depends testTargetInstance
	 * @expectedException Enject_Exception
	 */
	function testPrepareArgumentsException()
	{
		$parameters = array(
			 'b' => 'testC',
			 'c' => 'testB',
		);
		$target = new Test_Enject_Target_Mock();
		$method = new ReflectionMethod($target, 'testMethod1');
		Enject_Tools::prepareArguments($method, $parameters);
	}

	/**
	 * @depends testClass
	 * @depends testContainerInstance
	 * @depends testInjectionInstance
	 * @depends testTargetInstance
	 */
	function testInject()
	{
		$container = new Enject_Container_Base();
		$injection = new Enject_Injection_Default();
		$target = new Test_Enject_Target_Mock();
		$parameters = array('test1', 'test2');
		$injection->setMethod('testMethod')->setParameters($parameters);
		Enject_Tools::inject($container, $target, array($injection));
		$this->assertEquals($parameters, $target->getParameters('testMethod'));
	}

	/**
	 * @depends testClass
	 * @depends testContainerInstance
	 * @depends testInjectionInstance
	 * @depends testTargetInstance
	 */
	function testInjectMagic()
	{
		$container = new Enject_Container_Base();
		$injection = new Enject_Injection_Default();
		$target = new Test_Enject_Target_Mock();
		$parameters = array('test1', 'test2');
		$injection->setMethod('injectMagic')->setParameters($parameters);
		Enject_Tools::inject($container, $target, array($injection));
		$this->assertEquals($parameters, $target->getParameters('injectMagic'));
	}

	/**
	 * @depends testClass
	 * @depends testContainerInstance
	 * @depends testInjectionInstance
	 * @expectedException ReflectionException
	 */
	function testInjectException()
	{
		$this->_injections = array();
		$container = new Enject_Container_Base();
		$injection = new Enject_Injection_Default();
		$parameters = array('test1', 'test2');
		$injection->setMethod('undefinedMethod')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $this, array($injection));
		$expected = array($parameters);
		$this->assertEquals($expected, $this->_injections);
	}

	/**
	 * @depends testInject
	 * @depends testValueInstance
	 */
	function testInjectValue()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$this->_injections = array();
		$container = new Enject_Container_Base();
		$injection = new Enject_Injection_Default();
		$target = new Test_Enject_Target_Mock();
		$value = new Test_Enject_Value_Mock();
		$value->setValue($container);
		$parameters = array($value);
		$injection->setMethod('inject')->setParameters($parameters);
		$this->_injections = array();
		Enject_Tools::inject($container, $target, array($injection));
		$expected = array($container);
		$this->assertEquals($expected, $target->getParameters('inject'));
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
