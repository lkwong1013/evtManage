<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model','',TRUE);
		$this->load->model('schedule','',TRUE);
		
		
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();

	
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
			$scheduleData[$i]['url'] = base_url().$currentLang."/event/".$scheduleData[$i]['url'];
				
		}
	
		$scheduleData = json_encode($scheduleData, JSON_UNESCAPED_UNICODE);
	
		echo $scheduleData;
	}
	
	public function eventDetail($id=NULL) {
		$logPrefix = "eventDetail(): ";
		$baseObj = new MY_Controller();
		if (!empty($id)) {
			$data['eventData'] = json_decode($this->schedule->getEventDetail($id), true);
			$baseObj->logging($logPrefix.$this->schedule->getEventDetail($id));
			$this->load->view('header.php', $this->viewData);
			$this->load->view('eventDetailView.php', $data);
			$this->load->view('footer.php');
			
		} else {
			// $id is NULL
			// Error Request
			$this->load->view('errorPage.php');
		}
		
		
	}
	
	
	public function eventList() {
		$baseObj = new MY_Controller();
		$logPrefix = "eventList(): ";
		
		// Check permission 
		// Commented.It is a public function
		//if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
				
			//pagination settings
			$config['base_url'] = site_url('event/eventList');
			$config['total_rows'] = $this->schedule->getEventCount();
			$config['per_page'] = "10";
			$config["uri_segment"] = 4;
			$choice = $config["total_rows"] / $config["per_page"]; // Changed to integer type
			$config["num_links"] = floor($choice);
				
				
			//config for bootstrap pagination class integration
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['first_link'] = false;
			$config['last_link'] = false;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo';
			$config['prev_tag_open'] = '<li class="prev">';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&raquo';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
				
			$this->pagination->initialize($config);
			$data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data['maxRow'] = $config['total_rows'];
			//call the model function to get the notes data
			$data['eventList'] = json_decode($this->schedule->getEventList($config["per_page"], $data['page']), true);
				
			$data['pagination'] = $this->pagination->create_links();
				
			// Get Box message data from session
			$messgaeData = $this->session->userdata('boxMsg');
			$this->session->set_userdata('boxMsg',null);  // Reset msgbox data
				
			//load the note Test view
			$this->load->view('header.php', $this->viewData);
			$this->load->view('eventListView.php',$data);
			$this->load->view('footer.php', $this->viewData);
				
		//} else {
		//	$baseObj->logging($logPrefix."Access Denied - Error request");
		//	$baseObj->loginForm();
		//}
	}
	
}