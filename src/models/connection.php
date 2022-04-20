<?php 

class Database
{
	private $servername = "localhost";
	private $username = "root";
	private $password = "";
	private $database="booking_cabinet";
	private $conn;

	public function __construct()
	{

		try {
			  $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->database", $this->username, $this->password);
			//   echo 'connection is Done!';
			} catch(PDOException $e) 
			{
			  echo "Connection failed: " . $e->getMessage();
			}
	}

	public function insert($table,$tableCln,$tableVal)
	{
		$names="";
		$values="";
		$vrls="";
		for ($i=0; $i <count($tableCln) ; $i++)
		{ 
			if ($i>0) 
			{
				$vrls=",";
			}
			$names.=$vrls."`".$tableCln[$i]."`";
			$values.=$vrls."'".$tableVal[$i]."'";
		}
		$str="INSERT INTO `$table`(".$names.") VALUES (".$values.")";
		$query=$this->conn->prepare($str);
		if($query->execute()) {
			return true;
		}else {
			return false;
		}
	}

	public function update($table, $id, $date, $time, $service_type, $price)
	{
		$str="UPDATE `booking` SET `booking_date`=$date,`time`=$time,`service_type`=$service_type,`price`=$price WHERE id = $id";
		// $str="UPDATE `booking` SET `booking_date`='dasdasd',`time`=234234,`service_type`=23234234,`price`=234234 WHERE id = 2";
		$query=$this->conn->prepare($str);
		if($query->execute()) {
			return true;
		}else {
			return false;
		}
	}

	public function selectAll($table)
	{
		$query=$this->conn->prepare("SELECT * FROM `$table`");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function selectOne($table, $searchFor, $val)
	{
		$str = "SELECT * FROM `$table` WHERE " . $searchFor ."=?";
		$query=$this->conn->prepare($str);
		$query->execute(
			[$val]
		);
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function delete($table,$id)
	{
		$query=$this->conn->prepare("DELETE FROM `$table` WHERE id=$id");
		return $query->execute();
	}

	public function checkUserExist($id) {
		$str = "SELECT * FROM `users` WHERE id=?";
		$query=$this->conn->prepare($str);
		$query->execute(
			[$id]
		);
		return $query->fetch(PDO::FETCH_ASSOC);
	}

	public function checkBookedTime($date) {
		ini_set('display_errors','On');
		ini_set('error_reporting',E_ALL);
		$str = "SELECT * FROM `booking` WHERE booking_date=?";
		$query=$this->conn->prepare($str);
		$query->execute(
			[$date]
		);
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return json_encode($result);
	}

	public static function message($content, $status) {
	    return json_encode(array('message' => $content, 'error' => $status));
	}
}
