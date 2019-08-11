<?php
//-----------
//READ ME
//------------
//You can use this php objects and methods to easily access database
//You can also add methods or modify codes into it but keep it meaningful ... and dont forget to document it
//You can debug or fix code if you find errors or bugs in this script
//
//*************************************************************************************************************


class TableInDB{
	public $name;	// table name
	private $database;	// database object (i.e mysqli object)
	private $coloumns;
	private $in_database;

	public function __construct($name,$database){
		$this->name=$name;
		$this->database=$database;

		if(!$this->check_in_database()) exit("\nERROR : Table does not matched !");
	}

	//###################################
	// Checking whether the table itself exists in database or not
	// Also collects and stores all coloumns name in an array 
	private function check_in_database(){
		$sql="DESC ".$this->name;
		if($result_table=$this->database->query($sql)){
			$this->in_database=true;
			$coloumn_index=0;
			while($result_row=$result_table->fetch_assoc()){
				$this->coloumns[$coloumn_index++]=$result_row['Field'];
			}
		}
		else $this->in_database=false;
		
		return $this->in_database;
	}
	//###################################

	//###################################	
	public function get_coloumns_name(){
		
		return $this->coloumns;
	}
	//###################################

	//###################################
	// Fetch data from table 
	// Parametes -> array of coloumns_name to be selected, condition part in a sql in string (i.e -> "where name='John' ")
	// Returns row as array
	public function select($coloumns,$condition_in_string=""){


		$coloumns_all="";
		for($i=0;$i<count($coloumns);$i++){
			$coloumns_all.=$coloumns[$i];
			if($i+1!=count($coloumns)) $coloumns_all.=',';
		}
		
		$sql="SELECT ".$coloumns_all." from ".$this->name." ".$condition_in_string;
		if($result_table=$this->database->query($sql)){
			if(!$result_table->num_rows) return false;
			$row_index=0;
			while($result_row=$result_table->fetch_assoc()){
				for($i=0;$i<count($coloumns);$i++){
					$data[$row_index][$coloumns[$i]]=$result_row[$coloumns[$i]];
				}
				$row_index++;
			}
		}
		else{
			exit('ERROR: in SQL COMMAND - check in CONDITION PART');
			// return false;	
		} 
		return $data;
	}
	//###################################

	//###################################
	// Insert data into table 
	// Parametes -> associative array of values with key as coloumns_name 
	// Returns boolean value
	public function insert($values){
		

		$data=array_values($values);
		$keys=array_keys($values);
		
		//-------------------------------------
		$data_all="";
		$data_all.="(";	
		for($i=0;$i<count($data);$i++){
			if(gettype($data[$i])=='string'){
			$data_all.="'";	
			$data_all.=$data[$i];	
			$data_all.="'";
			}
			else $data_all.=$data[$i];	

			if($i+1!=count($data)){
				$data_all.=",";	
			}
		}
		$data_all.=")";
		//-------------------------------------

		//-------------------------------------
		$keys_all="";
		$keys_all.="(";	
		for($i=0;$i<count($keys);$i++){
			$keys_all.=$keys[$i];	
			if($i+1!=count($keys)) $keys_all.=",";
		}
		$keys_all.=")";
		//-------------------------------------

		$sql="INSERT INTO ".$this->name.$keys_all." VALUES".$data_all;
		// echo $sql;
		if($this->database->query($sql)) return true;
		return false;

	}
	//###################################

	//###################################
	// Update data in table 
	// Parametes -> associative array of values with key as coloumns_name , condition part in a sql in string (i.e -> "where name='John' ")
	// Returns boolean value
	// NOTE: $where_condition bugs in this method are not fixed yet
	public function update($values,$where_condition){

		if($where_condition==""||empty($where_condition)) return false;
		$data=array_values($values);
		$keys=array_keys($values);
		
		//-------------------------------------
		$settings="";
		for($i=0;$i<count($data);$i++){
			$settings.=$keys[$i];
			$settings.='=';

			if(gettype($data[$i])=='string'){
			$settings.="'";	
			$settings.=$data[$i];	
			$settings.="'";
			}
			else $settings.=$data[$i];	

			if($i+1!=count($data)){
				$settings.=",";	
			}
		}
		$sql="UPDATE ".$this->name." SET ".$settings." ".$where_condition;
		// echo $sql;
		
		//-------------------------------------

		if($this->database->query($sql)) return true;
		return false;

	}
	//###################################

	//###################################
	// Delete data in table
	// Parameters -> condition part in a sql in string (i.e -> "where id = 32 ")
	// Returns boolean value
	public function delete($condition_in_string){
		

		if($condition_in_string==""||empty($condition_in_string)) return false;
		if($condition_in_string=="all rows") $condition_in_string="";
		$sql="DELETE FROM ".$this->name." ".$condition_in_string;
		if($this->database->query($sql)) return true;
		return false;
	}
	//###################################	

	//###################################
	// Check whether the value of an attribute or coloumn is in the table or not
	// Parameters -> coloumn_name in string , condition part in a sql in string (i.e -> "where id = 32 ")
	// Returns boolean value	
	// NOTE: This method is not completed yet ... you can be ignore this method 
	public function has($coloumn_name,$condition_in_string){

		$sql="SELECT ".$coloumn_name." FROM ".$this->name." ".$condition_in_string;
		if($result_table=$this->database->query($sql)){
			if($result_table->num_rows>0) return true;
		}
		else{
			echo "ERROR in SQL while using HAS method";
			exit();
			// return true;
		}
		return false;

	}
	//###################################	
}


?>

	 
	