# ObjectMutator

## A simple library to get rid of useless setters and getters (or, accessors and mutators) in PHP > 5.3.0.

### Dependencies

- PHP 5.3.x

### Usage

#### Populator

	require_once '/path/to/lib/populator.php';

	class YourObject {
		private $foo = 'qux';
		function foo( ) {
			return $this->foo;
		}
	}

	$result = \ObjectMutator\Populator::populate( new YourObject, array( 'foo' => 'bar' ) );
	echo $result->foo( ); // YourObject::$foo is now "bar".

	/**
	 * You can use mutators, too, if you like to change the value before actually setting it.
	 * You can even use private mutators; they will be called by the Populator as well.
	 */
	class YourObject {
		private $foo = 'qux';
		function foo( ) {
			return $this->foo;
		}
		private function setFoo( $newFoo ) {
			$this->foo = str_reverse( ucfirst( $newFoo ) );
		}
	}

	$result = \ObjectMutator\Populator::populate( new YourObject, array( 'foo' => 'bar' ) );
	echo $result->foo( ); // YourObject::$foo is now "raB".

#### Extractor

	require_once( '/path/to/lib/extractor.php' );

	class YourObject {
		protected $foo;
		public function __construct( ) {
			$this->foo = 'test';
		}
		private function getFoo( ) {
			return str_reverse( ucfirst( $this->foo ) );
		}
	}

	$result = \ObjectMutator\Extractor::extract( new YourObject );
	echo $result['foo']; // this is now "ooF", as the private accessor is called.
