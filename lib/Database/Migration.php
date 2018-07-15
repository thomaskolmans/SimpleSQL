<?php
namespace lib\database\Migration;

class Migration{

	private $simplesql;

	constructor(SimpleSQL $simplesql){
		$this->simplesql = $simplesql
	}

	public function findFiles(){
		
	}

	public function migrate(){

	}

	public function clean(){

	}
	
	public function createMigrationTable(){
		$this->simplesql->insert("simplesql_migration",[
			""
		])
	}
	
}
?>