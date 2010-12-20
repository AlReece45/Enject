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
require_once 'Enject/InjectionTestCase.php';

/*
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Injection_Default_InjectionTest
	extends Test_Enject_InjectionTestCase
{
	/**
	 * @todo as the latest PHPUnit becomes more widespread: depend on
	 *		{@link Test_Enject_Blueprint_Injection_Test}
	 * @return Enject_Blueprint
	 */
	protected function _getInjection()
	{
		$this->assertClassExists('Enject_Injection_Default');
		$return = new Enject_Injection_Default();
		$return->setMethod('testMethod');
		$return->setParameters(array($this, 'parameter2'));
		return $return;
	}
}