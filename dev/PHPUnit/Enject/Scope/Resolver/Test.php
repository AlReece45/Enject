<?php
/*
 * Enject Library Tests
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */
require_once 'Enject/TestCase.php';

/*
 * @see Enject_Scope_Resolver
 */
class Test_Enject_Scope_Resolver_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Scope_Resolver
	 */
	protected function _getInstance()
	{
		return new Enject_Scope_Resolver();
	}

	/**
	 * Ensure that a test instance may be created
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Scope_Resolver');
		$resolver = new Enject_Scope_Resolver();
	}

	/**
	 * Tests to ensure that the container class is available
	 */
	function testContainerInstance()
	{
		$this->assertClassExists('Enject_Container_Base');
		$container = new Enject_Container_Base();
	}

	/**
	 * Tests to make sure the mock scope class is available
	 */
	function testScopeInstance()
	{
		$this->assertClassExists('Test_Enject_Scope_Mock');
		$scope = new Test_Enject_Scope_Mock();
	}

	/**
	 * @depends testInstance
	 */
	function testGetDefaultScope()
	{
		$resolver = $this->_getInstance();
		$this->assertEquals('default', $resolver->getScope());
	}

	/**
	 * @depends testInstance
	 */
	function testHasDefaultValue()
	{
		$resolver = $this->_getInstance();
		$this->assertFalse($resolver->hasValue());
	}

	/**
	 * @depends testInstance
	 */
	function testDefaultResolve()
	{
		$resolver = $this->_getInstance();
		$this->assertEquals(null, $resolver->resolve());
	}

	/**
	 * @depends testInstance
	 */
	function testSetScope()
	{
		$resolver = $this->_getInstance();
		$this->assertSame($resolver, $resolver->setScope('testScope'));
	}

	/**
	 * @depends testInstance
	 */
	function testSetValue()
	{
		$resolver = $this->_getInstance();
		$this->assertSame($resolver, $resolver->setValue($this));
	}

	/**
	 * @depends testInstance
	 * @depends testContainerInstance
	 */
	function testSetContainer()
	{
		$container = new Enject_Container_Base();
		$resolver = $this->_getInstance();
		$this->assertSame($resolver, $resolver->setContainer($container));
	}

	/**
	 * @depends testSetContainer
	 */
	function testGetSetContainer()
	{
		$container = new Enject_Container_Base();
		$resolver = $this->_getInstance();
		$resolver->setContainer($container);
		$this->assertSame($container, $resolver->getContainer());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetScope
	 */
	function testGetSetScopeScope()
	{
		$scope = new Test_Enject_Scope_Mock();
		$resolver = $this->_getInstance();
		$resolver->setScope($scope);
		$this->assertSame($scope, $resolver->getScope());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetContainer
	 * @depends testSetScope
	 */
	function testGetSetScopeString()
	{
		$scope = new Test_Enject_Scope_Mock();
		$scopeName = 'test';
		$container = new Enject_Container_Base();
		$container->registerScope($scopeName, $scope);
		$resolver = $this->_getInstance();
		$resolver->setContainer($container);
		$resolver->setScope($scopeName);
		$this->assertSame($scope, $resolver->getScope());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetScope
	 * @depends testSetValue
	 */
	function testCloneScope()
	{
		$scope = new Test_Enject_Scope_Mock();
		$resolver = $this->_getInstance();
		$resolver->setScope($scope);
		$resolver->setValue($this);
		$scope2 = clone $scope;
		$resolver->setScope($scope2);
		$this->assertNotSame($this, $resolver->resolve());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetScope
	 * @depends testSetValue
	 */
	function testScopeHasValue()
	{
		$resolver = $this->_getInstance();
		$resolver->setScope(new Test_Enject_Scope_Mock());
		$resolver->setValue($this);
		$this->assertTrue($resolver->hasValue());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetScope
	 * @depends testSetValue
	 */
	function testScopeResolve()
	{
		$resolver = $this->_getInstance();
		$resolver->setScope(new Test_Enject_Scope_Mock());
		$resolver->setValue($this);
		$this->assertSame($this, $resolver->resolve());
	}

	/**
	 * @depends testScopeInstance
	 * @depends testSetScope
	 * @depends testSetValue
	 */
	function testSetScopeHasValue()
	{
		$resolver = $this->_getInstance();
		$resolver->setScope(new Test_Enject_Scope_Mock());
		$resolver->setValue($this);
		$resolver->setScope(new Test_Enject_Scope_Mock());
		$this->assertFalse($resolver->hasValue());
	}
}
