<?php
namespace ObjectMutator;

/**
 * An API of the default Reflection API, which caches previously created
 * reflections and can provide valuable information.
 *
 * @author Berry Langerak <berry@berryllium.nl>
 */
class Reflector {
	/**
	 * Contains an array of instantiated \ReflectionClass objects.
	 *
	 * @var \ReflectionClass[]
	 */
	protected $reflections = array( );

	/**
	 * Contains an array of instantiated \ReflectionMethod objects.
	 *
	 * @var \ReflectionMethods[]
	 */
	protected $methods = array( );

	/**
	 * Tries to create a \ReflectionClass for object $object. If it was previously
	 * made, the previous instance will be returned.
	 *
	 * @param Object $object
	 * @return \ReflectionClass
	 */
	public function reflect( $object ) {
		$classname = $this->classname( $object );
		if( !array_key_exists( $classname, $this->reflections ) ) {
			$this->reflections[$classname] = new \ReflectionClass( $classname );
		}
		return $this->reflections[$classname];
	}

	/**
	 * Returns whether or not the method $method exists on the object $object.
	 *
	 * @param object $object
	 * @param string $method
	 * @return boolean
	 */
	public function hasMethod( $object, $method ) {
		return array_key_exists( $method, $this->methods( $this->reflect( $object ) ) );
	}

	/**
	 * Returns the a \ReflectionMethod for method $method of of object $object.
	 *
	 * @param object $object
	 * @param string $method
	 * @return \ReflectionMethod
	 */
	public function method( $object, $method ) {
		if( $this->hasMethod( $object, $method ) ) {
			$method = $this->reflect( $object )->getMethod( $method );
			$method->setAccessible( true );
			return $method;
		}
	}

	/**
	 * Finds and returns an array of all methods for the object of class $class.
	 *
	 * @param \ReflectionClass $class
	 * @return array
	 */
	protected function methods( \ReflectionClass $class ) {
		if( !isset( $this->methods[$class->name] ) ) {
			foreach( $class->getMethods( ) as $method ) {
				$this->methods[$class->name][strtolower( $method->name )] = $method;
			}
		}
		return $this->methods[$class->name];
	}

	/**
	 * Returns the classname of object $object.
	 *
	 * @param object $object
	 * @return string
	 */
	protected function classname( $object ) {
		if( !is_object( $object ) ) {
			throw new \Exception( 
				sprintf( 'ObjectMutator needs an instance of an object, but "%s" (%s) was given.', $object, \gettype( $object ) )
			);
		}
		return get_class( $object );
	}
}