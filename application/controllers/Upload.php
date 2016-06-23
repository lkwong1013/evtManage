<?php
  
   class Upload extends CI_Controller {
	
      public function __construct() { 
         parent::__construct(); 

         $this->load->helper(array('form', 'url')); 
      }
		
      public function index() { 
         $this->load->view('newSlideView', array('error' => ' ' )); 
      } 
		
      public function slideSubmit() { 
      	
      	$baseObj = new MY_Controller();
      	$CIObj = new CI_Controller();
      	$baseObj->loadLanguage();
      	$baseObj->initPage();
      	
      	 $this->load->model('SlideModel','',TRUE);

         $config['upload_path']   = 'notes/slides/'; 
         $config['allowed_types'] = 'gif|jpg|png'; 
         $config['max_size']      = 100; 
         $config['max_width']     = 1024; 
         $config['max_height']    = 768;  
         $this->load->library('upload', $config);
         
        
         $success = true;
         if ( ! $CIObj->upload->do_upload('slideImage')) {         	
            $error = array('error' => $this->upload->display_errors());
			$success = false;

         }
			
         else { 
//             $data = array('upload_data' => $this->upload->data()); 
//             $this->load->view('Upload_success', $data); 
         	
         	
         } 
         

         
         if ($success) {
         	// Success
         	$msg1 = $baseObj->genBoxMessage("success", "New Slide Inserted", 5000);
         	$messageData = array($msg1);
         	$this->session->set_userdata('boxMsg', $messageData);
         } else {
         	$msg1 = $baseObj->genBoxMessage("danger", $error, 15000);
         	$messageData = array($msg1);
         	$this->session->set_userdata('boxMsg', $messageData);
         }
         
         $this->load->view('header.php', $baseObj->viewData);
         $this->load->view('newSlideView');
         $this->load->view('footer.php');
      } 
   } 
?>