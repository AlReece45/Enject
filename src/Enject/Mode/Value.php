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
require_once 'Enject/Mode/Resolver.php';

/**
 * Classes that implement this interface implement standard mode functionality.
 */
interface Enject_Mode_Value
{
	/**
	 * The default mode (the procesing of this mode is value dependent)
	 */
	const MODE_DEFAULT = Enject_Mode_Resolver::MODE_DEFAULT;
	
	/**
	 * The default mode (the procesing of this mode is value dependent)
	 */
	const MODE_RESOLVE = Enject_Mode_Resolver::MODE_RESOLVE;

	/**
	 * @return String
	 */
	function getMode();

	/**
	 * @return Mixed
	 */
	function getValue();
}