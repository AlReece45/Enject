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
 * @see Enject_Injection
 */
abstract class Test_Enject_InjectionTestCase
	extends Test_Enject_TestCase
{

	/**
	 * @todo as the latest PHPUnit becomes more widespread: depend on
	 *		{@link Test_Enject_Injection_Default_Test}
	 * @return Enject_Injection
	 */
	protected abstract function _getInjection();

	/**
	 * Tests to make sure that {@link _getInjection()} returns an
	 * {@link Enject_Injection}
	 */

	function testInjectionInterface()
	{
		$this->assertInterfaceExists('Enject_Injection');
		$this->assertType('Enject_Injection', $this->_getInjection());
	}

	/**
	 * @param Enject_Injection $injection Value to use as a injection.
	 *  {@link _getInjection()} will be used instead if this is null. (PHPUnit
	 *  will likely run the test with no parameters). If you want to do additional
	 *  testing on return parameters, you can use this parameter.
	 * @depends testInjectionInterface
	 * @see Enject_Injection::getParameters()
	 */
	function testInjectionGetParameters($injection = null)
	{
		if($injection === null)
		{
			$injection = $this->_getInjection();
		}
		$this->assertType('Enject_Injection', $injection);
		$this->assertTraversable($injection->getParameters());
	}

	/**
	 * @param Enject_Injection $injection Value to use as a injection.
	 *  {@link _getInjection()} will be used instead if this is null. (PHPUnit
	 *  will likely run the test with no parameters). If you want to do additional
	 *  testing on return parameters, you can use this parameter.
	 * @depends testInjectionInterface
	 * @see Enject_Injection::getProperties()
	 */
	function testInjectionGetMethod($injection = null)
	{
		if($injection === null)
		{
			$injection = $this->_getInjection();
		}
		$this->assertType('Enject_Injection', $injection);
		$this->assertType('string', $injection->getMethod());
	}
}