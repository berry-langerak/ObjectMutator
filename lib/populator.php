<?php
namespace ObjectMutator;

require_once dirname( __FILE__ ) . '/reflector.php';

/**
 * This class is able to populate all properties in an object, regardless of 
 * whether or not those properties are protected, or even private.
 *
 * @author Berry Langerak <berry@berryllium.nl>
 */
class Populator {

	/**
	 * Tries to populate object $object with the values from array $values, then
	 * returns the object.
	 *
	 * @param Object $object
	 * @param Array $values
	 * @return Object
	 */
	static public function populate( $object, $values ) {
		foreach( Reflector::reflect( $object )->getProperties( ) as $property ) {
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
		if( Reflector::hasMethod( $object, $setter ) ) {
			Reflector::method( $object, $setter )->invoke( $object, $values[$property->name] );
			return true;
		}
		return false;
	}
}