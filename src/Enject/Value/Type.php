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
require_once 'Enject/Value.php';

/**
 * This {@link Enject_Value} is responsible for resolving a value to a type.
 * @see Enject_Container::getInstance()
 */
class Enject_Value_Type
	implements Enject_Value
{
	/**
	 * @var Enject_Container
	 */
	protected $_container;

	/**
	 * The name of the type that will be resolved
	 * @var String
	 */
	protected $_type;

	/**
	 * @return Enject_Container
	 */
	function getContainer()
	{
		return $this->_container;
	}

	/**
	 * Gets the name of the type that will be resolved.
	 * @return String
	 * @uses $_type
	 */
	function getType()
	{
		return $this->_type;
	}

	/**
	 * Gets the types (parent classes and interfaces) associated with the
	 * requested ty-e.
	 * @return String[]
	 * @uses Enject_Tools::getTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		$class = new ReflectionClass($this->getType());
		return Enject_Tools::getTypes($class);
	}

	/**
	 * Performs the actual resolation
	 * @param Enject_Container $container
	 * @return Mixed
	 * @uses Enject_Container::resolveType()
	 */
	function resolve()
	{
		return $this->getContainer()->resolveType($this->getType());
	}

	/**
	 * @param Enject_Container $component
	 * @return Enject_Value_Type
	 */
	function setContainer($container)
	{
		$this->_container = $container;
		return $this;
	}

	/**
	 * Sets the type that will be used when resolved.
	 * @param String $type
	 * @return Enject_Value_Type
	 */
	function setType($type)
	{
		$this->_type = $type;
		return $this;
	}
}