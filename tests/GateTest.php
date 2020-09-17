<?php

use PHPUnit\Framework\TestCase;
use Sy\Db\Gate;
use Sy\Db\Sql;

class GateTest extends TestCase {

	/**
	 * @var Gate
	 */
	protected $gate;

	protected function setUp() : void {
		$this->gate = new Gate('sqlite:' . __DIR__ . '/database.db');
		$this->gate->execute('
			CREATE TABLE test_table (
				id INTEGER PRIMARY KEY,
				name TEXT NOT NULL
			)
		');
	}
	
	protected function tearDown(): void {
		$this->gate->execute('DROP TABLE test_table');
	}

	public function testQuery() {
		$statement = $this->gate->query('SELECT * FROM t_user WHERE id=1');
		$res =$statement->fetch(PDO::FETCH_ASSOC);
		$this->assertEquals($res, array('id' => '1', 'firstname' => 'John', 'lastname' => 'Doe'));
	}

	public function testQuerySql() {
		$res = $this->gate->queryAll(new Sql('SELECT * FROM t_user WHERE firstname=(:firstname)', array(':firstname' => 'John')), PDO::FETCH_ASSOC);
		$this->assertEquals($res, array(
			array('id' => '1', 'firstname' => 'John', 'lastname' => 'Doe'),
			array('id' => '3', 'firstname' => 'John', 'lastname' => 'Wick'),
		));
	}

	public function testQueryAll() {
		$res = $this->gate->queryAll('SELECT * FROM t_user WHERE id=1', PDO::FETCH_ASSOC);
		$this->assertEquals($res, array(array('id' => '1', 'firstname' => 'John', 'lastname' => 'Doe')));
	}

	public function testQueryColumn() {
		$res = $this->gate->queryColumn('SELECT * FROM t_user WHERE id=1', 1);
		$this->assertEquals($res, 'John');
	}

	public function testQueryObject() {
		$res = $this->gate->queryObject('SELECT * FROM t_user WHERE id=1');
		$this->assertEquals($res->firstname, 'John');
	}

	public function testQueryOne() {
		$res = $this->gate->queryOne('SELECT * FROM t_user', PDO::FETCH_ASSOC);
		$this->assertEquals($res, array('id' => '1', 'firstname' => 'John', 'lastname' => 'Doe'));
	}

	public function testInsert() {
		$this->gate->insert('test_table', array('id' => 1, 'name' => 'hello'));
		$res = $this->gate->queryAll('SELECT * FROM test_table', PDO::FETCH_ASSOC);
		$this->assertEquals($res, array(array('id' => '1', 'name' => 'hello')));
	}

}