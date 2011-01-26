<?php

require_once realpath( dirname( __FILE__ ) ) . '/../lib/populator.php';

class Test_Of_Populator extends PHPUnit_Framework_TestCase {
	function testPrivateAndProtectedValuesWillBeSet( ) {
		$values = array(
			'baz' => 'Goodbye',
			'bar' => 'world'
		);

		$this->assertEquals( 'Goodbye, cruel world!', \ObjectMutator\Populator::populate( new TestclassPopulator, $values )->greet( ) );
	}

	function testDefaultValuesCanBeOverwritten( ) {
		$values = array(
			'baz' => 'Hello',
			'bar' => 'world',
			'qux' => 'lovely'
		);

		$this->assertEquals( 'Hello, lovely world!', \ObjectMutator\Populator::populate( new TestclassPopulator, $values )->greet( ) );
	}

	function testUserDefinedSetterMethodsWillBeUsedEvenIfPrivate( ) {
		$values = array(
			'foo' => 'hello'
		);

		$this->assertEquals( 'HELLO', \ObjectMutator\Populator::populate( new SecondTestclassPopulator( ), $values )->foo( ) );
	}
}

class TestclassPopulator {
	protected $foo;

	private $baz;
	protected $bar;
	private $qux = 'cruel';

	function setFoo( $newFoo ) {
		$this->foo = ucfirst( $newFoo );
	}

	function greet( ) {
		return $this->baz . ', ' . $this->qux . ' ' . $this->bar . '!';
	}

	function foo( ) {
		return $this->foo;
	}
}

class SecondTestclassPopulator {
	private $foo;
	private function setFoo( $value ) {
		$this->foo = strtoupper( $value );
	}

	public function foo( ) {
		return $this->foo;
	}
}