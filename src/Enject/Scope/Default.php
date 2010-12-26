<?php
/*
 * Enject Library
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Scope.php';

/**
 * Default scope shares all objects by default
 */
class Enject_Scope_Default
	implements Enject_Scope
{
	/**
	 * @var splObjectStorage
	 */
	protected $_scopeId;

	/**
	 * @var splObjectStorage
	 */
	protected $_values;

	/**
	 * When the scope is no longer accessible, inform all the
	 * {@link Enject_Scope_Value}s to remove this scope from their storage.
	 */
	function  __destruct()
	{
		foreach($this->getValues() as $value)
		{
			if($value instanceOf Enject_Scope_Value)
			{
				$value->removeScope($this);
			}
		}
	}

	/**
	 * When a scope is cloned, inform all registered {@link Enject_Scope_Value}s
	 * to clone their stored objects for this scope.
	 */
	function __clone()
	{
		$oldScopeId = $this->getScopeId();
		$this->_scopeId = null;
		foreach($this->getValues() as $value)
		{
			if($value instanceOf Enject_Scope_Value)
			{
				$value->cloneScope($this, $oldScopeId);
			}
		}
	}

	/**
	 * Regenerate the scope identifier (so that it remains unique) on wakeup
	 */
	function __sleep()
	{
		$return = get_object_vars($this);
		unset($return['_values']);
		return array_keys($return);
	}

	/**
	 * @see Enject_Scope::getScopeId()
	 * @return String
	 */
	function getScopeId()
	{
		if(!$this->_scopeId)
		{
			$this->_scopeId = uniqid('enject/scope/default');
		}
		return $this->_scopeId;
	}

	/**
	 * Returns all registered values.
	 *
	 * Only values that use scope register themselves
	 * @return SplObjectStorage
	 */
	function getValues()
	{
		if(!$this->_values instanceOf SplObjectStorage)
		{
			$this->_values = new SplObjectStorage();
		}
		return $this->_values;
	}

	/**
	 * @return Enject_Scope_Default
	 */
	function registerValue($value)
	{
		if($value instanceOf Enject_Scope_Value)
		{
			$this->getValues()->attach($value);
		}
		return $this;
	}
}