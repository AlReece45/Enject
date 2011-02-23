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
require_once 'Enject/Container/Base.php';

/**
 * Creates and manages objects.
 *
 * <p>First, the container manages objects. You can create an object any way you
 * want and register it with the container. A more robust way is getting an
 * object builder and telling it out to create the object.</p>
 *
 * <p>Second, the container manages injectors, injectors apply to classes or
 * interfaces they are useful for defining injections that aren't a part of
 * an specific injection.</p>
 */
class Enject_Container_Default
	extends Enject_Container_Base
{
	/**
	 * Whether or not the default scopes have been registered yet
	 * @var Boolean
	 */
	protected $_defaultScopesRegistered = false;

	/**
	 * Enable the default scopes (Default and prototype)
	 * @var Boolean
	 */
	protected $_enableDefaultScopes = true;

	/**
	 * @return Enject_Container_Default
	 */
	function enableDefaultScopes()
	{
		$this->_enableDefaultScopes = true;
		return $this;
	}

	/**
	 * @return Enject_Container_Default
	 */
	function disableDefaultScopes()
	{
		$this->_enableDefaultScopes = false;
		return $this;
	}

	/**
	 * @param String $scope
	 * @return Enject_Scope
	 */
	function getScope($scope = 'default')
	{
		// if the scope isn't registered, and default scopes are enabled
		// registere the default scopes
		if($this->_enableDefaultScopes && !$this->_defaultScopesRegistered)
		{
			require_once 'Enject/Scope/Default.php';
			$this->registerScope('default', new Enject_Scope_Default());
			$this->registerScope('prototype', new stdClass());
			$this->_defaultScopesRegistered = true;
		}
		return parent::getScope($scope);
	}
}
