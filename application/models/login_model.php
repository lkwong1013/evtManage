<?php
Class Login_model extends CI_Model	{

	function loginInfo($username, $password) {
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
        	return json_encode($query->result());
        } else {
        	return false;
        }
	}

	function SHA256($pwd) {
		return hash("sha256",$pwd);		
	}
 
}
?>