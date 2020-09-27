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
		$this->gate = new Gate('sqlite::memory:');
		$this->gate->execute('
			CREATE TABLE t_user (
				id INTEGER PRIMARY KEY,
				firstname TEXT NOT NULL,
				lastname TEXT NOT NULL
			)
		');
		$this->gate->insert('t_user', array('id' => 1, 'firstname' => 'John', 'lastname' => 'Doe'));
		$this->gate->insert('t_user', array('id' => 2, 'firstname' => 'Jane', 'lastname' => 'Doe'));
		$this->gate->insert('t_user', array('id' => 3, 'firstname' => 'John', 'lastname' => 'Wick'));
	}
	
	protected function tearDown(): void {
		$this->gate->execute('DROP TABLE t_user');
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
		$this->gate->insert('t_user', array('id' => 4, 'firstname' => 'hello', 'lastname' => 'world'));
		$res = $this->gate->queryAll('SELECT * FROM t_user WHERE id=4', PDO::FETCH_ASSOC);
		$this->assertEquals($res, array(array('id' => '4', 'firstname' => 'hello', 'lastname' => 'world')));
	}

}