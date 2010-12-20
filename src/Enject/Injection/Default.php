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
require_once 'Enject/Injection.php';

/**
 * Default implementation of {@link Enject_Injection}
 */
class Enject_Injection_Default
	implements Enject_Injection
{
	/**
	 * @var String
	 */
	protected $_method;

	/**
	 * Parameters used for the constructor of the injector.
	 * @see registerParameter()
	 * @var Mixed[]
	 */
	protected $_parameters = array();

	/**
	 * Returns the name of the method to inject
	 * @return String
	 * @see setMethod()
	 */
	function getMethod()
	{
		return $this->_method;
	}

	/**
	 * Returns the parametters to use in the injection
	 * @return Mixed[]
	 * @see setParameters()
	 */
	function getParameters()
	{
		return $this->_parameters;
	}

	/**
	 * Sets the method that this injection will call
	 * @param String|Reflection_Method $method
	 * @return Enject_Injection_Default
	 */
	function setMethod($method)
	{
		$this->_method = $method;
		return $this;
	}

	/**
	 * Sets the parameters that will be passed to the method
	 * @param Mixed $parameters
	 * @return Enject_Injection_Default
	 */
	function setParameters($parameters)
	{
		$this->_parameters = $parameters;
		return $this;
	}
}