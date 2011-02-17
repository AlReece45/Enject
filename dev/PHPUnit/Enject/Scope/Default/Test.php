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
class Test_Enject_Scope_Default_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Scope_Default
	 */
	protected function _getInstance()
	{
		return new Enject_Scope_Default();
	}

	/**
	 * Ensure that a test instance may be created
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Scope_Default');
		$builder = new Enject_Scope_Default();
	}

	/**
	 * Ensure that a test instance of {@link Test_Enject_Scope_Value} may be
	 * created.
	 */
	function testListenerInstance()
	{
		$this->assertClassExists('Test_Enject_Scope_Listener_Mock');
		$value = new Test_Enject_Scope_Listener_Mock();
	}

	/**
	 * @depends testInstance
	 */
	function testGetScopeId()
	{
		$scope1 = $this->_getInstance();
		$scope2 = $this->_getInstance();
		$this->assertNotEquals($scope1->getScopeId(), $scope2->getScopeId());
	}
	
	/**
	 * @depends testInstance
	 * @depends testListenerInstance
	 */
	function testRegisterListener()
	{
		$scope = $this->_getInstance();
		$value = new Test_Enject_Scope_Listener_Mock();
		$this->assertSame($scope, $scope->registerListener($value));
	}

	/**
	 * @depends testRegisterListener
	 */
	function testClone()
	{
		$listener = new Test_Enject_Scope_Listener_Mock();
		$scope = $this->_getInstance();
		$scope->registerListener($listener);
		$clone = clone $scope;
		$scopes = $listener->getClonedScopes();
		$this->assertTraversable($scopes);
		$this->assertNotEquals($scope->getScopeId(), $clone->getScopeId());
		$this->assertSame($clone, reset($scopes));
	}

	/**
	 * @depends testRegisterListener
	 * {@link Enject_Scope_Default::getScopeId()} changes after waking up.
	 */
	function testGetListeners()
	{
		$scope = $this->_getInstance();
		$value = new Test_Enject_Scope_Listener_Mock();
		$scope->registerListener($value);
		$return = $scope->getListeners();
		$this->assertTraversable($return);
		$this->assertType('splObjectStorage', $return);
		$this->assertEquals(1, count($return));
		$this->assertTrue($return->contains($value));
	}

	/**
	 * @depends testGetListeners
	 * {@link Enject_Scope_Default::getScopeId()} changes after waking up.
	 */
	function testSleep()
	{
		$scope = $this->_getInstance();
		$value = new Test_Enject_Scope_Listener_Mock();
		$scope->registerListener($value);
		$clone = unserialize(serialize($scope));
		$this->assertEquals(0, count($clone->getListeners()));
	}

	/**
	 * @depends testRegisterListener
	 */
	function testDestroyRemove()
	{
		$scope = $this->_getInstance();
		$value = new Test_Enject_Scope_Listener_Mock();
		$this->assertSame($scope, $scope->registerListener($value));
		$expected = $scope->getScopeId();
		unset($scope);
		$return = $value->getRemovedScopes();
		$this->assertTraversable($return);
		$this->assertEquals($expected, reset($return));
	}
}
