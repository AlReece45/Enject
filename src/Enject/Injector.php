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
 * A injector injects other objects (commonly using a blueprint)
 */
interface Enject_Injector
{
	/**
	 * Requests the injector to inject the injector object
	 * @param Enject_Container_Default $container
	 * @param Mixed $object
	 * @return Mixed
	 */
	function inject($container, $object);
}