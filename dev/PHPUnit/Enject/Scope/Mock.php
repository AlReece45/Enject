<?php
/*
 * Enject Library
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */
require_once 'Enject/Scope.php';

/**
 * Default scope shares all objects by default
 */
class Test_Enject_Scope_Mock
	implements Enject_Scope
{
	/**
	 * @var splObjectStorage
	 */
	protected $_scopeId;

	/**
	 * @var splObjectStorage
	 */
	protected $_listeners;

	/**
	 * When the scope is no longer accessible, inform all the
	 * {@link Enject_Scope_Listener}s to remove this scope from their storage.
	 */
	function  __destruct()
	{
		foreach($this->getListeners() as $listener)
		{
			if($listener instanceOf Enject_Scope_Listener)
			{
				$listener->removeScope($this);
			}
		}
	}

	/**
	 * When a scope is cloned, inform all registered {@link Enject_Scope_Listener}s
	 * to clone their stored objects for this scope.
	 */
	function __clone()
	{
		$oldScopeId = $this->getScopeId();
		$this->_scopeId = null;
		foreach($this->getListeners() as $listener)
		{
			if($listener instanceOf Enject_Scope_Listener)
			{
				$listener->cloneScope($this, $oldScopeId);
			}
		}
	}

	/**
	 * Regenerate the scope identifier (so that it remains unique) on wakeup
	 */
	function __sleep()
	{
		$return = get_object_vars($this);
		unset($return['_listeners']);
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
			$this->_scopeId = uniqid('enject/scope/mock');
		}
		return $this->_scopeId;
	}

	/**
	 * Returns all registered Listeners.
	 *
	 * Only Listeners that use scope register themselves
	 * @return SplObjectStorage
	 */
	function getListeners()
	{
		if(!$this->_listeners instanceOf SplObjectStorage)
		{
			$this->_listeners = new SplObjectStorage();
		}
		return $this->_listeners;
	}

	/**
	 * @return Enject_Scope_Default
	 */
	function registerListener($listener)
	{
		if($listener instanceOf Enject_Scope_Listener)
		{
			$this->getListeners()->attach($listener);
		}
		return $this;
	}

	/**
	 * @see Enject_Scope::getScopeId()
	 * @return String
	 */
	function setScopeId($scopeId)
	{
		$this->_scopeId = $scopeId;;
	}
}