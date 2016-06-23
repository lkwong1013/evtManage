<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends MY_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model','',TRUE);
		$this->load->model('userAccount','',TRUE);
		$this->load->helper('email');
		//$this->load->library('session');
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();
		
	}

	public function index()
	{	
		
		if (!$this->session->userdata('registerFormData')) {
			// Password will not set into session
			$registerFormData = array(
					'firstName' => '',
					'lastName' => '',
					'userName' => '',
					'email' => ''					
			);
			$this->session->set_userdata('registerFormData', $registerFormData);
			$data['registerFormData'] = $registerFormData;
		} else {
			$data['registerFormData'] = $this->session->userdata('registerFormData');				
		}

		$this->load->view('header.php', $this->viewData);
		$this->load->view('registerView.php', $data);
		$this->load->view('footer.php');
	}
	
	public function registerSubmit() {
		$baseObj = new MY_Controller();
		$logPrefix = "registerSubmit(): ";
		$data = $this->input->post();		
		
		// Set input value to session
		$this->session->set_userdata('registerFormData', $data);
		// Do validation check
		if ($this->registerValidate($data)) {
			// Passed validation
			
			// Redirect to welcome page(index for testing), if insert success
			// Insert user to DB
			$encryptPwd = hash('SHA256',$data['pwd']);
			$insertData = array(
				'USER_NAME' => $data['userName'],
				'FIRST_NAME' => $data['firstName'],
				'LAST_NAME' => $data['lastName'],
				'PASSWORD' => $encryptPwd,
				'ROLE' => 'N',
				'EMAIL' => $data['email'],
				'MODIFY_DATE' => date('Y-m-d H:i:s'),
				'MODIFY_BY' => 'SYSTEM'
			
			);
			if ($this->userAccount->insertUser($insertData)) {
				// Clear session
				$this->session->set_userdata('registerFormData',null);  // Reset calFormData
				$msg1 = $baseObj->genBoxMessage("success", "Register Successfully", 5000);
				$messageData = array(
						$msg1
				);
				$this->session->set_userdata('boxMsg', $messageData);
				$baseObj->logging($logPrefix."Register Successfully : ".$data['userName']);
				echo "insert-success";
				
			} else {
				// Insert to DB failed
				$baseObj->logging($logPrefix."Failed to insert : Database connection problem");		
				$msg1 = $baseObj->genBoxMessage("danger", "Database connection problem", 5000);
				$messageData = array(
						$msg1
				);
				$this->session->set_userdata('boxMsg', $messageData);
				$baseObj->logging($logPrefix."Insert to DB failed!");
				echo "insert-failed";				
			}
			
		} else {
			$baseObj->logging($logPrefix."Validation check failed!");
			echo "insert-failed";
		}
		
	}
	
	public function registerValidate($data) {
		$logPrefix = "registerValidate(): ";
		$baseObj = new MY_Controller();
		$baseObj->logging($logPrefix."Data:".json_encode($data));
		$flag = true;
		$errMsg = "";
		if (empty($data['lastName'])) {
			$errMsg = $errMsg.lang('regMsgLastNameMiss')."<br>";
			$flag = false;
		}
		if (empty($data['firstName'])) {
			$errMsg = $errMsg.lang('regMsgFirstNameMiss')."<br>";
			$flag = false;
		}
		
		// UserName
		if (empty($data['userName'])) {
			$errMsg = $errMsg.lang('regMsgUserNameMiss')."<br>";
			$flag = false;
		}
		// Check username is duplicated
			
		if ($this->userAccount->chkUserNameExist($data['userName'])) {
			$errMsg = $errMsg.lang('regMsgUserNameDup')."<br>";
			$flag = false;
			
		}
		
		// Password
		if (empty($data['pwd'])) {
			$errMsg = $errMsg.lang('regMsgPwdMiss')."<br>";
			$flag = false;
		} else if (strcmp($data['pwd'], $data['confirmPwd']) != 0) {
			// Password and Confirmed Password not match
			$errMsg = $errMsg.lang('regMsgPwdMismatch')."<br>";
			$flag = false;
		} else if (strlen($data['pwd']) < 6) {
			$errMsg = $errMsg.lang('regMsgPwdInvalid')."<br>";
			$flag = false;
		}
		
		// Email
		if (empty($data['email'])) {
			$errMsg = $errMsg.lang('regMsgEmailMiss')."<br>";
			$flag = false;			
		} else if (!valid_email($data['email'])) {
			$errMsg = $errMsg.lang('regMsgEmailInvalid')."<br>";
			$flag = false;
		} 
		
		// Check email is duplicated			
		if ($this->userAccount->chkEmailExist($data['email'])) {
			$errMsg = $errMsg.lang('regMsgEmailDup')."<br>";
			$flag = false;				
		}
		
		if (!$flag) {
			$msg1 = $baseObj->genBoxMessage('reminder', $errMsg, 15000);
				
			$messageData = array(
					$msg1
			);
			$this->session->set_userdata('boxMsg', $messageData);
		}
		
		return $flag;
	}
}
?>