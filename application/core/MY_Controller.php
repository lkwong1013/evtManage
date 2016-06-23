<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller{
	
	//Page info
	protected $data = array();
	protected $viws_dir = 'frontend';
	protected $template = "main";
	protected $skeleton = "skeleton";
	protected $header_view = "header";
	protected $footer_view = "footer";

	//Page contents
	protected $javascript = array();
	protected $css = array();
	protected $fonts = array();

	//Page Meta
	protected $title = FALSE;
	protected $description = FALSE;
	protected $keywords = FALSE;
	protected $author = FALSE;
	
    protected $controller_name = FALSE;
    protected $action_name = FALSE;
	
    //Current user info
    protected $currentUserInfo;
    protected $viewData;
    function __construct()
	{	

		parent::__construct();
        //set the current controller and action name
        $this->controller_name = $this->router->fetch_directory() . $this->router->fetch_class();
        $this->action_name = $this->router->fetch_method();		

        $this->title = $this->controller_name.'-'.$this->action_name;
		
		$this->load->helper(array('cms'));
		
		$this->initPage();
	}
	 
	
	protected function _render($view = '',$renderData="FULLPAGE") {
    
	
        switch ($renderData) {
        case "AJAX"     :
            $this->load->view($view,$this->data);
        break;
        case "JSON"     :
            echo json_encode($this->data);
        break;
        case "FULLPAGE" :
        default         : 
		//static
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		
		//meta
		$toTpl["title"] = $this->title;
		$toTpl["description"] = $this->description;
		$toTpl["keywords"] = $this->keywords;
		$toTpl["author"] = $this->author;
	
		
		$path = $this->router->fetch_directory() ? $this->router->fetch_directory():'';

        if(empty($view)) {
		    $view_path = $path .'pages/'. ltrim($this->controller_name,$this->router->fetch_directory()).'_'. $this->action_name . '.php'; //set the path off the view
	      
	        if (is_file(APPPATH.'views/'.$view_path)) {
				
	           $view = $view_path;
	        } 
        }

        $toBody["content_body"] = $this->load->view($view,$this->data,true);        
			
		$toBody["header"] = $this->load->view("template/".$this->header_view,$toTpl,true);
		$toBody["footer"] = $this->load->view("template/".$this->footer_view,'',true);

		$toTpl["body"] = $this->load->view("template/".$this->template,$toBody,true);
		
		//render view
		$this->load->view("template/".$this->skeleton,$toTpl);
		 break;
    }
	
	}
	
	// Called by every controller
	public function initPage() {
		$this->loadLanguage();
		//$this->isLoggedIn();
		$this->overHeadLoginForm();
		$this->getCurrentUserInfo();
		$this->getBoxMessage();
		$this->viewData['currentLang'] = $this->lang->lang(); // Get current language type
		$this->viewData['test'] = "From parent"; 
		$this->viewData['siteDomain'] = "samaxxw.com";
		$this->viewData['defaultUrl'] = "http://samaxxw.com/evtManage/";
		$this->load->library('email');
		$this->load->library('pagination');
		
		// Get user role if logged in
		if ($this->isLoggedIn()) {
			$userData = $this->session->userdata('logged_in');
			$this->viewData['gblUserRole'] = $userData['userRole'];
		}

		// Generate token with SHA256 for each request
// 		$getPageToken =  $this->session->userdata('pageToken');
// 		$this->viewData['currentToken'] = $getPageToken['currentToken'];
		//$this->viewData['newToken'] = $getPageToken['newToken'];
	}
	
	public function loadLanguage() {
		$this->load->helper(array('url','html'));
		$this->load->helper('language');
		$this->load->helper('date');
		$this->lang->load('contact');	// Can be remove later
		$this->lang->load('resources');
		
	}
	
	public function isLoggedIn() {
		
		$logPrefix = "isLoggedIn(): ";
		if ($this->session->userdata('logged_in')) {
			//$this->logging($logPrefix."Logged In");
// 			$this->viewData['showLoginForm'] = "display:none";
//  			$this->viewData['showLoginInfo'] = "display:block";
			return true;
		} else {
			//$this->logging($logPrefix."Not Log In");
			return false;
// 			$this->viewData['showLoginForm'] = "display:block";
//  			$this->viewData['showLoginInfo'] = "display:none";
		}
	}
	
	public function overHeadLoginForm() {
		if ($this->isLoggedIn()) {
			$this->viewData['showLoginForm'] = "display:none";
			$this->viewData['showLoginInfo'] = "display:block";
			//return true;
		} else {
			//return false;
			$this->viewData['showLoginForm'] = "display:block";
			$this->viewData['showLoginInfo'] = "display:none";
		}		
	}
	
	public function getCurrentUserInfo() {
		if ($this->session->userdata('logged_in')) {
			$userData = $this->session->userdata('logged_in');
			$this->viewData['currentUserName'] = $userData['userName'];
			$this->viewData['currentUserRole'] = $userData['userRole'];       								
		} else {
			return false;
		}
	}
	
	public function setCurrentUserInfo($userName, $userRole) {
		$this->currentUserInfo['userName'] = $userName;
		$this->currentUserInfo['userRole'] = $userRole;
	}
	
	public function getBoxMessage() {
		
		if ($this->session->userdata('boxMsg')) {
			$messageData = $this->session->userdata('boxMsg');
			if (count($messageData) > 0) {
				$this->viewData['messageData'] = $messageData;
				
				unset($messageData); // Make sure the message used once only
				$this->session->set_userdata('boxMsg',null);  // Reset msgbox data
			}
		}
	}
	
	public function genPageToken() {
		
		$getPageToken = $this->session->userdata('pageToken');
		if ($getPageToken['newToken'] == "") {
			$getPageToken['newToken'] = hash("sha256",mt_rand(100000,999999));
			error_log(now()." genPageToken():New token missing => Generated New Token: ".$getPageToken['newToken']."\n", 3, "/var/www/html/evtManage/error_log.log");				
		}
		
		$pageToken = array(
				//'currentToken' => $getPageToken['newToken'],
				'newToken' => hash("sha256",mt_rand(100000,999999)),
				'currentToken' => $getPageToken['newToken']
				
		);			
		$this->session->set_userdata('pageToken', $pageToken);
		$tokenData = $this->session->userdata('pageToken');
		error_log(now()." genPageToken(): Current Token Token: ".$tokenData['currentToken']."\n", 3, "/var/www/html/evtManage/error_log.log");

		//$pageToken = hash("sha256",mt_rand(100000,999999));				
		//return $pageToken;
	}
	
	public function logging($content) {
		error_log(date("Y-m-d H:i:s")." ".$content."\n", 3, "/var/www/html/evtManage/error_log.log");
	}
	
	public function genBoxMessage($type, $content, $duration) {
		$this->logging('genBoxMessage(): '.$type.' '.$content.' '.$duration);
		if (isset($type) && isset($content) && isset($duration)) {
			$msg = array (
					'type' => $type,
					'content' => $content,
					'remainTime' => $duration
			);
			return $msg;
		}		
	}
	
	public function loginForm() {

		// Render the page
		$this->load->view('header.php', $this->viewData);
		$this->load->view('loginForm');
		$this->load->view('footer.php', $this->viewData);
	}
	
	
	public function errorRequest() {
		// Render the page
		$this->load->view('header.php', $this->viewData);
		$this->load->view('errorPage.php');
		$this->load->view('footer.php', $this->viewData);
	}
	
	
	// $subject : Email Subject (Regular String)
	// $reciver : Reciver email address (Array)
	// $message : Email content (HTML format)
	// Return Boolean
	public function emailService($inSubject, $inReciver, $inMessage) {
		$logPrefix = "emailService(): ";
		$this->logging($logPrefix."Email Service Called.");
		$this->logging($logPrefix."\$inSubject: ".$inSubject);
		$this->logging($logPrefix."\$inReciver: ".json_encode($inReciver));
		$this->logging($logPrefix."\$inMessage: ".$inMessage);
		/*
		 * Sender Email account setting
		 */ 		
		$sender_email = "samaxxw@gmail.com";
		$user_password = "Max62001";
		
		//$receiver_email = "lkwong1013@gmail.com";
		$receiver_email = implode(",", $inReciver);
		$username = "XXX Company";
		$subject = $inSubject;
		$message = $inMessage;
		
		// Configure email library
		$config['protocol'] = 'smtp';
		$config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = $sender_email;
		$config['smtp_pass'] = $user_password;
		
		
		// Load email library and passing configured values to email library
		$this->email->initialize($config);
		
		$this->email->set_newline("\r\n"); // *****Extremly important**** MUST BE PLACED!!!
		
		// Sender email address
		$this->email->from($sender_email, $username);
		// Receiver email address
		$this->email->to($receiver_email);
		// Subject of email
		$this->email->subject($subject);
		
		$this->email->set_mailtype("html");
		// Message in email
		$this->email->message($message);
		
		if ($this->email->send()) {
			$this->logging($logPrefix."Email message sent.");
			return true;		
		} else {
			$this->logging($logPrefix.$this->email->print_debugger());
			return false;
		}
		
	}

}
