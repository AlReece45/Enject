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

/**
 * Mock Interfaces (internal interface only used here)
 */
interface Test_Enject_Target_Parent {}
interface Test_Enject_Target
	extends Test_Enject_Target_Parent {}
/**
 * Parent class of it (internal class only used here)
 */
class Test_Enject_Target_Mock_Parent
	implements Test_Enject_Target_Parent {}

/*
 * A mock target for most of the injections
 */
class Test_Enject_Target_Mock
	extends Test_Enject_Target_Mock_Parent
	implements Test_Enject_Target
{
	/**
	 * Parameters used to construct this class
	 * @var Mixed[]
	 */
	protected $_parameters = array();

	/**
	 * @var Mixed[]
	 */
	protected $_injections = array();

	/**
	 * @var Mixed[]
	 */
	protected $_properties = array();

	/**
	 * @var Integer
	 */
	protected $_setProperties= 0;

	/**
	 * Simply store the paramteers
	 */
	function __construct($a = null, $b = null, $c = null)
	{
		$this->_parameters = func_get_args();
	}

	/**
	 * Store injections/properties as they are called
	 */
	function __call($method, $parameters)
	{
		if(strncmp('set', $method, 3) == 0)
		{;
			$this->_setProperties++;
			$this->_properties[substr($method, 3)] = reset($parameters);
		}
		$this->_injections[$method] = $parameters;
	}

	/**
	 * @return Integer
	 */
	function countSetProperties()
	{
		return $this->_setProperties;
	}

	/**
	 * Gets the injections
	 * @return Mixed[]
	 */
	function getInjections()
	{
		return $this->_injections;
	}
	
	/**
	 * Gets the properties that have been "set"
	 * @return Mixed[]
	 */
	function getProperties()
	{
		return $this->_properties;
	}

	/**
	 * @param String $property
	 * @return String
	 */
	function getProperty($property)
	{
		$return = null;
		if(isset($this->_properties[$property]))
		{
			$return = $this->_properties[$property];
		}
		return $return;
	}

	/**
	 * @param String $function
	 * @return Boolean
	 */
	function isInjected($method)
	{
		return isset($this->_injections[$method]);
	}

	/**
	 * @param String $property
	 * @return Boolean
	 */
	function isProperty($property)
	{
		return isset($this->_properties[$property]);
	}

	/**
	 * @param String $method
	 * @return Mixed[]
	 */
	function getParameters($method)
	{
		$return = array();
		if(isset($this->_injections[$method]))
		{
			$return = $this->_injections[$method];
		}
		return $return;
	}

	/**#@+
	 * Test methods
	 */
	function testMethod($a = null, $b = null, $c = null)
	{
		$this->_injections['testMethod'] = func_get_args();
	}
	function testMethod1($a, $b = null, $c = null)
	{
		$this->_injections['testMethod'] = func_get_args();
	}
	function testMethod2($a, $b, $c = null)
	{
		$this->_injections['testMethod'] = func_get_args();
	}
	/**#@-*/
}
