<?php
	
	// Contains helper functions not related to any specific module.
	
	function debug(){
		
		global 	$user_auth,	 $db, $application_list;
		
		$attributes = $user_auth->getAttributes();
		echo '<h4>User Attributes</h4>';
		echo print_pretty($attributes);

		echo '<h4>User Session (SimpleSAMLphp_SESSION)</h4>';
		$saml_session =  (array) unserialize($_SESSION['SimpleSAMLphp_SESSION']);
		echo print_pretty($saml_session);
		
		echo '<h4>Application List</h4>';
		echo print_pretty($application_list);
		
	}
	
	function print_pretty($arr){
		$retStr = '<ul>';
		if (is_array($arr)){
			foreach ($arr as $key=>$val){
				if (is_array($val)){
					$retStr .= '<li>' . $key . ' => ' . print_pretty($val) . '</li>';
				}else{
					if(is_serialized($val)){
						$new_val =  (array) unserialize($val);
						$retStr .= '<li>' . $key . ' => ' . print_pretty($new_val) . '</li>';
					}else
						$retStr .= '<li>' . $key . ' => ' . $val . '</li>';
				}
			}
		}
		$retStr .= '</ul>';
		return $retStr;
	}

	function is_serialized( $data ) {
		// if it isn't a string, it isn't serialized
		if ( !is_string( $data ) )
			return false;
		$data = trim( $data );
		if ( 'N;' == $data )
			return true;
		if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
			return false;
		switch ( $badions[1] ) {
			case 'a' :
			case 'O' :
			case 's' :
				if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
					return true;
				break;
			case 'b' :
			case 'i' :
			case 'd' :
				if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
					return true;
				break;
		}
		return false;
	}
	
	class PDOTester extends PDO {
		public function __construct($dsn, $username = null, $password = null, $driver_options = array())
		{
			parent::__construct($dsn, $username, $password, $driver_options);
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDOStatementTester', array($this)));
		}
	}
	class PDOStatementTester extends PDOStatement {
		const NO_MAX_LENGTH = -1;
		
		protected $connection;
		protected $bound_params = array();
		
		protected function __construct(PDO $connection)
		{
			$this->connection = $connection;
		}
		
		public function bindParam($paramno, &$param, $type = PDO::PARAM_STR, $maxlen = null, $driverdata = null)
		{
			$this->bound_params[$paramno] = array(
				'value' => &$param,
				'type' => $type,
				'maxlen' => (is_null($maxlen)) ? self::NO_MAX_LENGTH : $maxlen,
				// ignore driver data
			);
			
			$result = parent::bindParam($paramno, $param, $type, $maxlen, $driverdata);
		}
		
		public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
		{
			$this->bound_params[$parameter] = array(
				'value' => $value,
				'type' => $data_type,
				'maxlen' => self::NO_MAX_LENGTH
			);
			parent::bindValue($parameter, $value, $data_type);
		}
		
		public function getSQL($values = array())
		{
			$sql = $this->queryString;
			
			if (sizeof($values) > 0) {
				foreach ($values as $key => $value) {
					$sql = str_replace($key, $this->connection->quote($value), $sql);
				}
			}
			
			if (sizeof($this->bound_params)) {
				foreach ($this->bound_params as $key => $param) {
					$value = $param['value'];
					if (!is_null($param['type'])) {
						$value = self::cast($value, $param['type']);
					}
					if ($param['maxlen'] && $param['maxlen'] != self::NO_MAX_LENGTH) {
						$value = self::truncate($value, $param['maxlen']);
					}
					if (!is_null($value)) {
						$sql = str_replace($key, $this->connection->quote($value), $sql);
					} else {
						$sql = str_replace($key, 'NULL', $sql);
					}
				}
			}
			return $sql;
		}
		
		static protected function cast($value, $type)
		{
			switch ($type) {
				case PDO::PARAM_BOOL:
					return (bool) $value;
					break;
				case PDO::PARAM_NULL:
					return null;
					break;
				case PDO::PARAM_INT:
					return (int) $value;
				case PDO::PARAM_STR:
				default:
					return $value;
			}
		}
		
		static protected function truncate($value, $length)
		{
			return substr($value, 0, $length);
		}
	}
?>