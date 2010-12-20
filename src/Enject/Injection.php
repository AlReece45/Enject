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
 *//**
 * Enject Injection
 */
interface Enject_Injection
{
	/**
	 * Returns the name of the method
	 * @param Mixed $object
	 * @return ReflectionMethod
	 */
	function getMethod();

	/**
	 * @param Enject_Container $container
	 * @return Mixed
	 */
	function getParameters();
}