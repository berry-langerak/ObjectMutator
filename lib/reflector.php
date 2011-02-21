<?php
namespace ObjectMutator;

class Reflector {
	protected $reflections = array( );

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

	public function hasMethod( $object, $method ) {
		return array_key_exists( $method, $this->methods( $this->reflect( $object ) ) );
	}

	protected function methods( \ReflectionClass $class ) {
		if( !isset( $this->methods[$class->name] ) ) {
			foreach( $class->getMethods( ) as $method ) {
				$this->methods[$class->name][strtolower( $method->name )] = $method;
			}
		}
		return $this->methods[$class->name];
	}

	public function method( $object, $method ) {
		if( $this->hasMethod( $object, $method ) ) {
			$method = $this->reflect( $object )->getMethod( $method );
			$method->setAccessible( true );
			return $method;
		}
	}

	public function classname( $object ) {
		if( !is_object( $object ) ) {
			throw new \Exception( 
				sprintf( 'ObjectMutator needs an instance of an object, but "%s" (%s) was given.', $object, \gettype( $object ) )
			);
		}
		return get_class( $object );
	}
}