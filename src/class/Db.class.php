<?php
require('../inc/config-db.php');
/* *
	* DB - A simple database class 
	* @author		Author: mrp-synergies
	* @version      0.1
	*/

class Db
{
	private $Host;
	private $DBName;
	private $DBUser;
	private $DBPassword;
	private $DBPort;
	private $pdo;
	private $sQuery;
	private $bConnected = false;
	private $parameters;
	private $oldParameters;
	public $rowCount   = 0;
	public $columnCount   = 0;
	public $querycount = 0;
	public $succes;
	public $traduction;
	
	public function __construct()
	{
		global $serverIpAddr, $configDbName, $configUser, $configPass;
		if($_SERVER['HTTP_HOST'] == CONFIG_SRVIP){ 
			$this->Host = 'localhost';
			$this->DBName = CONFIG_DBNAME;
			$this->DBUser = CONFIG_USR;
			$this->DBPassword = CONFIG_PASS;
		}else{
			$this->Host = 'localhost';
			$this->DBName = CONFIG_DBNAME; 
			$this->DBUser = CONFIG_USR; 
			$this->DBPassword = CONFIG_PASS; 
		}
		$this->DBPort = 3306;
		$this->Connect();
		$this->parameters = array();
	}
	
	
	private function Connect()
	{
		try {
            $this->pdo = new PDO('mysql:host=localhost:'.$this->DBPort.';dbname='.$this->DBName,
                $this->DBUser,
                $this->DBPassword,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                )
            );

            //$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
			$this->bConnected = true;
			
		}
		catch (PDOException $e) {
			echo $this->ExceptionLog($e->getMessage());
			die();
		}
	}
	
	
	public function CloseConnection()
	{
		$this->pdo = null;
	}
	
	
	private function Init($query)
	{
		if (!$this->bConnected) {
			$this->Connect();
		}
		try {
			$this->sQuery     = $this->pdo->prepare($query);
			
			if (!empty($this->parameters)) {
				
				foreach ($this->parameters as $values) {
					$this->sQuery->bindValue($values[0],$values[2],$values[1]);
				}
			}
			
			$this->succes = $this->sQuery->execute();
			$this->querycount++;
			
		}
		catch (PDOException $e) {
			echo $this->ExceptionLog($query,$this->parameters);
			echo $this->ExceptionLog($e->getMessage(),$this->sQuery->debugDumpParams());
			die();
		}
		
		$this->oldParameters = $this->parameters;
		$this->parameters = array();
	}
	
	/**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
	public function bindInt($para, $value,$isNull=false)
	{
		$value=($isNull && empty($value))?Null:intval($value);
		$this->parameters[sizeof($this->parameters)] = array(':'. $para,PDO::PARAM_INT,$value);
	}

    /**
     *	@void
     *
     *	Add the parameter to the parameter array
     *	@param string $para
     *	@param string $value
     */
    public function bindTinyint($para, $value)
    {
        $value=(empty($value) || ($value==0))?0:$value;
        $this->parameters[sizeof($this->parameters)] = array(':'. $para,PDO::PARAM_INT,intval($value));
    }
	
	/**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
	public function bindTxt($para, $value,$isNull=false)
	{
        $value=($isNull && empty($value))?Null:$value;
	    $this->parameters[sizeof($this->parameters)] = array(":" . $para,PDO::PARAM_STR,($value));
	}
	
	/**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
	public function bindArray($para, $value)
	{	
		foreach ($value as $k => $id){
			$this->parameters[sizeof($this->parameters)] = array(":" . $para.$k,PDO::PARAM_STR,($id));
		}
	}
	
	/**
	*	@void 
	*
	*	Add the parameter to the parameter array
	*	@param string $para  
	*	@param string $value 
	*/	
	public function bindDate($para, $value)
	{
		$d = DateTime::createFromFormat('Y-m-d', $value);
		if(!$d || $d->format('Y-m-d') != $value){
			$value = null;
		};
		$this->parameters[sizeof($this->parameters)] = array(":" . $para,PDO::PARAM_STR,($value));
	}

	/**
	 *	@void
	 *
	 *	Add the parameter to the parameter array
	 *	@param string $para
	 *	@param string $value
	 */
	public function bindTime($para, $value)
	{
		$d = DateTime::createFromFormat('H:i', $value);
		if($d && $d->format('H:i') == $value) {
			$value = $d->format('H:i:s');
		}else{
			$value = null;
		};
		$this->parameters[sizeof($this->parameters)] = array(":" . $para,PDO::PARAM_STR,($value));
	}
	
	private function BuildParams($query, $params = array()){
		$params=$this->parameters;
		if (!empty($params)) {
			$array_parameter_found = false;
			foreach ($params as $parameter_key => $parameter) {
				if (is_array($parameter)){
					$array_parameter_found = true;
					$in = "";
					foreach ($parameter as $key => $value){
						$name_placeholder = $parameter_key."_".$key;
						// concatenates params as named placeholders
					    	$in .= ":".$name_placeholder.", ";
						// adds each single parameter to $params
						$params[$name_placeholder] = $value;
					}
					$in = rtrim($in, ", ");
					$query = preg_replace("/:".$parameter_key."/", $in, $query);
					// removes array form $params
					unset($params[$parameter_key]);
				}
			}
			// updates $this->params if $params and $query have changed
			if ($array_parameter_found) $this->parameters = $params;
		}
		return $query;
	}
	
	
	public function query($query, $fetchmode = PDO::FETCH_ASSOC)
	{
		$query        = trim($query);
		$rawStatement = explode(" ", $query);
		$this->Init($query);
		$statement = strtolower($rawStatement[0]);
		if ($statement === 'select' || $statement === 'show') {
			return $this->sQuery->fetchAll($fetchmode);
		} elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
			return $this->sQuery->rowCount();
		} else {
			return NULL;
		}
	}
	
	public function debug($query, $fetchmode = PDO::FETCH_ASSOC)
	{
		$query        = trim($query);
		$rawStatement = explode(" ", $query);

        $param = $this->parameters;
        if (!empty($param) && is_array($param)) {
            $sql = $query;
            foreach ($param as $parameter) {
                $sql = str_replace ($parameter[0], "'" . $parameter[2] . "'", $sql);
            }
            echo "<br/>***";
            echo "\r\n\nRaw SQL : " . $sql;
            echo "<br/>***<br/>";
        }

		$this->Init($query);
		$statement = strtolower($rawStatement[0]);
		
		var_dump($query);
        var_dump($this->oldParameters);

		if ($statement === 'select' || $statement === 'show') {
			return $this->sQuery->fetchAll($fetchmode);
		} elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
			return $this->sQuery->rowCount();
		} else {
			return NULL;
		}
	}
	
	
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
	
	
	public function column($query, $params = null)
	{
		$this->Init($query, $params);
		$resultColumn = $this->sQuery->fetchAll(PDO::FETCH_COLUMN);
		$this->rowCount = $this->sQuery->rowCount();
		$this->columnCount = $this->sQuery->columnCount();
		$this->sQuery->closeCursor();
		return $resultColumn;
	}
	public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC)
	{
		$this->Init($query);
		$resultRow = $this->sQuery->fetch($fetchmode);
		$this->rowCount = $this->sQuery->rowCount();
		$this->columnCount = $this->sQuery->columnCount();
		$this->sQuery->closeCursor();
		return $resultRow;
	}
	
	
	public function single($query)
	{
		$this->Init($query.' limit 0,1');
		return $this->sQuery->fetch(PDO::FETCH_ASSOC);
	}
	
	public function show($query)
	{
		$this->Init($query);
		return $this->sQuery->fetch(PDO::FETCH_ASSOC);
	}
	
	public function sql2date($date) 
	{
		$date = ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$','\\3.\\2.\\1', $date);
		return $date;
	}
	// Tranforme une date au format dd.mm.yyyy en une date au format SQL
	public function date2sql($date) 
	{	
		$date = ereg_replace('^([0-9]{1,2}).([0-9]{1,2}).([0-9]{2,4})$','\\3-\\2-\\1', $date);
		return $date;
	}
	
	
	private function ExceptionLog($message, $param = "")
	{
		$exception = 'Erreur de Base de données. <br />';
		$exception .= $this->DBName.'\r\n'.$message;
		$exception .= "<br /> Notre webmaster en a été informé.";
		
		if (!empty($param) && is_array($param)) {
			$sql = $message;
			foreach ($param as $parameter) {
				$sql = str_replace ($parameter[0], "'" . $parameter[2] . "'", $sql);
			}
			$exception .= "\r\nRaw SQL : " . $sql;
		}
		$to      = 'maxime@alunis.ch';
		$subject = 'mrp alunis-Gast';
		$headers = 'From: webmaster@mrpsynergies.ch' . "\r\n" .
			'Reply-To: webmaster@mrpsynergies.ch' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		$error=mail($to, $subject, $exception, $headers);
		//var_dump($error);

		//Prevent search engines to crawl
		header("HTTP/1.1 500 Internal Server Error");
		header("Status: 500 Internal Server Error");
		return $exception;
	}
}
?>
