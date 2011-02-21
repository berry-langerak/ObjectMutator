<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once realpath( dirname( __FILE__ ) ) . '/../lib/extractor.php';

class Test_Of_Extractor extends PHPUnit_Framework_TestCase {
	function setUp( ) {
		$this->extractor = new \ObjectMutator\Extractor( );
	}
	function testPrivateAndProtectedValuesWillBeRetrieved( ) {
		$expected = array(
			'foo' => 'foo',
			'bar' => 'bar',
			'baz' => 'baz',
			'qux' => 'qux'
		);

		$this->assertEquals( $expected, $this->extractor->extract( new TestclassExtractor ) );
	}

	function testUserDefinedGetterMethodsWillBeUsedIfPresent( ) {
		$expected = array(
			'foo' => 'foo',
			'bar' => 'bar',
			'qux' => 'Qux'
		);

		$this->assertEquals( $expected, $this->extractor->extract( new SecondTestClassExtractor ) );
	}

	function testUserDefinedGetterMethodsWillBeUsedEvenIfPrivate( ) {
		$expected = array(
			'foo' => 'foo',
			'bar' => 'bar',
			'baz' => 'BAZ'
		);

		$this->assertEquals( $expected, $this->extractor->extract( new ThirdTestClassExtractor( ) ) );
	}

	function testExtractCanExtractToAnObjectInsteadOfArray( ) {
		$expected = new stdClass( );
		$expected->foo = 'foo';
		$expected->bar = 'bar';
		$expected->baz = 'BAZ';

		$this->assertEquals( $expected, $this->extractor->extract( new ThirdTestClassExtractor( ), new stdClass( ) ) );
	}
}


class TestclassExtractor {
	protected $foo;

	private $baz;
	protected $bar;
	protected $qux;

	public function __construct( ) {
		$this->foo = 'foo';
		$this->baz = 'baz';
		$this->bar = 'bar';
		$this->qux = 'qux';
	}
}

class SecondTestClassExtractor extends TestclassExtractor {
	public function getQux( ) {
		return ucfirst( $this->qux );
	}
}

class ThirdTestClassExtractor {
	protected $foo = 'foo';
	protected $bar;
	protected $baz;

	public function __construct( ) {
		list( $this->bar, $this->baz ) = array( 'bar', 'baz' );
	}

	private function getBaz( ) {
		return strtoupper( $this->baz );
	}
}