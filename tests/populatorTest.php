<?php

require_once realpath( dirname( __FILE__ ) ) . '/../lib/populator.php';

class Test_Of_Populator extends PHPUnit_Framework_TestCase {
	function testPrivateAndProtectedValuesWillBeSet( ) {
		$values = array(
			'baz' => 'Goodbye',
			'bar' => 'world'
		);

		$this->assertEquals( 'Goodbye, cruel world!', \Populator::populate( new Testclass, $values )->greet( ) );
	}

	function testDefaultValuesCanBeOverwritten( ) {
		$values = array(
			'baz' => 'Hello',
			'bar' => 'world',
			'qux' => 'lovely'
		);

		$this->assertEquals( 'Hello, lovely world!', \Populator::populate( new Testclass, $values )->greet( ) );
	}

	function testUserDefinedSetterMethodsWillBeUsedIfPresent( ) {
		$values = array(
			'foo' => 'automatic, baby!'
		);

		$this->assertEquals( 'Automatic, baby!', \Populator::populate( new Testclass, $values )->foo( ) );
	}
}


class Testclass {
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