<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slide extends MY_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->model('login_model','',TRUE);
		$this->load->model('userAccount','',TRUE);
		$this->load->model('SlideModel','',TRUE);
		
        $this->load->helper(array('form', 'url')); 
		
		//$this->load->library('session');
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();

	}

	/*
	 * Admin function
	 */
	public function newSlide() {
		
		$baseObj = new MY_Controller();
		if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
			$this->load->view('header.php', $this->viewData);
			$this->load->view('newSlideView.php');
			$this->load->view('footer.php');
		} else {
			$baseObj->logging("noteEditor():Access Denied - No permission");
			$baseObj->loginForm();
		}
		
		
	}
	
	public function slidePreview() {
		$baseObj = new MY_Controller();
		$data['slideContent'] = $this->SlideModel->getSlideView();
		
		$this->load->view('header.php', $this->viewData);
		$this->load->view('slidePreviewView.php', $data);
		$this->load->view('footer.php', $this->viewData);
	}
	
	/*
	 * Admin function
	 */

	public function editSlide($seq=NULL) {
		$baseObj = new MY_Controller();
		$logPrefix = "editSlide(): ";
		if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
			
			$this->load->view('header.php', $this->viewData);
			
			if (!empty($seq)) {
				$data['slideContent'] = $this->SlideModel->getSlideContent($seq);
				//$baseObj->logging($logPrefix.$data['slideContent'][0]->SLIDE_CONTENT);
				$this->load->view('editSlideView.php',$data);
			} else {
				// Error Request
				$this->load->view('errorPage.php');
			}
			
			$this->load->view('footer.php', $this->viewData);
			
		} else {
			$baseObj->logging($logPrefix."Access Denied - No permission");
			$baseObj->loginForm();
		}
		
	}
	
	/*
	 * Admin function
	 */
	
	public function delSlide($seq=NULL) {
		$baseObj = new MY_Controller();
		$logPrefix = "delSlide(): ";
		if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
			$messageData = array();
			if (!empty($seq)) {
				// 1. Get slide image file name
				// 2. Delete record
				// 3. Delete slide image
				$data['slideContent'] = $this->SlideModel->getSlideContent($seq);
				$fileName = $data['slideContent'][0]->SLIDE_CONTENT;
				
				if ($this->SlideModel->deleteSlide($seq)) {
					// Delete success
					// Delete file 
					$filePath = "./notes/slides/".$fileName;
					$this->load->helper("file");
					if (unlink($filePath)) {
						$baseObj->logging($logPrefix."File deleted :".$filePath);
						// Update failed DB issue
						$baseObj->logging($logPrefix."Slide Deleted");
						$msg = $baseObj->genBoxMessage("success", "Slide deleted.", 5000);
						array_push($messageData, $msg);
						
					} else {
						$baseObj->logging($logPrefix."File delete failed (Please delete the file manually at :".$filePath.")");
						
						$baseObj->logging($logPrefix."Slide record Deleted (Image not deleted)");
						$msg = $baseObj->genBoxMessage("warning", "Slide deleted.", 15000);
						array_push($messageData, $msg);
					}
					
					$this->session->set_userdata('boxMsg', $messageData);
					
				} else {
					$baseObj->logging($logPrefix."Slide delete failed: DB issue");
					$msg3 = $baseObj->genBoxMessage("danger", lang('gblDBIssue'), 15000);
					array_push($messageData, $msg3);
					$this->session->set_userdata('boxMsg', $messageData);
					
				}
				redirect('slide/slideList/', 'refresh');
			} else {
				// Error request
				$baseObj->logging($logPrefix."Access Denied - Error request");
				$baseObj->errorRequest();
			}
			
		} else {
			$baseObj->logging($logPrefix."Access Denied - Error request");
			$baseObj->loginForm();
		}
		
		
	}
	/*
	 * Admin function
	 */
	public function slideList() {
		
		$baseObj = new MY_Controller();
		if ($baseObj->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
		
			$data['slidelist'] = $this->SlideModel->getSlideList();
			// Data reparse for display
			for ($i = 0; $i < count($data['slidelist']); ++$i) {
				
				//Visible reparse
				if ($data['slidelist'][$i]->VISIBLE == "T") {
					$data['slidelist'][$i]->VISIBLE = "Yes";
				} else {
					$data['slidelist'][$i]->VISIBLE = "No";
				}
				
				// Order reparse
				if ($data['slidelist'][$i]->ORDER == 0) {
					$data['slidelist'][$i]->ORDER = "*Default";
				}
			}
			
			$this->load->view('header.php', $this->viewData);
			$this->load->view('slideList.php', $data);
			$this->load->view('footer.php');
			
		} else {
			
			$baseObj->logging("noteEditor():Access Denied - No permission");
			$baseObj->loginForm();
		}
		
		
	}
	
	public function slideSubmitValidation($source) {
		
		if (empty($source['slideName'])) {
			$baseObj = new MY_Controller();
			$msg1 = $baseObj->genBoxMessage("danger", lang('slideNameMiss'), 15000);
			$messageData = array($msg1);
			$this->session->set_userdata('boxMsg', $messageData);
			return false;
		} else {
			return true;
		}
		
	}
	
	/*
	 * Admin function
	 */
	public function editSlideSubmit() {
		$baseObj = new MY_Controller();
		if (!$baseObj->isLoggedIn()) {
			
			$baseObj->logging("noteEditor():Access Denied - No permission");
			$baseObj->loginForm();
			return;
		} else if (strcmp('A', $this->viewData['gblUserRole']) != 0) {
			$baseObj->logging("noteEditor():Access Denied - No permission");
			$baseObj->loginForm();
			return;
			
		}
		
		$logPrefix = "editSlideSubmit(): ";
		$sourceInput = $this->input->post();
		$seq = $this->input->post('seq');
		$slideName = $this->input->post('slideName');		
		$visible = $this->input->post('visible');
		$content = $this->input->post('content');
		$order = $this->input->post('order');
		$slideImage = $this->input->post('slideImage');

		if (empty($seq)) {
			$baseObj = new MY_Controller();
			$this->load->view('header.php', $this->viewData);
			$this->load->view('errorPage.php');
			$this->load->view('footer.php');
			
			return;
			
		}
		
		$CIObj = new CI_Controller();		
		
		if (empty($visible)) {
			$visible = "F";
		} else {
			$visible = "T";
		}
		
		if (!$this->slideSubmitValidation($sourceInput)) {
			redirect('slide/editSlide/'.$seq, 'refresh');
			return;
		}
		
		// Form Validation check passed
		// Perfomr Upload image proccess
		
		$fileName = md5(uniqid(rand(), true)).time();
		
		$config['upload_path']   = 'notes/slides/';
		$config['file_name']	 = $fileName;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = 1000;
		$config['max_width']     = 1500;
		$config['max_height']    = 768;
		$this->load->library('upload', $config);
		
		$imageUpdate = false;
		$messageData = array();
		
		if ( ! $CIObj->upload->do_upload('slideImage')) {
			// Upload failed
			// OR no image update
			
			$errors = array($CIObj->upload->display_errors());
			$baseObj = new MY_Controller();
			$baseObj->logging(json_encode($errors[0]));
			if (strcmp("<p>You did not select a file to upload.</p>", $errors[0]) == 0) {
				$msg1 = $baseObj->genBoxMessage("info", "No image update.", 5000);
				array_push($messageData, $msg1);
			} else {
				$msg1 = $baseObj->genBoxMessage("reminder", $errors[0], 15000);
				array_push($messageData, $msg1);
			}	
			$updateData =  array (
				'SLIDE_NAME' => $slideName,
				'ORDER' => $order, // Default set to 0
				'MODIFY_DATE' => date('Y-m-d H:i:s'),
				'VISIBLE' => $visible,
				'MODIFY_BY' => $this->viewData['currentUserName']														
			);
			
		} else {
			
			// Upload success
			// Update record to DB
			$data = $CIObj->upload->data();
			$baseObj = new MY_Controller();
			$updateData = array (
					'SLIDE_NAME' => $slideName,
					'SLIDE_CONTENT' => $fileName.$data['file_ext'],
					'ORDER' => $order, // Default set to 0
					'MODIFY_DATE' => date('Y-m-d H:i:s'),
					'VISIBLE' => $visible,
					'MODIFY_BY' => $this->viewData['currentUserName']
			);
			
			$imageUpdate = true;					
			
		}
		
		if ($this->SlideModel->updateSlide($updateData, $seq)) {
			// Update success
			if ($imageUpdate) {
				
				$baseObj->logging($logPrefix."Image updated");
				// Delete the old slide image
				$filePath = "./notes/slides/".$content;
				$this->load->helper("file");
				if (unlink($filePath)) {
					$baseObj->logging($logPrefix."File deleted :".$filePath);
				} else {
					$baseObj->logging($logPrefix."File delete failed :".$filePath);
				}	
			}
			
			$baseObj->logging($logPrefix."Slide updated");
			$msg2 = $baseObj->genBoxMessage("success", "Slide updated", 15000);
			array_push($messageData, $msg2);
			
		} else {
			// Update failed DB issue
			$baseObj->logging($logPrefix."Slide update failed: DB issue");
			$msg3 = $baseObj->genBoxMessage("danger", lang('gblDBIssue'), 15000);
			array_push($messageData, $msg3);
		}
		//$messageData = array($msg1, $msg2);
		
		//$baseObj->logging("Visible: ".$visible );
		
		$this->session->set_userdata('boxMsg', $messageData);
		redirect('slide/editSlide/'.$seq, 'refresh');
	}
	
	public function slideSubmit() {
		// If MY_Controller run define before calling upload API from CI_Controller
		// upload API will be undefined
		
		
		$CIObj = new CI_Controller();
		
		$slideName = $this->input->post('slideName');	
		$visible = $this->input->post('visible');
		$order = $this->input->post('order');
		$inputSource = $this->input->post();

		if (empty($visible)) {
			$visible = "F";
		} else {
			$visible = "T";
		}
		//$formValid = ; 
		if (!$this->slideSubmitValidation($inputSource)) {
			redirect('slide/newSlide', 'refresh');
			return;
		}
		
		$fileName = md5(uniqid(rand(), true)).time();
		
		$config['upload_path']   = 'notes/slides/';
		$config['file_name']	 = $fileName;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = 1000;
		$config['max_width']     = 1500;
		$config['max_height']    = 768;
		$this->load->library('upload', $config);
		
		if ( ! $CIObj->upload->do_upload('slideImage')) {			
			// Upload failed
			
			$errors = array($CIObj->upload->display_errors());
			$baseObj = new MY_Controller();
			
// 			for($i=0 ; $i<count($errors); $i++) {
// 				$msg = $baseObj->genBoxMessage("danger", $errors->error, 15000);
// 				array_push($messageData, $msg);
// 			}
			
			$baseObj->logging($errors[0]);
			$msg1 = $baseObj->genBoxMessage("reminder", "<br>".$errors[0], 15000);
			$messageData = array($msg1);
			$this->session->set_userdata('boxMsg', $messageData);
			redirect('slide/newSlide', 'refresh');
		} else {
			
			// Upload success
			// Insert record to DB
			$data = $CIObj->upload->data();
			$baseObj = new MY_Controller();
			$insertData = array (
				'SLIDE_NAME' => $slideName,
				'SLIDE_CONTENT' => $fileName.$data['file_ext'],
				'ORDER' => $order, // Default set to 0
				'DATE_ADDED' => date('Y-m-d H:i:s'),
				'MODIFY_DATE' => date('Y-m-d H:i:s'),
				'VISIBLE' => $visible,
				'MODIFY_BY' => $this->viewData['currentUserName']														
			);
			
 			if ($this->SlideModel->insertSlide($insertData)) {
 				// Do image upload
 				
 				// 			$baseObj->logging($data['file_ext']);
 				$msg1 = $baseObj->genBoxMessage("success", "New Slide Inserted", 5000);
 				$messageData = array($msg1);
 				$this->session->set_userdata('boxMsg', $messageData);
 				redirect('slide/newSlide', 'refresh');
 			} else {
 				// Insert failed
 				// Cancel image upload
 				$filePath = "./notes/slides/".$fileName.$data['file_ext'];
 				$this->load->helper("file");
 				if (unlink($filePath)) {
 					$baseObj->logging("File deleted :".$filePath); 					
 				} else {
 					$baseObj->logging("File delete failed :".$filePath);
 				}
 				
 				
 				$msg1 = $baseObj->genBoxMessage("danger", lang('gblDBIssue'), 15000);
 				$messageData = array($msg1);
 				$this->session->set_userdata('boxMsg', $messageData);
 				redirect('slide/newSlide', 'refresh');
 			}
			
			
		}
	}

	
	
}