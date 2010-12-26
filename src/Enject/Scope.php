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
 * Responsible for storing and returning objects in for specified values.
 * Often implemented with a SplObjectStorage
 * @see Enject_Scope_Default
 * @see Enject_Scope_Prototype
 */
interface Enject_Scope
{
	/**
	 * @return String
	 */
	function getScopeId();

	/**
	 * @return Enject_Value
	 */
	function registerValue($value);
}