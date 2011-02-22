<?php
/*
 * Enject Library
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Container/Value/Base.php';
require_once 'Enject/Mode/Value.php';

/**
 * This {@link Enject_Value} is responsible for refering to a Component
 * @see Enject_Container::registerComponent()
 */
class Enject_Container_Value_Component
	extends Enject_Container_Value_Base
	implements Enject_Mode_Value
{	
	/**
	 * The name of the component
	 * @var String
	 */
	protected $_name;

	/**
	 * @return String
	 */
	function getMode()
	{
		return $this->_getModeResolver()->getMode();
	}

	/**
	 * Gets the name of the component that will be used when resolving
	 * @return String
	 * @uses $_name
	 */
	function getName()
	{
		return $this->_name;
	}

	/**
	 * Gets the types (className, parent classes, and interfaces of the object
	 * that will; be returned.
	 * @return String[]
	 * @uses Enject_Tools::getTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		return Enject_Tools::getModeTypes($this, $this->getValue());
	}

	/**
	 * Builds the objects without doing any checks on the mode.
	 * @return Mixed
	 */
	function getValue()
	{
		return $this->getContainer()->resolveComponent($this->getName());
	}

	/**
	 * @param String $mode
	 * @return Enject_Container_Type
	 */
	function setMode($mode)
	{
		$this->_getModeResolver()->setMode($mode);
		return $this;
	}

	/**
	 * @param String $component
	 * @return Enject_Container_Value_Component
	 */
	function setName($name)
	{
		$this->_name = $name;
		return $this;
	}

	/**
	 * @param Enject_Container_Base $container
	 * @return Mixed
	 */
	function resolve()
	{
		$modeResolver = $this->_getModeResolver();
		$modeResolver->setValue($this->getValue());
		return $modeResolver->resolve();
	}
}
