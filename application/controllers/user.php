<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model','',TRUE);
		$this->load->model('userAccount','',TRUE);
		
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();

	
	}
	
	public function init() {
		// Load language setting
		$baseObject = new CI_Controller();
		$baseObject->loadLanguage();
		
	}
	
	public function filterUserList() {
		$logPrefix = "filterUserList(): ";
		$baseObj = new MY_Controller();
		if ($this->input->post()) {
			$searchBoxData = $this->input->post();
			// Update userFilter session
			$baseObj->logging($logPrefix."Session data: ".json_encode($searchBoxData));
			$this->session->set_userdata('userFilter', $searchBoxData);
		}
	}
	
	public function clearFilterUserList() {
		$logPrefix = "clearFilterUserList(): ";
		$baseObj = new MY_Controller();	
		$this->session->set_userdata('userFilter',null);  // Clear userFilter session data
		$baseObj->logging($logPrefix."userFilter Session Cleared");
	}
	
	public function userList() {
		$baseObj = new MY_Controller();
		$logPrefix = "userList(): ";
		
		// Check permission
		if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {		
			// Handle user filter session			
			if ($this->session->userdata('userFilter')) {
				$searchCriteria = $this->session->userdata('userFilter');
			}
			//pagination settings
			$config['base_url'] = site_url('user/userList');
			if (!empty($searchCriteria)) {
				$config['total_rows'] = $this->userAccount->getUserCount($searchCriteria);
				$baseObj->logging($logPrefix."With criteria");
			} else {
				$config['total_rows'] = $this->userAccount->getUserCount();
				$baseObj->logging($logPrefix."Without criteria");
			}
			
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
			//call the model function to get the user accounts data
			
			
			if (empty($searchCriteria)) {
				// No session stored
				$data['userlist'] = $this->userAccount->getUserList($config["per_page"], $data['page']);
			} else {
				$searchCriteria = $this->session->userdata('userFilter');
				$data['userlist'] = $this->userAccount->getUserList($config["per_page"], $data['page'], $searchCriteria);
			}
			
			$data['pagination'] = $this->pagination->create_links();
			
			// Get Box message data from session
			$messgaeData = $this->session->userdata('boxMsg');
			$this->session->set_userdata('boxMsg',null);  // Reset msgbox data
			
			
			// Get User Role
			$data['userRoleList'] = $this->userAccount->getUserRole();
			$baseObj->logging($logPrefix.json_encode($data['userRoleList']));
			//load the note Test view
			$this->load->view('header.php', $this->viewData);
			$this->load->view('userListView.php',$data);
			$this->load->view('footer.php', $this->viewData);
			
		} else {
			$baseObj->logging($logPrefix."Access Denied - Error request");
			$baseObj->loginForm();
		}
	}
		
	
}