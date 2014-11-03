<?php
// Contiene metodos y funciones para gestionar la base de datos
include_once("DataBaseManager.php");

class DBManager extends DataBaseManager{

	// atributos heredados
	// $db
	public $columns = array();
	public $db_name;
	public $err_data;
	public $the_key = array();
	public $foreign_key = array();
	var $fetched = false;
	var $columns_defs = array();
	var $connection;
	
	// constructor
	function __construct($connection, $db_name, $db_columns, $key, $foreigns = null){
		parent::__construct($connection);
		$this->db_name = $db_name;
		$this->foreign_key = $foreigns;
		$this->columns_defs = $db_columns;
		$this->the_key = $key;
		$this->err_data = "";
		$this->connection = $connection;
		foreach ($db_columns as $columnname)
			$this->columns[$columnname] = "NULL";
	}
	
	function fetch($query="", $custom=false, $order = null, $asc = true){
		$this->err_data = "";
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= "ASC";
			}else{
				$order_text .= "DESC";
			}
		}
		
		
		
		if (!$custom){
			if ($query != ""){
				$query = " WHERE ".$query;
			}
			
			$sql = 'SELECT * FROM '.$this->db_name.''.$query.' '.$order_text.';';
		}else {
			$sql = $query;
		}
		
		$retorno = array();
		
		try{
			$result = $this->db->Execute($sql);
			
			while ($row = mysqli_fetch_assoc($result)){
				$rowobj = new DBManager($this->connection, $this->db_name, $this->columns_defs, $this->the_key);
				foreach($this->columns_defs as $definitions){
					$rowobj->columns[$definitions] = $row[$definitions];
				}
				$retorno[] = $rowobj;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $retorno;
	}

	function fetch_obj_in($objs, $cond="", $order = null, $asc = true){
		$consulta = "";
		$this->err_data = "";
		
		$valid = false;
		$statements = array();
		$tables = array();
		
		$base_letter = "A";
		$tables[] = $this->db_name." ".$base_letter;
		$base_table = $base_letter;
		
		foreach ($objs as $obj) {
			$base_letter++;
			$tables[] = $obj->db_name." ".$base_letter;
			foreach ($this->foreign_key as $fkey => $value) {
				if ($value[0] == $obj->db_name){
					if (in_array($value[1], $obj->the_key)){
						$statements[] = $base_letter.".".$value[1]."="
						.((($this->GetType($obj->columns[$value[1]]) == 'boolean' 
						|| $this->GetType($obj->columns[$value[1]]) == 'float' 
						|| $this->GetType($obj->columns[$value[1]]) == 'integer' 
						|| $this->GetType($obj->columns[$value[1]]) == 'numeric' 
						|| $this->GetType($obj->columns[$value[1]]) == 'NULL'))?'':"'")
							.(($this->GetType($obj->columns[$value[1]]) == 'NULL')?'NULL':$obj->columns[$value[1]])
						.((($this->GetType($obj->columns[$value[1]]) == 'boolean' 
						|| $this->GetType($obj->columns[$value[1]]) == 'float' 
						|| $this->GetType($obj->columns[$value[1]]) == 'integer' 
						|| $this->GetType($obj->columns[$value[1]]) == 'numeric' 
						|| $this->GetType($obj->columns[$value[1]]) == 'NULL'))?'':"'");
						$statements[] = $base_letter.".".$value[1]."=".$base_table.".".$fkey;
					}	
				}
			}
		}
		
		$tablestr = "";
		$count = 0;
		foreach ($tables as $table) {
			if ($count != 0)
				$tablestr .= ", ";
			$tablestr .= $table;
			$count++;
		}
		
		$joinstr = "";
		$count = 0;
		foreach ($statements as $statement) {
			if ($count != 0)
				$joinstr .= " AND ";
			$joinstr .= $statement;
			$count++;
		}
		
		if ($cond != ""){
			$joinstr .= " AND ".$cond;
		}
		
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= "ASC";
			}else{
				$order_text .= "DESC";
			}
		}
		
	    $sql = 'SELECT '.$base_table.'.* FROM '.$tablestr.' WHERE '.$joinstr.' '.$order_text.';';
		
		$retorno = array();
		
		try{
			$result = $this->db->Execute($sql);
			
			while ($row = mysqli_fetch_assoc($result)){
				$rowobj = new DBManager($this->connection, $this->db_name, $this->columns_defs, $this->the_key);
				foreach($this->columns_defs as $definitions){
					$rowobj->columns[$definitions] = $row[$definitions];
				}
				$retorno[] = $rowobj;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $retorno;
		
	}
	
	function fetch_id($id, $order = null, $asc = true, $cond = ""){
		$consulta = "";
		$this->err_data = "";
		
		$result = false;
		
		$key_names = "";
		$count = 0;
		
		if (count($id) > 0)
			foreach ($this->the_key as $keys) {
				
				if ($count > 0)
					$key_names .= ' AND ';
				
				$key_names .= $keys."="
					.((($this->GetType($id[$keys]) == 'boolean' 
					|| $this->GetType($id[$keys]) == 'float' 
					|| $this->GetType($id[$keys]) == 'integer' 
					|| $this->GetType($id[$keys]) == 'numeric' 
					|| $this->GetType($id[$keys]) == 'NULL'))?"":"'")
						.(($this->GetType($id[$keys]) == 'NULL')?'NULL':$id[$keys])
					.((($this->GetType($id[$keys]) == 'boolean' 
					|| $this->GetType($id[$keys]) == 'float' 
					|| $this->GetType($id[$keys]) == 'integer' 
					|| $this->GetType($id[$keys]) == 'numeric' 
					|| $this->GetType($id[$keys]) == 'NULL'))?"":"'");
				$count++;
				
			}
		
		
		
		if ($cond != ""){
			$key_names .= ($key_names != "")?" AND ":"";
			$key_names .= $cond;
		}
		
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= "ASC";
			}else{
				$order_text .= "DESC";
			}
		}
		
	    $sql = 'SELECT * FROM '.$this->db_name.' WHERE '.$key_names.' '.$order_text.';';

		try{
			$result = $this->db->Execute($sql);
			
			if ($row = mysqli_fetch_assoc($result)){
				foreach($this->columns_defs as $definitions){
					$this->columns[$definitions] = $row[$definitions];
				}
			}else
				return FALSE;
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return TRUE;
	}

	function count($id, $order = null, $asc = true, $cond = ""){
		$this->err_data = "";
		
		$result = false;
		
		$key_names = "";
		$count = 0;
		
		foreach ($this->the_key as $keys) {
			
			if ($count > 0)
				$key_names .= ' AND ';
			
			$key_names .= $keys."="
				.((($this->GetType($id[$keys]) == 'boolean' 
				|| $this->GetType($id[$keys]) == 'float' 
				|| $this->GetType($id[$keys]) == 'integer' 
				|| $this->GetType($id[$keys]) == 'numeric' 
				|| $this->GetType($id[$keys]) == 'NULL'))?"":"'")
					.(($this->GetType($id[$keys]) == 'NULL')?'NULL':$id[$keys])
				.((($this->GetType($id[$keys]) == 'boolean' 
				|| $this->GetType($id[$keys]) == 'float' 
				|| $this->GetType($id[$keys]) == 'integer' 
				|| $this->GetType($id[$keys]) == 'numeric' 
				|| $this->GetType($id[$keys]) == 'NULL'))?"":"'");
			$count++;
			
		}
		
		if ($cond != ""){
			if ($count > 0)
				$key_names .= ' AND ';
			$key_names .= $cond;
		}
		
		$count = 0;
	    $sql = 'SELECT count(*) FROM '.$this->db_name.' WHERE '.$key_names.' '.$order_text.';';

		try{
			$result = $this->db->Execute($sql);
			
			if ($row = mysqli_fetch_array($result)){
				$count = $row[0];
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $count;
	}
	
	function delete($id){
		$this->err_data = "";
		
		$key_names = "";
		$count = 0;
		foreach ($this->the_key as $keys) {
			if ($count > 0)
				$key_names .= ' AND ';
			$key_names = 'a.'.$key.'='
				.((($this->GetType($this->columns[$keys]) == 'boolean' 
				|| $this->GetType($this->columns[$keys]) == 'float' 
				|| $this->GetType($this->columns[$keys]) == 'integer' 
				|| $this->GetType($this->columns[$keys]) == 'numeric' 
				|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'")
					.(($this->GetType($this->columns[$keys]) == 'NULL')?'NULL':$this->columns[$keys])
				.((($this->GetType($this->columns[$keys]) == 'boolean' 
				|| $this->GetType($this->columns[$keys]) == 'float' 
				|| $this->GetType($this->columns[$keys]) == 'integer' 
				|| $this->GetType($this->columns[$keys]) == 'numeric' 
				|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'");
			$count++;
		}
		
		$sql = "DELETE a FROM ".$this->db_name." a WHERE ".$key_names.";";
		
		try{
			$this->BeginTransaction();
			
			$this->Commit();	
		}
		catch(Exception $ex){
			$this->RollBack();
			//throw new Exception("DELETE) " . $e->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		
		return TRUE;
	}
	
	function update($conditions = null){
		$query = "";
		$count = 0;
		try{
			$this->BeginTransaction();
			
			foreach ($this->columns as $key => $value) {
				if ($count > 0)
					$query .= ' ,';
				$query .= $key.'='
					.((($this->GetType($value) == 'boolean' 
					|| $this->GetType($value) == 'float' 
					|| $this->GetType($value) == 'integer' 
					|| $this->GetType($value) == 'numeric' 
					|| $this->GetType($value) == 'NULL'))?'':"'")
						.(($this->GetType($value) == 'NULL')?'NULL':$value)
					.((($this->GetType($value) == 'boolean' 
					|| $this->GetType($value) == 'float' 
					|| $this->GetType($value) == 'integer' 
					|| $this->GetType($value) == 'numeric' 
					|| $this->GetType($value) == 'NULL'))?'':"'");
				$count++;
			}
			
			$key_names = "";
			$count = 0;
			foreach ($this->the_key as $keys) {
				if ($count > 0)
					$key_names .= ' AND ';
				$key_names = $key.'='
					.((($this->GetType($this->columns[$keys]) == 'boolean' 
					|| $this->GetType($this->columns[$keys]) == 'float' 
					|| $this->GetType($this->columns[$keys]) == 'integer' 
					|| $this->GetType($this->columns[$keys]) == 'numeric' 
					|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'")
						.(($this->GetType($this->columns[$keys]) == 'NULL')?'NULL':$this->columns[$keys])
					.((($this->GetType($this->columns[$keys]) == 'boolean' 
					|| $this->GetType($this->columns[$keys]) == 'float' 
					|| $this->GetType($this->columns[$keys]) == 'integer' 
					|| $this->GetType($this->columns[$keys]) == 'numeric' 
					|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'");
				$count++;
			}
			
			$sql = "UPDATE ".$this->db_name." SET ".$query." WHERE ".$key_names;
			
			if (!is_null($conditions)){
				$sql .= ' AND '.$conditions;
			}
			
			$result = $this->db->Execute($sql);
			
			$this->Commit();	
		}catch(Exception $e)
		{
			$this->RollBack();
			$this->err_data = $e->getMessage();
			return FALSE;
			//throw new Exception("(UPDATE) " . $e->getMessage());
		}
		return TRUE;
				
	}

	function insert(){
		$query = "";
		$count = 0;
		$result = FALSE;
		
		try{
			$this->BeginTransaction();
			
			foreach ($this->columns as $key => $value) {
				if ($count > 0)
					$query .= ' ,';
				$query .= ((($this->GetType($value) == 'boolean' 
					|| $this->GetType($value) == 'float' 
					|| $this->GetType($value) == 'integer' 
					|| $this->GetType($value) == 'numeric' 
					|| $this->GetType($value) == 'NULL'))?'':"'")
						.(($this->GetType($value) == 'NULL')?'NULL':$value)
					.((($this->GetType($value) == 'boolean' 
					|| $this->GetType($value) == 'float' 
					|| $this->GetType($value) == 'integer' 
					|| $this->GetType($value) == 'numeric' 
					|| $this->GetType($value) == 'NULL'))?'':"'");
				$count++;
			}
			
			$sql = "INSERT INTO ".$this->db_name." VALUES ( ".$query." ) ";
			
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
			
			$this->Commit();	
		}catch(Exception $e)
		{
			$this->RollBack();
			$this->err_data = $e->getMessage();
			return FALSE;
		}
		return $result;
	}	
	
}
?>