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
 * This {@link Enject_Value} is responsible for resolving a value to a type.
 * @see Enject_Container_Base::getInstance()
 */
class Enject_Container_Value_Type
	extends Enject_Container_Value_Base
	implements Enject_Mode_Value
{
	/**
	 * The name of the type that will be resolved
	 * @var String
	 */
	protected $_mode;

	/**
	 * The name of the type that will be resolved
	 * @var String
	 */
	protected $_type;

	/**
	 * @return String
	 */
	function getMode()
	{
		$return = $this->_mode;
		if(!$return)
		{
			$return = self::MODE_RESOLVE;
			try
			{
				$class = new ReflectionClass($this->getType());
				if($class->implementsInterface('Enject_Value'))
				{
					$return = self::MODE_DEFAULT;
				}
			}
			catch(ReflectionException $exception)
			{
				// unfortunately, this will probably cause
				// an error when trying to resolve the type later
				// for now, use the default mode
			}
		}
		return $return;
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
	 * @uses Enject_Tools::getModeTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		return Enject_Tools::getModeTypes($this, $this->getType());
	}

	/**
	 * @return Mixed[]
	 * @see getType()
	 * @see Enject_Container_Base::resolveType()
	 */
	function getValue()
	{
		return $this->getContainer()->resolveType($this->getType());
	}

	/**
	 * Performs the actual resolation
	 * @param Enject_Container_Base $container
	 * @return Mixed
	 * @uses Enject_Container_Base::resolveType()
	 */
	function resolve()
	{
		// use the mode to resolve if needed
		$modeResolver = $this->_getModeResolver();
		$modeResolver->setMode($this->getMode());
		$modeResolver->setValue($this->getValue());
		return $modeResolver->resolve();
	}

	/**
	 * @param String $mode
	 * @return Enject_Container_Value_Type
	 */
	function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}

	/**
	 * Sets the type that will be used when resolved.
	 * @param String $type
	 * @return Enject_Container_Value_Type
	 */
	function setType($type)
	{
		$this->_type = $type;
		return $this;
	}
}
