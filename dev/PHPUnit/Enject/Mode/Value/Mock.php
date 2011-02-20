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
require_once 'Enject/Value/Mock.php';
require_once 'Enject/Mode/Value.php';

/*
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Mode_Value_Mock
	extends Test_Enject_Value_Mock
	implements Enject_Mode_Value
{
	/**
	 * @param String
	 */
	protected $_mode;

	/**
	 * @return String
	 */
	function getMode()
	{
		return $this->_mode;
	}

	/**
	 * @param String $mode
	 * @return Test_Enject_Mode_Value_Mock
	 */
	function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}
}