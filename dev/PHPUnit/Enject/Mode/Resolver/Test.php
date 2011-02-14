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
 * @see Enject_Mode_Resolver
 */
class Test_Enject_Mode_Resolver_Test
	extends Test_Enject_TestCase
{
	/**
	 * @return Enject_Mode_Resolver
	 */
	protected function _getInstance()
	{
		return new Enject_Mode_Resolver();
	}

	/**
	 * Ensure that a test instance may be created
	 */
	function testInstance()
	{
		$this->assertClassExists('Enject_Mode_Resolver');
		$resolver = new Enject_Mode_Resolver();
	}

	/**
	 * @depends testInstance
	 */
	function testGetDefaultMode()
	{
		$resolver = $this->_getInstance();
		$this->assertEquals('default', $resolver->getMode());
	}

	/**
	 * @depends testInstance
	 */
	function testSetMode()
	{
		$resolver = $this->_getInstance();
		$this->assertSame($resolver, $resolver->setMode('testMode'));
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
	 * @depends testSetMode
	 */
	function testGetSetMode()
	{
		$mode = 'testMode';
		$resolver = $this->_getInstance();
		$resolver->setMode($mode);
		$this->assertEquals($mode, $resolver->getMode());
	}

	/**
	 * @depends testSetValue
	 */
	function testGetSetValue()
	{
		$resolver = $this->_getInstance();
		$resolver->setValue($this);
		$this->assertSame($this, $resolver->getValue());
	}

	/**
	 * @depends testSetValue
	 */
	function testResolve()
	{
		$resolver = $this->_getInstance();
		$resolver->setValue($this);
		$this->assertSame($this, $resolver->resolve($this));
	}

	/**
	 * @depends testSetValue
	 */
	function testResolveValueDefaultMode()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
		$resolver = $this->_getInstance();
		$resolver->setValue($value);
		$resolver->setMode('default');
		$this->assertSame($resolver->resolve(), $value);
	}

	/**
	 * @depends testSetValue
	 */
	function testResolveValueResolveMode()
	{
		$this->assertClassExists('Test_Enject_Value_Mock');
		$value = new Test_Enject_Value_Mock();
		$value->setValue($this);
		$resolver = $this->_getInstance();
		$resolver->setValue($value);
		$resolver->setMode('resolve');
		$this->assertSame($this, $resolver->resolve());
	}
}
