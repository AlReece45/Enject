Enject
======

Enject is a PHP Injection Library. To use it, make sure that the Enject 
directory appears in your include_path. I've licensed it under the LGPL
2.1+ so you should be able to use it anywhere. If there's any licensing 
concerns or problems, feel free to contact me about them. 

Its a relatively new library. If you're interested in an Injection Framework, I 
recommend you take a look. I've tried to keep it as simple as possible while 
making sure that it is properly tested.

Before reading the overview: I highly recommend reading the code instead. I
try to make my source code well documented and readable. A lot of questions
are probably answered there. Though, I'm always looking to make my code better.

Usage
-----

	// create the container
	$container = new Enject_Container_Base();

	// define a few injections for SomeObject
	$injector = $container->getInjector('SomeObject');
	// you may specify parameters by name
	$parameters = array('someParameter' => 'value', 'fun' => true);
	$injector->addInjection('someMethod', $parameters);
	// it works fine if you don not give them by name.
	$injector->addInjection('anotherMethod', array(1,2,3));
	// properties are simply setXXX injections. 
	// They have the benefit of not just getting added
	$injector->registerProperty('label', 'Some Object Label');

	// inject an object
	$object = new SomeObject();
	// the following will call someParameter, someMethod, registerProperty 
	// addInjections are called in the order they were added.
	// registerProperties are always called last
	$container->inject($object);

	// use a component
	$container->registerComponent('core.someobject', $object);
	$object = $this->resolveComponent('core.someobject');

	// use a component by type
	$object = $this->resolveType('SomeObject');

	// override a component by type
	$newObject = new SomeObject();
	$this->registerType('SomeObject', $newObject);

	// make a builder
	$builder = $container->getBuilder('SomeObject');
	// pass it some parameters to the constructor
	$builder->setParameters(array('someParameter' => 'someValue'));
	// register a parameter
	$builder->registerParameter('anotherParameter', 'value'));
	// injections/properties work the same, although it will get
	// injected after its created.
	$builder->addInjection('findLove');
	$builder->registerProperty('searching', true);

	// register the buildre and get it back (resolved)
	$this->registerComponent('core.someobject.searching', $builder);
	$object = $this->resolveComponent('core.someobject.searching');

Overview
--------

Enject_Container_Base is the main injection object. It has several methods for 
registering injections as well as injecting already existing objects.

Enject_Injection defines an Injection (the method and parameters to call).
If you want to implement your own injections you simply need to implement
this interface.

Enject_Injection_Collection is used internally by a few objects. If you
implement your own Injector (see below), you may find it useful.

Enject_Injector is what does the middleman work between a Container and an 
Injector. The container contains a method (getInjector) for getting an 
instance of it for a given class (it will reuse it when possible).

When you need to construct objects, use the getBuilder() method to get an
instance of Enject_Value_Builder. It allows you to specify constructors
as well as Injections (methods and properties). When you want the constructed
object to be

You may also define your own Injector and register it with the
registerInjector() method. Note that you're expected to manage your own
injectors so there is no public functionality to get all of the injectors for
a target.

Objects are reused via Components and Via types. When you register a component
it registers all of its types as well. Its a first-come first-serve system
when it comes to registering components. New components will not override the
registered types for already registered types. If you want a component to 
explicitly provide a type, registerType() exists for that.

If the above does not provide everything needed (for example, calling a method
create an object). You may implement the Enject_Value interface and define
an alterative way to resolve an object (return the object you want to be used 
from the resolve() method).

Answers
-------
(not an FAQ since these haven't been asked yet)
 - Why another dependency injection library?
	I'm working on a few other projects. I wanted something pretty simple
	and extendable (with interfaces instead of abstract classes). 
	
	Something that worked with PHP 5.2 (5 should work fine).

	Something that didn't reach into other concerns such as class loading
	or implemented a Singleton pattern. 

	Something that's unit tested with more than just code coverage in mind
	(functionality and interfaces). Something that has no trouble with
	 PHP errors (E_STRICT and the like). Has relatively consistant and 
	simple API. 

	There are more reasons, the above are probably the most prevaliant.

 - Where would this be useful?
	Just about everywhere. I don't expect everyone (or anyone else for that 
	matter) To use it. I'm publishing it in the hopes that someone might 
	might later find it useful. I certainly plan to find it as such.

Testing
-------

To run the tests (from the dev directory):

    $ phpunit

Contributing
------------

If there's an interest in this framework. I'll likely set up a
better group process. For now follow the github way or email me :)

1. Fork it.
2. Create a branch (`git checkout -b my_markup`)
3. Commit your changes (`git commit -am "Added Snarkdown"`)
4. Push to the branch (`git push origin my_markup`)
5. Create an [Issue][1] with a link to your branch
6. Enjoy a refreshing Diet Coke and wait

