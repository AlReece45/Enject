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
 * @see Enject_Injection_Collection
 */
abstract class Test_Enject_Injection_CollectionTestCase
	extends Test_Enject_TestCase
{

	/**
	 * @todo as the latest PHPUnit becomes more widespread: depend on
	 *		{@link Test_Enject_Injection_Collection_Default_Test}
	 * @return Enject_Injection_Collection
	 */
	protected abstract function _getInjectionCollection();

	/**
	 * Tests to make sure that {@link _getInjectionCollection()} returns an
	 * {@link Enject_Injection_Collection}
	 */
	function testInjectionCollectionInterface()
	{
		$this->assertInterfaceExists('Enject_Injection_Collection');
		$this->assertType('Enject_Injection_Collection', $this->_getInjectionCollection());
	}

	/**
	 * @param Enject_Injection_Collection $injectionCollection Value to use as an
	 * injection broker. {@link _getInjectionCollection()} will be used instead if
	 * this is null. (PHPUnit will likely run the test with no parameters). If
	 * you want to do additional testing on return parameters, you can use this
	 * parameter.
	 * @depends testInjectionCollectionInterface
	 * @see Enject_Injection_Collection::getInjections()
	 */
	function testInjectionCollectionGetInjections($injectionCollection = null)
	{
		if($injectionCollection === null)
		{
			$injectionCollection = $this->_getInjectionCollection();
		}
		$this->assertType('Enject_Injection_Collection', $injectionCollection);
		$injections = $injectionCollection->getInjections();
		$this->assertTraversable($injections);
		foreach($injections as $injection)
		{
			$this->assertType('Enject_Injection', $injection);
		}
	}
}