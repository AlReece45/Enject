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
require_once 'Enject/Value.php';

/**
 * Used for values that utilize containers
 */
interface Enject_Container_Value
	extends Enject_Value
{
	/**
	 * @return Enject_Container_Base
	 * @throws Enject_Value_ContainerUndefinedException
	 */
	function getContainer();
}
