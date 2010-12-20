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
 * Provide a consistant way to get a list of {@link Enject_Injection} from
 * an object.
 */
interface Enject_Injection_Collection
{
	/**
	 * Gets various other injections to make
	 * @return Enject_Injection[]
	 */
	function getInjections();
}