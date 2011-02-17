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

/**
 * For efficency, values that use {@link Enject_Scope} should register with the
 * scope and implement this interface. When the scope is cloned or destroyed
 * the methods in this interface are called.
 * @see Enject_Scope
 */
interface Enject_Scope_Listener
{
	/**
	 * When a scope is cloned, this method will be called. It is expected that
	 * the instaneces the scope uses will be cloned (not just recreated).
	 * @return Enject_Scope_Value
	 */
	function cloneScope($scope, $oldScopeId);

	/**
	 * Called when a scope is destroyed, at this point a Value may remove all
	 * instances that belong to this scope.
	 * @return Enject_Scope_Value
	 */
	function removeScope($scope);
}