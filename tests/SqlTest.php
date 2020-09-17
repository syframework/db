<?php

use PHPUnit\Framework\TestCase;
use Sy\Db\Sql;

class SqlTest extends TestCase {

	public function testSql() {
		$sql = new Sql('SELECT * FROM t_user WHERE firstname IN (:firstname)', array(':firstname' => array('John', 'Jane')));
		$this->assertEquals($sql->getSql(), 'SELECT * FROM t_user WHERE firstname IN (:firstname0,:firstname1)');
		$this->assertEquals($sql->getParams(), array(':firstname0' => 'John', ':firstname1' => 'Jane'));
	}

}