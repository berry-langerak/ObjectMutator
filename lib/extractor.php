<?php
namespace ObjectMutator;

require_once dirname( __FILE__ ) . '/reflector.php';

/**
 * This class is able to extract all properties from an object, regardless of
 * whether or not those properties are protected, or even private.
 *
 * @author Berry Langerak <berry@berryllium.nl>
 * @todo Make sure extract can also extract to objects (e.g. DataTransferObjects)
 */
class Extractor {

	/**
	 * Contains an instance of the Reflector object.
	 * 
	 * @var \ObjectMutator\Reflector
	 */
	protected $reflector;

	/**
	 * Extracts all properties of object $object and returns them in an array.
	 *
	 * @param Object $object
	 * @return Array
	 */
	public function extract( $object, $target = array( ) ) {
		foreach( $this->reflector( )->reflect( $object )->getProperties( ) as $property ) {
			$target = $this->setValue( $target, $property->getName( ), $this->value( $object, $property ) );
		}
		return $target;
	}

	/**
	 * Sets the value of property $property to value $value on target $target. The target may be an array or an object.
	 *
	 * @param mixed $target
	 * @param string $property
	 * @param mixed $value
	 * @return mixed
	 */
	protected function setValue( $target, $property, $value ) {
		if( \is_object( $target ) ) {
			$target->$property = $value;
			return $target;
		}
		$target[$property] = $value;
		return $target;
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
	protected function value( $object, \ReflectionProperty $property ) {
		if( !( $value = $this->useGetter( $object, $property ) ) ) {
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
	protected function useGetter( $object, \ReflectionProperty $property ) {
		$method = 'get' . $property->name;
		if( $this->reflector( )->hasMethod( $object, $method ) ) {
			return $this->reflector( )->method( $object, $method )->invoke( $object );
		}
		return false;
	}

	/**
	 * Lazy loads the Reflector object.
	 *
	 * @return \ObjectMutator\Reflector
	 */
	protected function reflector( ) {
		if( $this->reflector === null ) {
			$this->reflector = new \ObjectMutator\Reflector( );
		}
		return $this->reflector;
	}
}