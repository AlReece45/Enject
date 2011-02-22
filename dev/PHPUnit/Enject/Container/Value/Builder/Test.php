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
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Container_Value_Builder_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Container_Value_Builder 
	 */
	protected function _getInstance()
	{
		return new Enject_Container_Value_Builder();
	}

	/**
	 * Ensure that a test instance may be created
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Container_Value_Builder');
		$builder = new Enject_Container_Value_Builder();
	}

	/**
	 * Ensures that the mock target class exists and can be created
	 */
	function testTargetInstance()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$target = new Test_Enject_Target_Mock();
	}

	/**
	 * Ensures that the mock value builder class exists and can be created
	 */
	function testValueInstance()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$target = new Test_Enject_Value_Mock();
	}

	/**
	 * @depends testInstance
	 */
	function testAddInjection()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->addInjection('setValue', $this));
	}

	/**
	 * @depends testInstance
	 */
	function testGetInjectionCollection()
	{
		$builder = $this->_getInstance();
		$return = $builder->getInjectionCollection();
		$this->assertType('Enject_Injection_Collection_Default', $return);
	}

	/**
	 * @depends testInstance
	 */
	function testGetInjectionsEmpty()
	{
		$builder = $this->_getInstance();
		$return = $builder->getInjections();
		$this->assertTraversable($return);
		$this->assertEquals(0, count($return));
	}

	/**
	 * @depends testInstance
	 */
	function testGetParametersEmpty()
	{
		$builder = $this->_getInstance();
		$return = $builder->getParameters();
		$this->assertTraversable($return);
		$this->assertEquals(0, count($return));
	}

	/**
	 * @depends testInstance
	 */
	function testGetScopeDefault()
	{
		$builder = $this->_getInstance();
		$return = $builder->getScope();
		$this->assertEquals('default', $return);
	}

	/**
	 * @depends testInstance
	 */
	function testRegisterParameter()
	{
		$builder = $this->_getInstance();
		$return = $builder->registerParameter('test', 'value');
		$this->assertSame($builder, $return);
	}

	/**
	 * @depends testInstance
	 */
	function testRegisterProperty()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->registerProperty('name', 'value'));
	}

	/**
	 * @depends testInstance
	 */
	function testSetClassname()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->setClassName('Enject_Value_Mock'));
	}

	/**
	 * @depends testInstance
	 */
	function testSetContainer()
	{
		$this->assertClassExists('Enject_Container_Default');
		$builder = $this->_getInstance();
		$container = $builder->setContainer(new Enject_Container_Default());
		$this->assertSame($builder, $container);
	}

	/**
	 * @depends testInstance
	 */
	function testSetMode()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->setMode('default'));
	}
	
	/**
	 * @depends testInstance
	 */
	function testSetParameters()
	{
		$builder = $this->_getInstance();
		$return = $builder->setParameters(array('test' => 'value'));
		$this->assertSame($builder, $return);
	}

	/**
	 * @depends testInstance
	 */
	function testSetScope()
	{
		$builder = $this->_getInstance();
		$this->assertSame($builder, $builder->setScope('something'));
	}

	/**
	 * @depends testAddInjection
	 */
	function testGetInjection()
	{
		$builder = $this->_getInstance();

		$method = 'setValue';
		$parameters = array($this);

		$builder->addInjection($method, $parameters);
		$return = $builder->getInjections();

		$this->assertTraversable($return);
		$injection = reset($return);
		$this->assertEquals($method, $injection->getMethod());
		$this->assertEquals($parameters, $injection->getParameters());
	}
	
	/**
	 * @depends testRegisterParameter
	 */
	function testGetRegisteredParameter()
	{
		$builder = $this->_getInstance();
		$return = $builder->registerParameter('test', 'value');
		$return = $builder->getParameters();
		$this->assertTraversable($return);
		$this->assertEquals(1, count($return));
		$this->assertEquals('value', $return['test']);
	}

	/**
	 * @depends testRegisterProperty
	 */
	function testGetPropertyInjections()
	{
		$builder = $this->_getInstance();
		$builder->registerProperty('name', 'value');
		$injections = $builder->getInjections();
		$this->assertTraversable($injections);
		$injection = reset($injections);
		$this->assertEquals('setname', strtolower($injection->getMethod()));
		$this->assertEquals(array('value'), $injection->getParameters());
	}

	/**
	 * @depends testSetClassname
	 */
	function testGetClassname()
	{
		$builder = $this->_getInstance();
		$builder->setClassName('Enject_Value_Mock');
		$this->assertEquals('Enject_Value_Mock', $builder->getClassname());
	}

	/**
	 * @depends testSetParameters
	 */
	function testGetSetParameters()
	{
		$builder = $this->_getInstance();
		$parameters = array('test' => 'value');
		$builder->setParameters($parameters);
		$this->assertEquals($parameters, $builder->getParameters());
	}

	/**
	 * @depends testSetClassname
	 * @depends testSetContainer
	 * @depends testTargetInstance
	 */
	function testGetTypes()
	{
		$builder = $this->_getInstance();
		$builder->setClassname('Test_Enject_Target_Mock');
		$builder->setContainer(new Enject_Container_Base());
		$return = $builder->getTypes();
		$expected = array(
			'Test_Enject_Target' => 'Test_Enject_Target',
			'Test_Enject_Target_Mock' => 'Test_Enject_Target_Mock',
			'Test_Enject_Target_Mock_Parent' => 'Test_Enject_Target_Mock_Parent',
			'Test_Enject_Target_Parent' => 'Test_Enject_Target_Parent',
		);
		$this->assertEquals($expected, $return);
	}

	/**
	 * @depends testSetClassname
	 * @depends testSetContainer
	 * @depends testTargetInstance
	 */
	function testResolve()
	{
		$builder = $this->_getInstance();
		$builder->setContainer(new Enject_Container_Base());
		$builder->setClassname('Test_Enject_Target_Mock');
		$this->assertType('Test_Enject_Target', $builder->resolve());
	}
	
	/**
	 * @depends testSetClassname
	 * @depends testSetContainer
	 * @depends testTargetInstance
	 * @depends testValueInstance
	 */
	function testResolveValue()
	{
		$builder = $this->_getInstance();
		$target = new Test_Enject_Target_Mock();
		$builder->setContainer(new Enject_Container_Base());
		$builder->setClassname('Test_Enject_Value_Mock');
		$builder->registerProperty('value', $target);
		$this->assertSame($target, $builder->resolve());
	}

	/**
	 * @depends testResolve
	 * @depends testValueInstance
	 * @depends testSetMode
	 */
	function testResolveDefaultMode()
	{
		$target = new Test_Enject_Target_Mock();
		$builder = $this->_getInstance();
		$builder->setContainer(new Enject_Container_Base());
		$builder->setClassname('Test_Enject_Value_Mock');
		$builder->registerProperty('value', $target);
		$this->assertSame($target, $builder->resolve());
	}

	/**
	 * @depends testResolve
	 * @depends testValueInstance
	 * @depends testSetMode
	 */
	function testResolveValueMode()
	{
		$builder = $this->_getInstance();
		$builder->setContainer(new Enject_Container_Base());
		$builder->setClassname('Test_Enject_Value_Mock');
		$builder->setMode('value');
		$this->assertType('Test_Enject_Value_Mock', $builder->resolve());
	}

	/**
	 * @depends testGetTypes
	 * @depends testResolve
	 * @depends testValueInstance
	 */
	function testGetTypesValue()
	{
		$target = new Test_Enject_Target_Mock();
		$builder = $this->_getInstance();
		$builder->setClassname('Test_Enject_Value_Mock');
		$builder->setContainer(new Enject_Container_Base());
		$builder->registerProperty('value', $target);
		$return = $builder->getTypes();
		$expected = array(
			'Test_Enject_Target' => 'Test_Enject_Target',
			'Test_Enject_Target_Mock' => 'Test_Enject_Target_Mock',
			'Test_Enject_Target_Mock_Parent' => 'Test_Enject_Target_Mock_Parent',
			'Test_Enject_Target_Parent' => 'Test_Enject_Target_Parent',
		);
		$this->assertEquals($expected, $return);
	}
}
