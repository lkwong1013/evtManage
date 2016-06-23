<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model','',TRUE);
		$this->load->model('userAccount','',TRUE);
		$this->load->model('schedule','',TRUE);
		$this->load->model('notes','',TRUE);
		$this->load->model('SlideModel','',TRUE);
		
		//$this->load->library('session');
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();
		//$baseObject->isLoggedIn();
		//$baseObject->getCurrentUserInfo();
		
	}
	
	public function init() {
		// Load language setting
		$baseObject = new CI_Controller();
		$baseObject->loadLanguage();
		
	}
	

	
	public function index()
	{	

		$this->load->view('header.php', $this->viewData);
		$this->load->view('home.php');
		$this->load->view('footer.php');
	}

	public function adminTest() {
	
		// Render view
		$this->load->view('header.php', $this->viewData);
        $this->load->view('adminPanelTest.php');
        $this->load->view('footer.php');
	}
	
	public function langTest()
	{

		$data['currentLang'] = $this->lang->lang(); // Get current language type
		$data['uriString'] = uri_string();
		$data['tcUrl'] = str_replace('en', 'tc', uri_string()); // replace en to tc
		$data['enUrl'] = str_replace('tc', 'en', uri_string()); // replace tc to en
		
		$this->load->view('header.php', $this->viewData);
		$this->load->view('pages/contact', $data);
		$this->load->view('footer.php');
		
	}
	
	public function calTest() {
		$this->load->view('header.php', $this->viewData);
		$this->load->view('calTest.php', $this->viewData);
		$this->load->view('footer.php');
	}
	
	public function calFormTest() {
		$baseObj = new MY_Controller();
		$baseObj->logging('calFormTest()');
		if ($baseObj->isLoggedIn()) {
			if (!$this->session->userdata('calFormData')) {
				// Initialize the data structure for form
				$calFormData = array(
						'eventTitle' => '',
						'eventTitleTc' => '',
						'eventStart' => '',
						'eventEnd' => '',
						'fullDayEvent' => ''
				);
				$this->session->set_userdata('calFormData', $calFormData);
				$data['calFormData'] = $calFormData;
			} else {
				$data['calFormData'] = $this->session->userdata('calFormData');
			}
			 
	
			
			$this->load->view('header.php', $this->viewData);
			$this->load->view('calFormTest.php', $data);
			$this->load->view('footer.php');	
		} else {
			$baseObj->loginForm();
		}
	}
	
	public function calSubmitTest() {
		
		$baseObject = new MY_Controller();		
		$logPrefix = "calSubmitTest(): ";
		$baseObject->logging($logPrefix);
		
		if ($this->input->post()) {
			$calFormData = $this->input->post();
			// Update calFormData session
			$this->session->set_userdata('calFormData', $calFormData);
			// Do validation check
		
			if ($this->calSubmitValidate($calFormData)) {
				// Passed validation
			
				// Perform insert to DB
				
				// Get last eventID from DB
				
				$lastEvtId = json_decode($this->schedule->getLastEventID(), true); // JSON String Decode returned from DB
				
				if (isset($calFormData['fullDayEvent'])) {
					$fullDayEvent = "true";
				} else {
					$fullDayEvent = "false";
				}
				$newEvtId = $lastEvtId[0]['EVENT_ID'] + 1;
				$insertData = array(
					'EVENT_ID' => $newEvtId,
					'EVENT_TITLE' => $calFormData['eventTitle'],
					'EVENT_TITLE_CHT' => $calFormData['eventTitleTc'],
					'START' => $calFormData['eventStart'],
					'END' => $calFormData['eventEnd'],
					'URL' => "eventDetail/".$newEvtId,
					'ALL_DAY' => $fullDayEvent,						
				);
				
				$baseObject->logging($logPrefix."Insert Schedule Data: ".json_encode($insertData));
				if ($this->schedule->insertSchedule($insertData)) {
					// Insert Success
					// Clear session if inserted successfully
					$this->session->set_userdata('calFormData',null);  // Reset calFormData

					$msg1 = $baseObject->genBoxMessage("success", "New event inserted", 5000);
					$baseObject->logging($logPrefix.":".$msg1['content']);
					$messageData = array(
							$msg1
					);
					$this->session->set_userdata('boxMsg', $messageData);
					$baseObject->logging($logPrefix."Insert Schedule SUCCESS");
					echo "insert-success";
					
				} else {
					// Insert failed
					$msg1 = $baseObject->genBoxMessage("danger", "Insert Failed - Please check error log", 5000);
					$baseObject->logging($logPrefix.":".$msg1['content']);
					$messageData = array(
							$msg1
					);
					$this->session->set_userdata('boxMsg', $messageData);
					$baseObject->logging($logPrefix."Insert Schedule FAILED");
					echo "insert-failed";
					
				}
				
				
			} else {
				// Error message generated in calSubmitValidate()
				echo "insert-failed";
			}
			$baseObject->logging($logPrefix.print_r($calFormData));
		} else {
			
			echo "source is empty";
		}
		
		
// 		Sample input
// 		eventTitle] => Test Event
// 		[eventTitleTc] => Test Event CHT
// 		[eventStart] => 05/24/2016 12:00 AM
// 		[eventEnd] => 05/26/2016 12:00 AM
// 		[fullDayEvent] => on
		
		print_r($this->input->post());
	}
	
	public function calSubmitValidate($data) {
		$flag = true;
		$msg = "";
		if (empty($data['eventTitle'])) {
			$msg = $msg."Event Title is missing<br>";
			$flag = false;
		}
		if (empty($data['eventTitleTc'])) {
			$msg = $msg."Event Title TC is missing<br>";
			$flag = false;
		}
		if (empty($data['eventStart'])) {
			$msg = $msg."Start date is missing<br>";
			$flag = false;
		}
		if (empty($data['eventEnd'])) {
			$msg = $msg."End date is missing<br>";
			$flag = false;
		}
		
		// Date time validation check
		if (!empty($data['eventStart']) && !empty($data['eventEnd'])) {
			if (strtotime($data['eventStart']) > strtotime($data['eventEnd'])) {
				$msg = $msg."Invalid date & time setting<br>";
				$flag = false;
			}
		}
		
		
		if (!$flag) {
			// Set message data
			$msg1 = array (
					'type' => 'reminder',
					'content' => $msg,
					'remainTime' => 15000
			);
			
			$messageData = array(
					$msg1
			);
			$this->session->set_userdata('boxMsg', $messageData);
		}
		return $flag;
	}
	
	public function slideTest() {
		$this->load->view('header.php', $this->viewData);
		$this->load->view('slideTest.php', $this->viewData);
		$this->load->view('footer.php');
		
	}
	
	public function login() {
			$logPrefix = "login(): ";
			$userName = $this->input->post('userName');
	        $password = $this->input->post('password');
			$loginValid = $this->userAccount->loginValidate($userName, $password); // Return JSON String
			$testObj = new MY_Controller();
				

       		if ($loginValid) {
	       		// Get user account info
	       		$userAccountData = $this->userAccount->getUserInfo($userName);
	       		if (!$userAccountData) {
	       			// No data receive
	       			echo "login-failed";
	       		 	return;
	       		} else if (sizeof($userAccountData) == 1) {
	       			$userAccountData = json_decode($userAccountData, true); // JSON String Decode returned from DB
	       			$getUserName = $userAccountData[0]['USER_NAME'];
	       			$getUserRole = $userAccountData[0]['ROLE'];
	       			
	       			// Add User data into session
	       			$sessionData = array(
	       					'userName' => $getUserName,
	       					'userRole' => $getUserRole
	       			);
	       			
	       			$this->session->set_userdata('logged_in', $sessionData);
	       			$testObj->setCurrentUserInfo($getUserName,$getUserRole);
	       			
	       			// Loign success message
	       			$messageData = array();
	       			$loginSuccess = array (
	       					'type' => 'success',
	       					'content' => lang('boxMsgLoginSuccess'),
	       					'remainTime' => 3000
	       			);
	       			
	       			array_push($messageData,$loginSuccess);       			      	       			       		
	       			$this->session->set_userdata('boxMsg', $messageData);	       			
	       			$testObj->logging($logPrefix."Login Success : ".$getUserName);
	       			echo base_url().$this->viewData['currentLang']."/welcome/testFront";
	       			return;
	       		}	
	       } else {
	       		$testObj->logging($logPrefix."Login Failed : ".$userName);	       	 
	       		echo "login-failed";
	       		return;
	       }
	}
	
	public function logout() {

		$this->session->unset_userdata('logged_in');
   		session_destroy();
		redirect('welcome/adminTest');
	}
	
	public function getSchedule() {
		$currentLang = $this->viewData['currentLang'];
		$scheduleData = json_decode($this->schedule->getSchedule($currentLang), true);
		
		
		for ($i=0 ; $i < count($scheduleData) ; $i++) {
			if ($scheduleData[$i]['allDay'] == "false") {
				$scheduleData[$i]['allDay'] = false;
			} else {
				$scheduleData[$i]['allDay'] = true;
			}
			// Reparse the url from DB
			$scheduleData[$i]['url'] = base_url().$currentLang."/".$scheduleData[$i]['url'];
			
		}
		
		$scheduleData = json_encode($scheduleData, JSON_UNESCAPED_UNICODE);
		
		echo $scheduleData;
	}
	
	public function emailTest2() {
		$baseObj = new MY_Controller();
		if ($baseObj->isLoggedIn()) {
			$reciver = array('lkwong1013@gmail.com','yiwaisam0612@gmail.com');		
			$baseObj->emailService("Testing Email Template 2", $reciver, "<h1>Testing2</h1>");
		} else {
			$baseObj->loginForm();
		}
	}
	
	public function emailTest() {
		// Sender email account setup
		//$sender_email = "samaxxw@yahoo.com";
// 		$sender_email = "samaxxw@gmail.com";
// 		$user_password = "Max62001";
// 		//$receiver_email = "lkwong1013@gmail.com";
// 		$receiver_email = "lkwong1013@gmail.com";
// 		$username = "Testing Account";
// 		$subject = "Testing Email";
// 		$message = "<b style='color:red'>Testing Content</b><br>Email generated by system";
		
// 		// Configure email library
// 		$config['protocol'] = 'smtp';
// 		$config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
// 		$config['smtp_host'] = 'ssl://smtp.gmail.com';
// 		$config['smtp_port'] = '465';
// 		$config['smtp_user'] = $sender_email;
// 		$config['smtp_pass'] = $user_password;
		
		
// 		// Load email library and passing configured values to email library
// 		$this->email->initialize($config);

// 		$this->email->set_newline("\r\n"); // *****Extremly important**** MUST BE PLACED!!!
		
// 		// Sender email address
// 		$this->email->from($sender_email, $username);
// 		// Receiver email address
// 		$this->email->to($receiver_email);
// 		// Subject of email
// 		$this->email->subject($subject);
		
// 		$this->email->set_mailtype("html");
// 		// Message in email
// 		$this->email->message($message);
		
// 		if ($this->email->send()) {
// 			$data['message_display'] = 'Email Successfully Send !';
// 		} else {
// 			$data['message_display'] =  '<p class="error_msg">Invalid Gmail Account or Password !</p>';
// 		}
// 		$data['emailDebug'] = $this->email->print_debugger();
// 		$this->load->view('header.php', $this->viewData);
// 		$this->load->view('mailTest.php', $data);
// 		$this->load->view('footer.php', $this->viewData);
	}
	
// 	public function noteEditorTest() {
// 		//error_log(now()." noteEditorTest()\n", 3, "/var/www/html/evtManage/error_log.log");
// 		$baseObject = new MY_Controller();
// 		$baseObject->logging("noteEditorTest()");
// 		if ($baseObject->isLoggedIn()) {
		
// // 			if ($getPageToken['currentToken'] == "") {
// // 				$baseObject->genPageToken(); // Generate new page token
// // 				$getPageToken = $this->session->userdata('pageToken');
// // 			}
	
// 			// Token check required in this page
// 			//error_log(now()." noteEditorTest() - currentToken: ".$data['currentToken']."\n", 3, "/var/www/html/evtManage/error_log.log");
// 			$this->load->view('header.php', $this->viewData);
// 			$this->load->view('noteEditorTest.php');
// 			$this->load->view('footer.php', $this->viewData);
// 		} else {
// 			$baseObject->loginForm();
// 		}
		
// 	}
	
// 	public function noteSubmitTest() {
		// Create Notes images table(NOTES_IMAGES)
		// ------COLUMN------
		// SEQ
		// NOTESID
		// IMAGESPATH
		// ----------------
		// 1. Check security token
		// 1. Upload the images
		// 2. Insert images information to (NOTES_IMAGES)
		
		// Notes update process
		// Check the img src is contained {#^data:image/\w+;base64,#i}
		// Skip it, if do not contain
// 		$baseObject = new MY_Controller();
		
// 		$content = $this->input->post('noteContent');
// 		$thread = $this->input->post('noteThread');
// 		//$token = $this->input->post('token'); // Security token
// 		$this->load->helper('path');
// 		$this->load->library('image_lib');
		
		// Get security token from session
		// Token Obselete 
// 		$getPageToken = $this->session->userdata('pageToken');
// 		error_log(now()." noteSubmitTest(): Current Token:".$getPageToken['currentToken']."\n", 3, "/var/www/html/evtManage/error_log.log");		
// 		error_log(now()." noteSubmitTest(): New Token:".$getPageToken['newToken']."\n", 3, "/var/www/html/evtManage/error_log.log");
// 		error_log(now()." noteSubmitTest(): Form Token:".$token."\n", 3, "/var/www/html/evtManage/error_log.log");
// 		if ($baseObject->isLoggedIn()) {
// //			error_log(now()." noteSubmitTest(): Token valid!\n", 3, "/var/www/html/evtManage/error_log.log");
// 		//$content = "Success";
// 		// parse HTML content
// 			if (!empty($content)) {
				
// 				$doc = new DOMDocument();
// 				$doc->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD can avoid <html><body> tag
// 				$imageTags = $doc->getElementsByTagName('img'); // Get all img tag
// 				$count = 0;
// 				foreach($imageTags as $tag) {	// Loop over all <img> tags 
					
	
// 					if (preg_match('#^data:image/\w+;base64,#i', $tag->getAttribute('src'))) {
// 						// This is new image
// 						$count++;
// 						// Perform upload process
// 						//echo $tag->getAttribute('src'); // get the src link from img tag
// 						$image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $tag->getAttribute('src')));
// 						//$image = base64_decode($tag->getAttribute('src'));
// 						$image_name = md5(uniqid(rand(), true)).time();//date("YmdHis");// image name generating with random number with 32 characters and timestamp
// 						$filename = $image_name . '.' . 'png';
// 						//rename file name with random number
// 						$path = set_realpath('notes/images/');
// 						//image uploading folder path
// 						file_put_contents($path . $filename, $image);
// 						// image is bind and upload to respective folder
// 						$tag->setAttribute('src', base_url()."notes/images/".$filename);
// 					} 
	
					
				 	
// 				}
				
				// Save XML
// 				$str = $doc->saveXML($doc->documentElement);
// 				//echo substr_count($content, 'src="data:image'); // Find how many image inside content
				
// 				// Data for insert 
// 				// Pack data into an array
// 				$data = array (
// 					'NOTES_THREAD' => $thread,
// 					'NOTES_CONTENT' => $str,
// 					'MODIFY_BY' => $this->viewData['currentUserName'],
// 					'LAST_MODIFY' => date("Y-m-d H:i:s")
// 				);
					
// 				$messageData = array();
// 				if ($this->notes->insertNote($data)) {
// 					// Success
					
// 					$msg = array (
// 							'type' => 'success',
// 							'content' => lang('boxMsgInsertNoteSuccess'),
// 							'remainTime' => 5000
// 					);				
// 					array_push($messageData,$msg);
// 					$this->session->set_userdata('boxMsg', $messageData);
// 					//redirect('welcome/noteEditorTest');
// 					// Should redirect by JS
// 					$baseObject->logging("Note instered successfully!");
// 					echo "insert-success";
// 					return;
// 				} else {
// 					// Failed
// 					$baseObject->logging("Note insert failed!");
// 					echo "insert-failed";
// 					return;
// 				}
				
// 				//echo $str; // return html structure
// 				//echo $count;
// 				//echo $content;
// 			} else {
// 				echo "source is empty";
// 			}
//		} else {
//			// No session stored
//			$baseObject->logging("Note insert failed! Reason: Not logged in");
//			echo "insert-failed";									
//		}
//	}
	
	public function testOnly() {
		print_r($_POST);
		
	}
	
	public function doUpload() {
		$config['upload_path']   = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = 100;
		$config['max_width']     = 1024;
		$config['max_height']    = 768;
		$this->load->library('upload', $config);
			
		if ( ! $this->upload->do_upload('file')) {
			$error = array('error' => $this->upload->display_errors());
			echo $error;
			return;
		} else {
			$data = array('upload_data' => $this->upload->data());
			echo "upload success";
			return;
		}
	}
	
	
	public function mainContainer() {
		
		
	}
	

	
// 	public function noteTest() {
// 		//pagination settings
// 		$config['base_url'] = site_url('welcome/noteTest');
// 		$config['total_rows'] = $this->notes->getNotesCount();
// 		$config['per_page'] = "10";
// 		$config["uri_segment"] = 4;
// 		$choice = $config["total_rows"] / $config["per_page"]; // Changed to integer type
// 		$config["num_links"] = floor($choice);
		
		
// 		//config for bootstrap pagination class integration
// 		$config['full_tag_open'] = '<ul class="pagination">';
// 		$config['full_tag_close'] = '</ul>';
// 		$config['first_link'] = false;
// 		$config['last_link'] = false;
// 		$config['first_tag_open'] = '<li>';
// 		$config['first_tag_close'] = '</li>';
// 		$config['prev_link'] = '&laquo';
// 		$config['prev_tag_open'] = '<li class="prev">';
// 		$config['prev_tag_close'] = '</li>';
// 		$config['next_link'] = '&raquo';
// 		$config['next_tag_open'] = '<li>';
// 		$config['next_tag_close'] = '</li>';
// 		$config['last_tag_open'] = '<li>';
// 		$config['last_tag_close'] = '</li>';
// 		$config['cur_tag_open'] = '<li class="active"><a href="#">';
// 		$config['cur_tag_close'] = '</a></li>';
// 		$config['num_tag_open'] = '<li>';
// 		$config['num_tag_close'] = '</li>';
		
// 		$this->pagination->initialize($config);
// 		$data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
// 		$data['maxRow'] = $config['total_rows'];
// 		//call the model function to get the notes data
// 		$data['notelist'] = $this->notes->getNotes($config["per_page"], $data['page']);
		
// 		$data['pagination'] = $this->pagination->create_links();
		
// 		// Get Box message data from session
// 		$messgaeData = $this->session->userdata('boxMsg');
// 		$this->session->set_userdata('boxMsg',null);  // Reset msgbox data
		
// 		//load the note Test view
// 		$this->load->view('header.php', $this->viewData);
// 		$this->load->view('noteTest',$data);
// 		$this->load->view('footer.php', $this->viewData);
// 	}
	
	
// 	public function delNote() {
		

		
// 		$msg1 = array (
// 				'type' => 'success',
// 				'content' => 'Test 123',
// 				'remainTime' => 5000
// 		);
// 		$msg2 = array (
// 				'type' => 'danger',
// 				'content' => 'Test 234',
// 				'remainTime' => 5000
// 		);

// 		$messageData = array(
// 				$msg1, $msg2
// 		);
// 		$this->session->set_userdata('boxMsg', $messageData);
// 	}
	
	public function testFront() {
		
		// Get slide content		
		$data['slideContent'] = $this->SlideModel->getSlideView();
		$this->load->view('header.php', $this->viewData);
		$this->load->view('frontView2', $data);
		$this->load->view('footer.php', $this->viewData);
		
	}
	
	public function signIn() {
		$baseObj = new MY_Controller();
		$baseObj->loginForm();
	}
}