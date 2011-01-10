# Populator

## A simple way to get rid of your useless setters.

### Dependencies

- PHP 5.3.x

### Usage

	class YourObject {
		private $foo = 'qux';
		function foo( ) {
			return $this->foo;
		}
	}

	$result = \Populator::populate( new YourObject, array( 'foo' => 'bar' ) );
	echo $result->foo( ); // YourObject::$foo is now "bar".

	// Use setters, too.
	class YourObject {
		private $foo = 'qux';
		function foo( ) {
			return $this->foo;
		}
		function setFoo( $newFoo ) {
			$this->foo = str_reverse( ucfirst( $newFoo ) );
		}
	}

	$result = \Populator::populate( new YourObject, array( 'foo' => 'bar' ) );
	echo $result->foo( ); // YourObject::$foo is now "raB".
