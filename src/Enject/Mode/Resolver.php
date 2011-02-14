<?php
/*
 * Enject Library
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Resolver.php';

/**
 * Base object for commonly used functionality in the builders
 */
class Enject_Mode_Resolver
{
	/**
	 * Default mode to use when resolving objects. Doesn't do any resolving of
	 * {@link Enject_Value}s. Most Enject_Value's do not use this as the default.
	 */
	const MODE_DEFAULT = 'default';

	/**
	 * This mode is used to resolve an Enject_Value (once). Most
	 * {@link Enject_Value}s use this mode as the default.
	 */
	const MODE_RESOLVE = 'resolve';

	/**
	 * Mode to use for resolving objects
	 * @var String
	 */
	protected $_mode = self::MODE_DEFAULT;

	/**
	 * Gets the currently set build mode
	 * @see setMode()
	 * @return String
	 */
	function getMode()
	{
		return $this->_mode;
	}

	/**
	 * @return Mixed
	 */
	function getValue()
	{
		return $this->_value;
	}

	/**
	 * @param Mixed $object
	 * @return Mixed
	 */
	function resolve()
	{
		$return = $this->getValue();
		if($return instanceOf Enject_Value
			 && $this->getMode() == self::MODE_RESOLVE)
		{
			$return = $return->resolve();
		}
		return $return;
	}

	/**
	 * Sets the resolving mode:
	 * @see MODE_DEFAULT
	 * @see MODE_RESOLVE
	 * @see MODE_VALUE
	 * @return Enject_Mode_Resolver
	 */
	function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}

	/**
	 * @param Mixed $value
	 * @return Enject_Mode_Resolver
	 */
	function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}
}
