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
require_once 'Enject/ValueTestCase.php';

/*
 * @see Enject_Value
 */
class Test_Enject_Container_Value_Type_ValueTest
	extends Test_Enject_ValueTestCase
{
	/**
	 * @todo as the latest PHPUnit becomes more widespread: depend on
	 *		{@link Test_Enject_Blueprint_Injection_Test}
	 * @return Enject_Value_Component
	 */
	protected function _getValue()
	{
		$this->assertClassExists('Test_Enject_Target_Mock');
		$this->assertClassExists('Enject_Container_Value_Type');
		$this->assertClassExists('Enject_Container_Base');
		$value = new Test_Enject_Target_Mock();
		$container = new Enject_Container_Base();
		$container->registerType('Test_Enject_Target_Mock', $value);
		$return = new Enject_Container_Value_Type();
		$return->setType('Test_Enject_Target_Mock');
		$return->setContainer($container);
		return $return;
	}
}
