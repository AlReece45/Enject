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

/**
 * An Enject_Value is an indirect object. Whenever Enject_Container_Default encounteres
 * a Enject_Value, it uses the methods to get the type and resolve the value.
 */
interface Enject_Value
{
	/**
	 * Gets the types that applies to this resolver
	 * @return String[]
	 */
	function getTypes();

	/**
	 * Resolves the object.
	 * @param Enject_Container_Default $container
	 * @return Mixed
	 */
	function resolve();

	/**
	 * @return Enject_Container_Default
	 */
	function getContainer();
}