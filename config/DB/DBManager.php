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
	var $dbase = "";
	var $user = "";
	var $pass = "";
	var $host = "";
	
	// constructor
	function __construct($connection, $db_server, $db_user, $db_pass, $db_database, $db_name, $db_columns, $key, $foreigns = null){
		parent::__construct($db_server, $db_user, $db_pass, $db_database, $connection);
		$this->host = $db_server;
		$this->user = $db_user;
		$this->pass = $db_pass;
		$this->dbase = $db_database;
		$this->db_name = $db_name;
		$this->foreign_key = $foreigns;
		$this->columns_defs = $db_columns;
		$this->the_key = $key;
		$this->err_data = "";
		foreach ($db_columns as $columnname)
			$this->columns[$columnname] = "NULL";
	}
	
	function fetch($query, $custom=false, $order = null, $asc = true){
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
				$rowobj = new DBManager($this->db, $this->host, $this->user, $this->pass, $this->dbase, $this->db_name, $this->columns_defs, $this->the_key);
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
				$rowobj = new DBManager($this->db, $this->host, $this->user, $this->pass, $this->dbase, $this->db_name, $this->columns_defs, $this->the_key);
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
			$key_names .= " AND ".$cond;
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
	
	/*----------------------------------------PUBLICATION-----------------------------------------*/
	
	function RecuperarCuentas(){
		$consulta = "";
		
		$result = false;
		
		$sql = 'SELECT u.* FROM account u WHERE u.active IS TRUE ORDER BY u.account;';

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
		}
		return $result;
	}
	
	function RecuperarCuentasUsuario($userid){
		$result = false;
		
		$sql = "SELECT u.* FROM account u, account_user au WHERE u.active IS TRUE AND au.id_account = u.db AND au.id_user = '$userid' ORDER BY u.account;";

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarCuentasUsuario) " . $ex->getMessage());
		}
		return $result;
	}
	
	function RecuperarCuentasDis(){
		$consulta = "";
		
		$result = false;
		
		$sql = 'SELECT u.* FROM account u WHERE u.active IS FALSE ORDER BY u.account;';

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarCuentasDis) " . $ex->getMessage());
		}
		return $result;
	}
	
	function RecuperarCuenta($id){
		$id = $this->db->CheckSQL($id);
		
		$consulta = "";
		
		$result = false;
		
		$sql = "SELECT u.* FROM account u WHERE u.db LIKE '$id';";

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarCuenta) " . $ex->getMessage());
		}
		return $result;
	}
	
	function RecuperarCuentasReady(){
		$id = $this->db->CheckSQL($id);
		
		$consulta = "";
		
		$result = false;
		
		$sql = "SELECT u.* FROM account u, reporte_mes r WHERE u.db = r.id_account AND r.sincronizar IS TRUE;";

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarCuentasReady) " . $ex->getMessage());
		}
		return $result;
	}
	
	function CuentasCountST($st){
		$st = $this->db->CheckSQL($st);
		
		$sql="SELECT count(*) FROM account u WHERE u.active = $st;";
		
		try{
			$result = $this->db->Execute($sql);
			if ($row=mysqli_fetch_array($result, MYSQL_NUM))
			{
				if ($row[0]){
					$val = $row[0];
				}else
					$val = 0;
			}else
			{
				$val = 0;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}

		return $val;
	}
	
	function CuentaUsuariosCount($id){
		$id = $this->db->CheckSQL($id);
		
		$sql="SELECT count(*) FROM account_user u WHERE u.id_account LIKE '$id';";
		
		try{
			$result = $this->db->Execute($sql);
			if ($row=mysqli_fetch_array($result, MYSQL_NUM))
			{
				if ($row[0]){
					$val = $row[0];
				}else
					$val = 0;
			}else
			{
				$val = 0;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}

		return $val;
	}
	
	function BorrarCuenta($id){
		$sql = "DELETE a FROM account a WHERE a.db = '$id';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(BorrarCuenta) " . $ex->getMessage());
		}
		
		return $result;
	}
	
	function CuentaExiste($db){
		
		$sql="SELECT a.* FROM account a where a.db = '$db';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}

		if ($row=mysqli_fetch_array($result, MYSQL_NUM))
		{
			return true;
		}else
			return false;
	}
	
	function ActivateAccount($id){
		$id = $this->db->CheckSQL($id);
		
		$sql="SELECT a.active FROM account a WHERE a.db = '$id';";
		
		try{
			$result = $this->db->Execute($sql);
			if ($row=mysqli_fetch_array($result, MYSQL_NUM))
			{
				if ($row[0]){
					$val = 'FALSE';
				}else
					$val = 'TRUE';
			}else
			{
				$val = 'TRUE';
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}
		
		$sql = "UPDATE account SET active = $val WHERE db = '$id';";
		$result = null;
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(ActivateAccount) " . $ex->getMessage());
		}

		return $result;
	}
	
	function VisbleAccount($id){
		$id = $this->db->CheckSQL($id);
		
		$sql="SELECT a.visible FROM account a WHERE a.db = '$id';";
		
		try{
			$result = $this->db->Execute($sql);
			if ($row=mysqli_fetch_array($result, MYSQL_NUM))
			{
				if ($row[0]){
					$val = 'FALSE';
				}else
					$val = 'TRUE';
			}else
			{
				$val = 'TRUE';
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}
		
		$sql = "UPDATE account SET visible = $val WHERE db = '$id';";
		$result = null;
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(VisibleAccount) " . $ex->getMessage());
		}

		return $result;
	}
	
	function ReadyAccount($id, $value = null){
		$id = $this->db->CheckSQL($id);
		
		$sql="SELECT a.ready FROM account a WHERE a.db = '$id';";
		
		if (is_null($value)){
			try{
				$result = $this->db->Execute($sql);
				if ($row=mysqli_fetch_array($result, MYSQL_NUM))
				{
					if ($row[0]){
						$val = 'FALSE';
					}else
						$val = 'TRUE';
				}else
				{
					$val = 'TRUE';
				}
			}
			catch(Exception $ex){
				// si existe un error se deshace la transacci&#65533;n
				throw new Exception($ex->getMessage());
			}
		}else{
			$val = $value;
		}
		
		if ($value == 'TRUE'){
			$sql = "UPDATE account SET ready = $val WHERE db = '$id';";
		}else{
			$sql = "UPDATE account SET ready = $val, lastupdate = CURRENT_TIMESTAMP() WHERE db = '$id';";
		}

		$result = null;
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(ActivateAccount) " . $ex->getMessage());
		}

		return $result;
	}
	
	
	function AgregarCuenta($db, $nombre, $email){
		$db = $this->db->CheckSQL($db);
		$nombre = $this->db->CheckSQL($nombre);
		$email = $this->db->CheckSQL($email);
		
		$sql = "INSERT INTO account VALUES('$db', '$nombre', 1, 0, 1, CURRENT_TIMESTAMP(), '$email');";

		$result = null;
		
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(AgregarCuenta) " . $ex->getMessage());
		}

		return $result;
	}
	
	function UpdateCuenta($db, $nombre, $email, $id){
		$id = $this->db->CheckSQL($id);	
		$db = $this->db->CheckSQL($db);
		$nombre = $this->db->CheckSQL($nombre);
		$email = $this->db->CheckSQL($email);
		
		$sql = "UPDATE account SET db = '$db', account = '$nombre', email = '$email' WHERE db = '$id';";

		$result = null;
		
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(UpdateCuenta) " . $ex->getMessage());
		}

		return $result;
	}
	
	/*---------------------------------REPORT INFORMATION--------------------*/
	function RecuperarReportes(){
		$consulta = "";
		
		$result = false;
		
		$sql = 'SELECT u.* FROM reporte u WHERE u.enabled IS TRUE ORDER BY u.nombre;';

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarReportes) " . $ex->getMessage());
		}
		return $result;
	}
	
	function RecuperarReportesCuenta($cuenta){
		$consulta = "";
		
		$result = false;
		
		$sql = "SELECT u.* FROM reporte u, reporte_cuenta rc WHERE rc.id_reporte = u.id AND rc.id_cuenta = '$cuenta' AND u.enabled IS TRUE ORDER BY u.nombre;";

		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(RecuperarReportesCuenta) " . $ex->getMessage());
		}
		return $result;
	}
	
	function AsignarReporteCuenta($db, $reporte){
		$db = $this->db->CheckSQL($db);
		$reporte = $this->db->CheckSQL($reporte);
		
		$sql = "INSERT INTO reporte_cuenta VALUES('$db', $reporte);";

		$result = null;
		
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(AsignarReporteCuenta) " . $ex->getMessage());
		}

		return $result;
	}
	
	function DesasignarReporteCuenta($db, $reporte){
		$db = $this->db->CheckSQL($db);
		$reporte = $this->db->CheckSQL($reporte);
		
		$sql = "DELETE a FROM reporte_cuenta a WHERE a.id_cuenta = '$db' AND a.id_reporte = $reporte;";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(DesasignarReporteCuenta) " . $ex->getMessage());
		}
		
		return $result;
	}
	
	function DesasignarTodosReporteCuenta($db){
		$db = $this->db->CheckSQL($db);
		$reporte = $this->db->CheckSQL($reporte);
		
		$sql = "DELETE a FROM reporte_cuenta a WHERE a.id_cuenta = '$db';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(DesasignarTodosReporteCuenta) " . $ex->getMessage());
		}
		
		return $result;
	}
	
	function ReportAssigned($report, $db){
		
		$sql="SELECT a.* FROM account a, reporte_cuenta rc where a.db = rc.id_cuenta AND rc.id_reporte = '$report' AND a.db = '$db';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}

		if ($row=mysqli_fetch_array($result, MYSQL_NUM))
		{
			return true;
		}else
			return false;
	}
	
	/*-------------------------------USERS IN ACCOUNT-------------------------------*/
	
	function AsignarUsuarioCuenta($db, $user, $main = 'FALSE'){
		$db = $this->db->CheckSQL($db);
		$user = $this->db->CheckSQL($user);
		
		$sql = "INSERT INTO account_user VALUES('$db', '$user', $main);";

		$result = null;
		
		try{
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(AsignarUsuarioCuenta) " . $ex->getMessage());
		}

		return $result;
	}
	
	function DesasignarUsuarioCuenta($db, $user){
		$db = $this->db->CheckSQL($db);
		$user = $this->db->CheckSQL($user);
		
		$sql = "DELETE a FROM account_user a WHERE a.id_account = '$db' AND a.id_user = '$user';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(DesasignarUsuarioCuenta) " . $ex->getMessage());
		}
		
		return $result;
	}
	
	function DesasignarTodosUsuarioCuenta($db){
		$db = $this->db->CheckSQL($db);
		
		$sql = "DELETE a FROM account_user a WHERE a.id_account = '$db';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception("(DesasignarTodosUsuarioCuenta) " . $ex->getMessage());
		}
		
		return $result;
	}
	
	function UserAssigned($user, $db){
		
		$sql="SELECT a.* FROM account a, account_user au where a.db = au.id_account AND au.id_user = '$user' AND a.db = '$db';";
		
		try{
			$result = $this->db->Execute($sql);
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			throw new Exception($ex->getMessage());
		}

		if ($row=mysqli_fetch_array($result, MYSQL_NUM))
		{
			return true;
		}else
			return false;
	}
	
	
	
}
?>