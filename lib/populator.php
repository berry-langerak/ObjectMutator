<?php
/**
 * This class is able to populate all properties in an object, regardless of 
 * whether or not those properties are protected, or even private.
 *
 * @author Berry Langerak <berry@berryllium.nl>
 */
class Populator {

	/**
	 * An array of previously instantiated ReflectionClasses, for caching.
	 * @var Array
	 */
	static protected $reflections = array( );

	/**
	 * An array of previously instatiated ReflectionProperties, for caching.
	 * @var Array
	 */
	static protected $properties = array( );

	/**
	 * Tries to populate object $object with the values from array $values, then
	 * returns the object.
	 *
	 * @param Object $object
	 * @param Array $values
	 * @return Object
	 */
	static public function populate( $object, $values ) {
		foreach( self::reflect( $object )->getProperties( ) as $property ) {
			if( array_key_exists( $property->name, $values ) ) {
				if( !self::useSetter( $object, $property, $values ) ) {
					$property->setAccessible( true );
					$property->setValue( $object, $values[$property->name] );
				}
			}
		}
		return $object;
	}

	/**
	 * Tries to determine the name of and use a setter for this property, and
	 * returns whether or not a setter was used.
	 *
	 * @param Object $object
	 * @param \ReflectionProperty $property
	 * @param array $values
	 * @return boolean
	 */
	static protected function useSetter( $object, $property, $values ) {
		$setter = 'set' . strtolower( $property->name );
		if( in_array( $setter, array_map( 'strtolower', get_class_methods( $property->class ) ) ) ) {
			$object->$setter( $values[$property->name] );
			return true;
		}
		return false;
	}

	/**
	 * Creates, caches and returns a ReflectionClass object.
	 *
	 * @param Object $object
	 * @return ReflectionClass
	 */
	static protected function reflect( $object ) {
		$classname = get_class( $object );
		if( !array_key_exists( get_class( $object ), self::$reflections ) ) {
			self::$reflections[$classname] = new \ReflectionClass( $classname );
		}
		return self::$reflections[$classname];
	}
}