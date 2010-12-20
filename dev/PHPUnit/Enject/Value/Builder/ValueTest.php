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
class Test_Enject_Value_Builder_ValueTest
	extends Test_Enject_ValueTestCase
{
	/**
	 * @todo as the latest PHPUnit becomes more widespread: depend on
	 *		{@link Test_Enject_Blueprint_Injection_Test}
	 * @return Enject_Blueprint
	 */
	protected function _getValue()
	{
		$this->assertClassExists('Enject_Value_Builder');
		$this->assertClassExists('Enject_Container');
		$this->assertClassExists('Test_Enject_Target_Mock');
		$return = new Enject_Value_Builder();
		$return->setClassname('Test_Enject_Target_Mock');
		$return->setContainer(new Enject_Container());
		return $return;
	}
}
