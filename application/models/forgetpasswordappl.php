<?php
Class ForgetPasswordAppl extends CI_Model	{
	
	function insertRecord($source) {
		
		if($this -> db -> insert('FORGET_PWD_APPL', $source)) {
			return true;
		} else {
			return false;
		}
		
		
	}
	
	function disableOldRequest($email) {
		$data = array(
			'VALID' => 'F'
		);
		
		$this -> db -> where('EMAIL', $email);
		if ($this -> db -> update('FORGET_PWD_APPL', $data)) {
			return true;
		} else {
			return false;			
		}
		
		
	}
	
	function disableToken($token) {
		$data = array(
				'VALID' => 'F'
		);
		
		$this -> db -> where('TOKEN', $token);
		if ($this -> db -> update('FORGET_PWD_APPL', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	function chkToken($token) {
		$this -> db -> select("VALID");
		$this -> db -> from('FORGET_PWD_APPL');
		$this -> db -> where('TOKEN', $token);
		$this -> db -> where('VALID', 'T');
		if ($this->db->count_all_results() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	function getRecordBytoken($token) {
		$this -> db -> select("EMAIL");
		$this -> db -> from('FORGET_PWD_APPL');
		$this -> db -> where('TOKEN', $token);
		
		$query = $this -> db -> get();
		if ($query->num_rows() > 0) {
			return json_encode($query->result());
		} else {
			return false;
		}
		
	}

}
?>