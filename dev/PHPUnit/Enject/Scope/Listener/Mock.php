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

require_once 'Enject/Scope/Listener.php';
/*
 * @see Enject_Blueprint_Default
 */
class Test_Enject_Scope_Listener_Mock
	implements Enject_Scope_Listener
{

	/**
	 * @var String[]
	 * @see getRemovedScopes()
	 */
	protected $_removedScopes = array();

	/**
	 * @var Enject_Scope[]
	 * @see getCLonedScopes()
	 */
	protected $_clonedScopes = array();

	/**
	 * @return String[]
	 * @see removeScope()
	 */
	function getRemovedScopes()
	{
		return $this->_removedScopes;
	}

	/**
	 * @return Enject_Scope[]
	 * @see cloneScope()
	 */
	function getClonedScopes()
	{
		return $this->_clonedScopes;
	}

	/**
	 * @param Enject_Scope $scope
	 * @return Test_Enject_Scope_Value_Mock
	 */
	function cloneScope($scope, $oldScopeId)
	{
		$this->_clonedScopes[] = $scope;
		return $this;
	}

	/**
	 * @param Enject_Scope $scope
	 * @return Test_Enject_Scope_Value_Mock
	 */
	function removeScope($scope)
	{
		$this->_removedScopes[] = $scope->getScopeId();
		return $this;
	}
}
