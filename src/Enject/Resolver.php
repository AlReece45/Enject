<?php
/*
 * Enject Library
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */

/**
 * Used to determine how (and when) to resolve other {@link Enject_Value}s
 */
interface Enject_Resolver
{
	/**
	 * @param Mixed $value
	 * @return Enject_Mode
	 */
	function setValue($value);
}