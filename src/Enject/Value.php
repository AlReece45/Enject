<?php

interface Enject_Value
{
	/**
	 * @return String[]
	 */
	function getTypes();
	function resolve(Enject_Container $container);
}