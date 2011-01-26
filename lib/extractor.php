<?php
namespace ObjectMutator;

require_once dirname( __FILE__ ) . '/reflector.php';

/**
 * This class is able to extract all properties from an object, regardless of
 * whether or not those properties are protected, or even private.
 *
 * @author Berry Langerak <berry@berryllium.nl>
 */
class Extractor {

	/**
	 * Extracts all properties of object $object and returns them in an array.
	 *
	 * @param Object $object
	 * @return Array
	 */
	static public function extract( $object ) {
		$properties = array( );
		foreach( Reflector::reflect( $object )->getProperties( ) as $property ) {
			$properties[$property->getName( )] = self::value( $object, $property );
		}
		return $properties;
	}

	/**
	 * Tries to retrieve a value for the property by using a getter. If no
	 * getter is available, it tries to read the property directly and returns
	 * the value.
	 *
	 * @param Object $object
	 * @param \ReflectionProperty $property
	 * @return mixed
	 */
	static protected function value( $object, \ReflectionProperty $property ) {
		if( !( $value = self::useGetter( $object, $property ) ) ) {
			$property->setAccessible( true );
			return $property->getValue( $object );
		}
		return $value;
	}

	/**
	 * Tries to find an accessor for the property $property on object $object.
	 * If an accessor is found, the value from the accessor will be returned. If
	 * no accessor is found, false is returned.
	 *
	 * @param Object $object
	 * @param \ReflectionProperty $property
	 * @return mixed
	 */
	static protected function useGetter( $object, \ReflectionProperty $property ) {
		$method = 'get' . $property->name;
		if( Reflector::hasMethod( $object, $method ) ) {
			return Reflector::method( $object, $method )->invoke( $object );
		}
		return false;
	}
}