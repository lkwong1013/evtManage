<?php
Class UserAccount extends CI_Model	{

	function loginValidate($username, $password) {
		// Assume null check has been done by front end\

		$thisObject = new Login_model();
		$encryptedPwd = $thisObject->SHA256($password);

		$this -> db -> select('USER_NAME, PASSWORD');
		$this -> db -> from('USER_ACCOUNT');
		$this -> db -> where('USER_NAME', $username);
		$this -> db -> where('PASSWORD', $encryptedPwd);
		$this -> db -> limit(1);

		$query = $this -> db -> get();
				

		if ($query->num_rows() > 0) {
			//return json_encode($query->result());
			return true;
		} else {
			return false;
		}
	}

	function SHA256($pwd) {
		return hash("sha256",$pwd);
	}
	
	function getUserCount($searchCriteria=NULL) {
		$this -> db -> select('ID');		
		if (!empty($searchCriteria)) {
			if (!empty($searchCriteria['userName'])) {
				$this -> db -> where('USER_NAME', $searchCriteria['userName']);
			}
			if (!empty($searchCriteria['userRole'])) {
				$this -> db -> where('ROLE', $searchCriteria['userRole']);
			}
		}
		$query = $this -> db -> get('USER_ACCOUNT');
		return $query -> num_rows();
	}

	function getUserInfo($userName=NULL) {
		$this -> db -> select('USER_NAME, FIRST_NAME, LAST_NAME ,ROLE');
		$this -> db -> from('USER_ACCOUNT');
		if (!empty($userName)) {
			$this -> db -> where('USER_NAME', $userName);
		} else {
			return false;
		}
		
		$query = $this -> db -> get();
		if ($query->num_rows() > 0) {
			return json_encode($query->result());
		} else {
			return false;
		}
	}
	
	function getUserList($limit, $start, $searchCriteria=NULL) {
	
		$this -> db -> select('ID, USER_NAME, FIRST_NAME, LAST_NAME ,ROLE, EMAIL');
		
		if (!empty($searchCriteria)) {
			if (!empty($searchCriteria['userName'])) {
				$this -> db -> where('USER_NAME', $searchCriteria['userName']);
			}
			if (!empty($searchCriteria['userRole'])) {
				$this -> db -> where('ROLE', $searchCriteria['userRole']);
			}
		}
		
		$query = $this -> db -> get('USER_ACCOUNT', $limit,$start);
		if ($query -> num_rows() > 0) {
			return $query -> result();
		} else {
			return false;
		}
	
	}
	
	function chkUserNameExist($userName) {
		$this -> db -> select('USER_NAME');
		$this -> db -> from('USER_ACCOUNT');
		$this -> db -> where('USER_NAME', $userName);
		
		if ($this->db->count_all_results() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function chkEmailExist($email) {
		$this -> db -> select('EMAIL');
		$this -> db -> from('USER_ACCOUNT');
		$this -> db -> where('EMAIL', $email);
	
		if ($this->db->count_all_results() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	function insertUser($source) {
	
	
		if ($this -> db -> insert('USER_ACCOUNT', $source)) {
			return true;
		} else {
			return false;
		}
	
	}
	
	function updatePwd($source) {
		
		$data = array(
				'PASSWORD' => $source['pwd'],
				'MODIFY_DATE' => date('Y-m-d H:i:s')
		);
		
		$this -> db -> where('EMAIL', $source['email']);
		if ($this -> db -> update('USER_ACCOUNT', $data)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function getUserRole() {
		$this -> db -> select('CODE, CODE_VALUE');
		$this -> db -> from('SYSTEM_DECODE');
		$this -> db -> where('CODE_TYPE', "USERROLE");
		$query = $this -> db -> get();
		if ($query -> num_rows() > 0) {
			return $query -> result();
		} else {
			return false;
		}
	}

}
?>