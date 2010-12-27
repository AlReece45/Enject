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
class Test_Enject_Value_Type_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Value_Type
	 */
	protected function _getInstance()
	{
		return new Enject_Value_Type();
	}

	/**
	 * Ensures the class exists and can be created
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Value_Type');
		$builder = new Enject_Value_Type();
	}

	/**
	 * @depends testInstance
	 */
	function testSetContainer()
	{
		$this->assertClassExists('Enject_Container');
		$builder = $this->_getInstance();
		$return = $builder->setContainer(new Enject_Container());
		$this->assertSame($builder, $return);
	}

	/**
	 * @depends testInstance
	 */
	function testSetType()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->setType('testType'));
	}

	/**
	 * @depends testSetType
	 */
	function testGetType()
	{
		$builder = $this->_getInstance();
		$builder->setType('Test_Enject_Target_Mock');
		$this->assertEquals('Test_Enject_Target_Mock', $builder->getType());
	}

	/**
	 * @depends testSetType
	 * @depends testSetContainer
	 */
	function testGetTypes()
	{
		$container = new Enject_Container();
		$container->registerType('Test_Enject_Value_Type_Test', $this);
		$builder = $this->_getInstance();
		$builder->setContainer($container);
		$builder->setType('Test_Enject_Value_Type_Test');
		$types = $builder->getTypes();
		foreach($types as $k => $type)
		{
			if(strncmp('Test_Enject', $type, 11) != 0
				 && strncmp('Enject', $type, 6) != 0)
			{
				unset($types[$k]);
			}
		}
		$expected = array(
			 'Test_Enject_TestCase' => 'Test_Enject_TestCase',
			 'Test_Enject_Value_Type_Test' => 'Test_Enject_Value_Type_Test',
		);
		$this->assertEquals($expected, $types);
	}

	/**
	 * @depends testSetType
	 * @depends testSetContainer
	 */
	function testResolve()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$container = new Enject_Container();
		$expected = new Test_Enject_Target_Mock();
		$container->registerComponent('Test_Enject_Target_Mock', $expected);
		$value = $this->_getInstance();
		$value->setContainer($container);
		$value->setType('Test_Enject_Target_Mock');
		$this->assertSame($expected, $value->resolve());
	}
}
