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
class Test_Enject_Container_Base_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Container_Base
	 */
	protected function _getInstance()
	{
		return new Enject_Container_Base();
	}

	/**
	 * Tests to make sure the object exists
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Container_Base');
		$container = new Enject_Container_Base();
		$this->assertType('Enject_Container_Base', $container);
	}

	/**
	 * Tests to make sure than the injector
	 * class exists and initializes correctly
	 */
	function testInjectorInstance()
	{
		$this->assertClassExists('Enject_Container_Injector');
		$injector = new Enject_Container_Injector();
	}

	/**
	 * Tests to make sure that the (mock) target class exists
	 * and initializes correctly.
	 */
	function testTargetInstance()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$target = new Test_Enject_Target_Mock();
	}

	/**
	 * @depends testInstance
	 */
	function testGetComponent()
	{
		$container = $this->_getInstance();
		$component = $container->getComponent('test.enject.target');
		$this->assertType('Enject_Value_Component', $component);
		$this->assertSame($container, $component->getContainer());
		$this->assertSame('test.enject.target', $component->getName());
	}

	/**
	 * @depends testInstance
	 */
	function testGetInjector()
	{
		$container = $this->_getInstance();
		$injector = $container->getInjector('Test_Enject_Target_Mock');
		$this->assertType('Enject_Injector', $injector);
	}

	/**
	 * @depends testInstance
	 */
	function testGetInjectorCached()
	{
		$container = $this->_getInstance();
		$injector = $container->getInjector('Test_Enject_Target_Mock');
		$injector2 = $container->getInjector('Test_Enject_Target_Mock');
		$this->assertSame($injector, $injector2);
	}

	/**
	 * @depends testInstance
	 */
	function testGetType()
	{
		$container = $this->_getInstance();
		$type = $container->getType('Test_Enject_Target_Mock');
		$this->assertSame($container, $type->getContainer());
		$this->assertSame('Test_Enject_Target_Mock', $type->getType());
	}

	/**
	 * @depends testInstance
	 */
	function testRegisterInjector()
	{
		$expected = new Enject_Container_Injector();
		$container = $this->_getInstance();
		$return = $container->registerInjector('Test_Enject_Target_Mock', $expected);
		$this->assertEquals($container, $return);
	}

	/**
	 * @depends testInstance
	 * @expectedException Enject_Exception
	 */
	function testGetScopeException()
	{
		$container = $this->_getInstance();
		$container->getScope('test');
	}

	/**
	 * @depends testInstance
	 * @expectedException Enject_Exception
	 */
	function testResolveBadType()
	{
		$container = $this->_getInstance();
		$type = $container->resolveType('this.is-a-tpye\'that doesn"t exist');
	}

	/**
	 * @depends testInstance
	 * @expectedException Enject_Exception
	 */
	function testResolveComponentException()
	{
		$container = $this->_getInstance();
		$container->resolveComponent('test.component');
	}

	/**
	 * @depends testInstance
	 */
	function testRegisterScope()
	{
		$this->assertClassExists('Enject_Scope_Default');
		$container = $this->_getInstance();
		$scope = new Enject_Scope_Default();
		$this->assertSame($container, $container->registerScope('test', $scope));
	}

	/**
	 * @depends testInstance
	 * @depends testInjectorInstance
	 */
	function testRegisterType()
	{
		$expected = new Enject_Container_Injector();
		$container = $this->_getInstance();
		$return = $container->registerType('Test_Enject_Target_Mock', $expected);
		$this->assertEquals($container, $return);
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testResolveValueType()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
		$target = new Test_Enject_Target_Mock();
		$value->setValue($target);
		$container = $this->_getInstance();
		$container->registerType('Test_Enject_Value_Mock', $value);
		$return = $container->resolveType('Test_Enject_Value_Mock');
		$this->assertSame($target, $return);
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testGetBuilder()
	{
		$container = $this->_getInstance();
		$builder = $container->getBuilder('Test_Enject_Target_Mock');
		$this->assertType('Enject_Container_Value_Builder', $builder);
		$this->assertSame('Test_Enject_Target_Mock', $builder->getClassname());
		$this->assertSame($container, $builder->getContainer());
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testRegisterComponent()
	{
		$container = $this->_getInstance();
		$expected = new Test_Enject_Target_Mock();
		$return = $container->registerComponent('test.component', $expected);
		$this->assertSame($container, $return);
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testInjectEmpty()
	{
		$container = $this->_getInstance();
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$this->assertEquals(0, count($target->getInjections()));
		$this->assertEquals(0, count($target->getProperties()));
	}

	/**
	 * @depends testInstance
	 * @depends testTargetInstance
	 */
	function testResolveUnregisteredType()
	{
		$container = $this->_getInstance();
		$type = $container->resolveType('Test_Enject_Target_Mock');
		$this->assertType('Test_Enject_Target_Mock', $type);
	}

	/**
	 * @depends testTargetInstance
	 * @depends testRegisterType
	 */
	function testResolveRegisteredType()
	{
		$expected = new Test_Enject_Target_Mock();
		$container = $this->_getInstance();
		$container->registerType('Test_Enject_Target_Mock', $expected);
		$type = $container->resolveType('Test_Enject_Target_Mock');
		$this->assertSame($expected, $type);
	}

	/**
	 * @depends testTargetInstance
	 * @depends testRegisterComponent
	 */
	function testResolveRegisteredComponent()
	{
		$expected = new Test_Enject_Target_Mock();
		$container = $this->_getInstance();
		$container->registerComponent('test.enject.target', $expected);
		$component = $container->resolveComponent('test.enject.target');
		$this->assertSame($expected, $component);
	}

	/**
	 * @depends testGetInjector
	 * @depends testTargetInstance
	 */
	function testInjectInjection()
	{
		$container = $this->_getInstance();
		$injector = $container->getInjector('Test_Enject_Target_Mock');
		$injector->registerProperty('test', 'testValue');
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$this->assertTrue($target->isProperty('test'));
		$this->assertEquals('testValue', $target->getProperty('test'));
	}

	/**
	 * Classes should always apply after interfaces
	 * @depends testInjectInjection
	 */
	function testInjectOrderInterface()
	{
		$container = $this->_getInstance();
		$injectorInterface = $container->getInjector('Test_Enject_Target');
		$injectorClass = $container->getInjector('Test_Enject_Target_Mock');

		$injectorInterface->registerProperty('test', 'testValue');
		$injectorClass->registerProperty('test', 'realValue');

		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();
		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals($properties['test'], 'realValue');
		$this->assertEquals(2, $target->countSetProperties());
	}

	/**
	 * Classes should always apply after interfaces
	 * @depends testInjectInjection
	 */
	function testInjectOrderParent()
	{
		$container = $this->_getInstance();
		$injectorParent = $container->getInjector('Test_Enject_Target_Parent');
		$injectorChild = $container->getInjector('Test_Enject_Target');

		$injectorParent->registerProperty('test', 'testValue');
		$injectorChild->registerProperty('test', 'realValue');

		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();
		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals($properties['test'], 'realValue');
		$this->assertEquals(2, $target->countSetProperties());
	}

	/**
	 * Classes should always apply after interfaces
	 * @depends testInjectInjection
	 */
	function testInjectOrderParentInterfaces()
	{
		$container = $this->_getInstance();

		$iParentInterface = $container->getInjector('Test_Enject_Target_Parent');
		$iParentInterface->registerProperty('test', 'testValue123');
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();

		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals(1, $target->countSetProperties());
		$this->assertEquals('testValue123', $properties['test']);

		$iParent = $container->getInjector('Test_Enject_Target_Mock_Parent');
		$iParent->registerProperty('test', 'testValue456');
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();

		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals(2, $target->countSetProperties());
		$this->assertEquals('testValue456', $properties['test']);

		$iChildInterface = $container->getInjector('Test_Enject_Target');
		$iChildInterface->registerProperty('test', 'testValue789');
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();

		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals(3, $target->countSetProperties());
		$this->assertEquals('testValue789', $properties['test']);

		$iChild = $container->getInjector('Test_Enject_Target_Mock');
		$iChild->registerProperty('test', 'testValueABCDEFG');
		$target = new Test_Enject_Target_Mock();
		$container->inject($target);
		$properties = $target->getProperties();

		$this->assertEquals(1, count($target->getInjections()));
		$this->assertEquals(1, count($properties));
		$this->assertEquals(4, $target->countSetProperties());
		$this->assertEquals('testValueABCDEFG', $properties['test']);
	}

	/**
	 * Classes should always apply after interfaces
	 * @depends testInjectInjection
	 */
	function testInjectMultipleInjections()
	{
		$this->assertClassExists('Test_Enject_Injector_Mock');
		$injector1 = new Test_Enject_Injector_Mock();
		$injector2 = new Test_Enject_Injector_Mock();

		// register the injectors on the container
		$container = $this->_getInstance();
		$container->registerInjector('Test_Enject_Target_Mock', $injector1);
		$container->registerInjector('Test_Enject_Target_Mock', $injector2);

		$target = new Test_Enject_Target_Mock();
		// make sure that the newly created object is marked as "not injected"
		$this->assertFalse($injector1->isObjectInjected($target));
		$this->assertFalse($injector2->isObjectInjected($target));

		// innject the object
		$container->inject($target);

		// make sure that the objects are now marked as injected
		$this->assertTrue($injector1->isObjectInjected($target));
		$this->assertTrue($injector2->isObjectInjected($target));
	}

	/**
	 * @depends testRegisterScope
	 */
	function testGetScope()
	{
		$container = $this->_getInstance();
		$scope = new Enject_Scope_Default();
		$container->registerScope('test', $scope);
		$this->assertSame($scope, $container->getScope('test'));
	}
}

