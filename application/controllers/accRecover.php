<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AccRecover extends MY_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->model('userAccount','',TRUE);
		$this->load->model('ForgetPasswordAppl','',TRUE);
		$this->load->helper('email');
		//$this->load->library('session');
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();

	}
	
	
	public function index() {
		$this->load->view('header.php', $this->viewData);
		$this->load->view('accRecoverApplFormView.php');
		$this->load->view('footer.php');
	}
	
	public function accRecoverProcess() {
		$logPrefix = "accRevcoverProcess(): ";
		$baseObj = new MY_Controller();
// 		if ($this->input->post()) {
			$recoveryData = $this->input->post();
			// Do validation
			if (!$this->inputValidation($recoveryData)) {
				// Generate warning message
				$baseObj->logging($logPrefix."Validation failed");
				$msg1 = $baseObj->genBoxMessage("danger",lang('accRecoverEmailInvalid'), 5000);
				$messageData = array($msg1);
				$this->session->set_userdata('boxMsg', $messageData);
				echo "failed";
				return;
			}
			// Check the email is exist in DB
			if ($this->userAccount->chkEmailExist($recoveryData['email'])) {
				// Disable all the account recovery request that user not done
				
				if (!$this->ForgetPasswordAppl->disableOldRequest($recoveryData['email'])) {
					$msg1 = $baseObj->genBoxMessage("danger", "Location #1:Database Error", 5000);
					$messageData = array($msg1);
					$this->session->set_userdata('boxMsg', $messageData);
					echo "failed";
					return ;
				} else {
					$baseObj->logging($logPrefix."Disabled all the old recovery request.");
				}
								
				$token = hash('sha256',date('Y-m-d H:i:s').rand());
				
				// 1. Insert application record
				
				$insertData = array(
					'EMAIL' => $recoveryData['email'],
					'TOKEN' => $token,
					'VALID' => 'T',
					'REQUEST_DATE' => date('Y-m-d H:i:s'),
					'IPADDR' => $this->input->ip_address()
				);

				if ($this->ForgetPasswordAppl->insertRecord($insertData)) {
					$baseObj->logging($logPrefix."Insert Success to FORGET_PWS_APPL: ".json_encode($insertData));
					
				} else {
					// Failed to insert
					$baseObj->logging($logPrefix."Insert Failed to FORGET_PWS_APPL: ".json_encode($insertData));
					
					$msg1 = $baseObj->genBoxMessage("danger", "Location #2:Database Error", 5000);
					$messageData = array($msg1);
					$this->session->set_userdata('boxMsg', $messageData);
					echo "failed";
					
					return;
				}
				
				// 2. Email the account recovery message to requested user
				$emailContent = "<p>Dear Sir/Madam,<br><br>"
						."	You have requested the account recovery service, "
						."Please click the link below to reset your password:<br>"
						.base_url().$this->viewData['currentLang']."/accRecover/doRecover/".$token
						."<br><br>"
						."Best Regards,<br>"
						."XXX Company";
						
					
				// emailService(String, String[], String)
				$baseObj->emailService("Account recovery request", array($recoveryData['email']), $emailContent);
				
				$baseObj->logging($logPrefix."Account Recovery requested: ".$recoveryData['email']);
				$msg1 = $baseObj->genBoxMessage("success", lang('accRecoverApplSuccess'), 5000);
				$messageData = array($msg1);
				$this->session->set_userdata('boxMsg', $messageData);
				echo "success";
				return;
			} else {
				// Email not exist
				$baseObj->logging($logPrefix."Email not exist in DB");
			
				$msg1 = $baseObj->genBoxMessage("danger", lang('accRecoverEmailNotFound'), 5000);
				$messageData = array($msg1);
				$this->session->set_userdata('boxMsg', $messageData);
				echo "failed";
				return;
				
			}
		
// 		} else {
// 			echo "failed";
			
// 		}
		
		echo "failed";
	}
	
	public function doRecover($token) {
		$logPrefix = "doRecover(): ";
		$baseObj = new MY_Controller();
		// Clear session Before do anything
		$this->session->set_userdata('accRecovery', null);
		
		if ($this->ForgetPasswordAppl->chkToken($token) && !empty($token) && isset($token)) {
			// Get token information
			$data['recoveryToken'] = $token;
			
			$tokenInfo = json_decode($this->ForgetPasswordAppl->getRecordBytoken($token),true);
			
			$email = $tokenInfo[0]['EMAIL'];
			$recoverySess = array(
				'token' => $token,
				'email' => $email
					
			);
			
			$this->session->set_userdata('accRecovery', $recoverySess);
			
			$this->load->view('header.php', $this->viewData);
			$this->load->view('accRecoverView.php', $data);
			$this->load->view('footer.php');
			
		} else {
			
			$baseObj->logging($logPrefix."Account recover token is invalid");		
			
			$this->load->view('header.php', $this->viewData);
			$this->load->view('frontView.php');
			$this->load->view('footer.php');
			// Redirect to index with error message
		}				
		
	}
	
	public function resetPwd() {
		$logPrefix = "resetPwd(): ";
		$baseObj = new MY_Controller();
		$recoveryData = $this->input->post();
		if ($this->recoverValidation($recoveryData)) {
			// Passed validation
			$encyptPwd = hash("sha256",$recoveryData['pwd']);
			// Update the new password to db
			$getToken = $this->session->userdata('accRecovery');
			$updateRecord = array(
				'pwd' => $encyptPwd,
				'email' => $getToken['email']				
			);
			$baseObj->logging($logPrefix.json_encode($updateRecord));
			if ($this->userAccount->updatePwd($updateRecord)) {
				
				// Update Succss				
				// Clear session
				$this->session->set_userdata('accRecovery', null);
				// Disable the token
				if ($this->ForgetPasswordAppl->disableToken($getToken['token'])) {
					// Final Step
					// Disable token		
					$msg1 = $baseObj->genBoxMessage('success', lang('accRecoverSuccess'), 15000);
					$baseObj->logging($logPrefix."Account has been recovered: ".$getToken['email']);
						
					$messageData = array($msg1);
					$this->session->set_userdata('boxMsg', $messageData);
					echo "success";
					return;
					
				} else {
					// Failed
					// Update token failed
					$msg1 = $baseObj->genBoxMessage('danger', lang('gblDBIssue'), 15000);
					$baseObj->logging($logPrefix."Update token failed".$getToken['email']);
					
					$messageData = array($msg1);
					$this->session->set_userdata('boxMsg', $messageData);
					echo "failed";
					return;
				}							
				
			} else {
				// Update password failed
				$msg1 = $baseObj->genBoxMessage('danger', lang('gblDBIssue'), 15000);
				$baseObj->logging($logPrefix."Update password failed".$getToken['email']);
					
				$messageData = array($msg1);
				$this->session->set_userdata('boxMsg', $messageData);
				echo "failed";
				return;
			}
			
			
			
		} else {
			// Failed in validation
			$baseObj->logging($logPrefix."Recover input validation failed");
				
			echo "failed";
		}
	}
	
	public function recoverValidation($data) {
		$flag = true;
		$logPrefix = "recoverValidation(): ";
		$baseObj = new MY_Controller();
		$errMsg = "";
		$baseObj->logging(json_encode($data));
		// Get token from session
		$getToken = $this->session->userdata('accRecovery');
		
		if (empty($data['pwd'])) {
			$errMsg = $errMsg.lang('accRecoverPwdMiss')."<br>";
			$flag = false;			
		} else if (strlen($data['pwd']) < 6) {
			$errMsg = $errMsg.lang('accRecoverPwdInvalid')."<br>";
			$flag = false;
		} else if (strcmp($data['pwd'], $data['confirmPwd']) != 0) {
			$errMsg = $errMsg.lang('accRecoverPwdMismatch')."<br>";				
			$flag = false;
		}
		if (empty($getToken['token'])) {
			$baseObj->logging($logPrefix."Lost token.");
			$errMsg = $errMsg.lang('gblTimeOut')."<br>";
			$flag = false;
		}
		
		if (!$flag) {
			$msg1 = $baseObj->genBoxMessage('reminder', $errMsg, 15000);
		
			$messageData = array($msg1);
			$this->session->set_userdata('boxMsg', $messageData);
		}
			
		return $flag;
	}
	
	
	public function inputValidation($data) {
		// Email validation
		$flag = true;
		$errMsg = "";
		if (empty($data['email'])) {
			//$errMsg = $errMsg.lang('accRecoverEmailMiss')."<br>";
			$flag = false;
		} else if (!valid_email($data['email'])) {
			//$errMsg = $errMsg.lang('accRecoverEmailInvalid')."<br>";
			$flag = false;
		}
		return $flag;
	}
	
	
	
}