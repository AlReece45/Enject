<?php
/*
 * Enject Library
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Injection/Collection.php';

/**
 * Provide an easy way to build injections (via method calls or via properties)
 * 
 * The properties are called using set[propertyName] methods,  other methods
 * may be called using addInjection
 */
class Enject_Injection_Collection_Default
	implements Enject_Injection_Collection
{
	/**
	 * Injections to make on the object (via setters). These
	 * are called only once per name (new values override
	 * older values. Unlike {@link addInjection()} which
	 * does not override but simply adds.
	 * @see setProperty()
	 * @var Mixed[]
	 */
	protected $_properties = array();

	/**
	 * Injections to make on the object.
	 * @see addInjections()
	 * @var Mixed[][]
	 */
	protected $_injections = array();

	/**
	 * Adds an injection (a method call with parameters)
	 * @param String|Enject_Injection $method
	 * @param Mixed $parameters
	 * @see addProperty() properties override regular injections
	 * @return Enject_Injection_Collection_Default
	 */
	function addInjection($method, $parameters = array())
	{
		// if $method is an instance of Enject_Injection, make it one
		if(!$method instanceOf Enject_Injection)
		{
			require_once 'Enject/Injection/Default.php';
			$injection = new Enject_Injection_Default();
			$method = $injection->setMethod($method)
				->setParameters($parameters);
		}
		$this->_injections[] = $method;
		return $this;
	}

	/**
	 * Gets the methods to call after the object is created
	 * @see addInjection()
	 * @see setProperty()
	 * @uses $_properties
	 * @uses $_injections
	 * @return Enject_Injection[]
	 */
	function getInjections()
	{
		$return = $this->_injections;
		foreach($this->_properties as $property => $value)
		{
			$return[] = self::getSetterInjection($property, $value);
		}
		return $return;
	}

	/**
	 * Sets a property (a value set through setter methods)
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Injection_Collection_Default
	 */
	function registerProperty($property, $value)
	{
		$this->_properties[strtolower($property)] = $value;
		return $this;
	}

	/**
	 * Gets an injection for a setter
	 * @param String $name
	 * @param Mixed $value
	 * @return Enject_Injection_Default
	 */
	static function getSetterInjection($property, $value)
	{
		require_once 'Enject/Injection/Default.php';
		$return = new Enject_Injection_Default();
		$return->setMethod("set$property");
		$return->setParameters(array($value));
		return $return;
	}
}
