<?php
namespace ObjectMutator;

class Reflector {
	static protected $reflections = array( );

	static protected $methods = array( );

	/**
	 * Tries to create a \ReflectionClass for object $object. If it was previously
	 * made, the previous instance will be returned.
	 *
	 * @param Object $object
	 * @return \ReflectionClass
	 */
	static public function reflect( $object ) {
		$classname = self::classname( $object );
		if( !array_key_exists( $classname, self::$reflections ) ) {
			self::$reflections[$classname] = new \ReflectionClass( $classname );
		}
		return self::$reflections[$classname];
	}

	static public function hasMethod( $object, $method ) {
		return array_key_exists( $method, self::methods( self::reflect( $object ) ) );
	}

	static protected function methods( \ReflectionClass $class ) {
		if( !isset( self::$methods[$class->name] ) ) {
			foreach( $class->getMethods( ) as $method ) {
				self::$methods[$class->name][strtolower( $method->name )] = $method;
			}
		}
		return self::$methods[$class->name];
	}

	static public function method( $object, $method ) {
		if( self::hasMethod( $object, $method ) ) {
			$method = self::reflect( $object )->getMethod( $method );
			$method->setAccessible( true );
			return $method;
		}
		throw new \Exception( 'Ah noes!' );
	}

	static public function classname( $object ) {
		if( !is_object( $object ) ) {
			throw new \Exception( 
				sprintf( 'ObjectMutator needs an instance of an object, but "%s" (%s) was given.', $object, \gettype( $object ) )
			);
		}
		return get_class( $object );
	}
}