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

require_once 'Enject/Value.php';
/*
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Value_Mock
	implements Enject_Value
{
	/**
	 * @var Mixed
	 */
	protected $_value;
	
	/**
	 * @return Mixed
	 */
	function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}

	/**
	 * @return String[]
	 */
	public function getTypes()
	{
		if(is_object($this->_value))
		{
			$return = Enject_Tools::getTypes($this->_value);
		}
		else
		{
			$return = array();
		}
		return $return;
	}

	/**
	 * @param Enject_Container $container
	 * @return Mixed
	 */
	public function resolve()
	{
		return $this->_value;
	}

	/**
	 * @return Enject_Container
	 */
	function getContainer()
	{
		if(!isset($this->_container))
		{
			return new Enject_Container();
		}
		return $this->_container;
	}
}