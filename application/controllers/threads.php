<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Threads extends MY_Controller {


	public function __construct() {
		parent::__construct();
		$this->load->model('userAccount','',TRUE);
		$this->load->model('notes','',TRUE);
		$this->load->helper('email');
		//$this->load->library('session');
		// Load language setting
		$baseObject = new MY_Controller();
		$baseObject->loadLanguage();
		$baseObject->initPage();

	}
	
	
	public function noteEditor($threadId=NULL) {
		//error_log(now()." noteEditorTest()\n", 3, "/var/www/html/evtManage/error_log.log");
		$baseObject = new MY_Controller();
		$baseObject->logging("noteEditor()");
		//$baseObject->logging($this->viewData['gblUserRole']);
		if ($baseObject->isLoggedIn() && strcmp('A', $this->viewData['gblUserRole']) == 0) {
	
			// 			if ($getPageToken['currentToken'] == "") {
			// 				$baseObject->genPageToken(); // Generate new page token
			// 				$getPageToken = $this->session->userdata('pageToken');
			// 			}
	
			// Token check required in this page
			//error_log(now()." noteEditorTest() - currentToken: ".$data['currentToken']."\n", 3, "/var/www/html/evtManage/error_log.log");
			$this->load->view('header.php', $this->viewData);
			if (empty($threadId)) {
				// For New Record
				$this->load->view('newNoteEditor.php');
			} else {
				// For edit record
				// get note content from DB
				$data['noteContent'] = $this->notes->getNoteContent($threadId);
				$this->load->view('editNoteEditor.php', $data);
				
			}
			
			$this->load->view('footer.php', $this->viewData);
		} else {
			$baseObject->logging("noteEditor():Access Denied - No permission");
			$baseObject->loginForm();
		}
	
	}
	
	public function noteSubmit() {
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
		$baseObject = new MY_Controller();
	
		$logPrefix = "noteSubmit(): ";
		$content = $this->input->post('noteContent');
		$thread = $this->input->post('noteThread');
		$noteOption = $this->input->post('noteOption');
		if (empty($thread)) {
			// Failed
			$baseObject->logging($logPrefix."Thread is missing!");
			echo "thread-missing";
			return;
		}
		//$token = $this->input->post('token'); // Security token
		$this->load->helper('path');
		$this->load->library('image_lib');
	
		// Get security token from session
		// Token Obselete
		// 		$getPageToken = $this->session->userdata('pageToken');
		// 		error_log(now()." noteSubmitTest(): Current Token:".$getPageToken['currentToken']."\n", 3, "/var/www/html/evtManage/error_log.log");
		// 		error_log(now()." noteSubmitTest(): New Token:".$getPageToken['newToken']."\n", 3, "/var/www/html/evtManage/error_log.log");
		// 		error_log(now()." noteSubmitTest(): Form Token:".$token."\n", 3, "/var/www/html/evtManage/error_log.log");
		if ($baseObject->isLoggedIn()) {
			//			error_log(now()." noteSubmitTest(): Token valid!\n", 3, "/var/www/html/evtManage/error_log.log");
			//$content = "Success";
			// parse HTML content
			if (!empty($content)) {
	
				$doc = new DOMDocument('1.0', 'UTF-8');
				//$doc->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD can avoid <html><body> tag
				$doc->loadHTML('<?xml encoding="utf-8"> ' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
				$doc->encoding = 'utf-8';
				$imageTags = $doc->getElementsByTagName('img'); // Get all img tag				
				$count = 1;
				//$baseObject->logging($content);
				foreach($imageTags as $tag) {	// Loop over all <img> tags
						
					$baseObject->logging($logPrefix."Image ".$count);
					if (preg_match('#^data:image/\w+;base64,#i', $tag->getAttribute('src'))) {
						// This is new image
						$count++;
						// Perform upload process
						//echo $tag->getAttribute('src'); // get the src link from img tag
						$image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $tag->getAttribute('src')));
						//$image = base64_decode($tag->getAttribute('src'));
						$image_name = md5(uniqid(rand(), true)).time();//date("YmdHis");// image name generating with random number with 32 characters and timestamp
						$filename = $image_name . '.' . 'png';
						//rename file name with random number
						$path = set_realpath('notes/images/');
						//image uploading folder path
						file_put_contents($path . $filename, $image);
						$baseObject->logging($logPrefix."Image ".$count." uploaded");
						// image is bind and upload to respective folder
						$tag->setAttribute('src', base_url()."notes/images/".$filename);
						$tag->setAttribute("class", "img-responsive"); // Photo will be resized depend on the screen width
						$tag->setAttribute("actual-filename", $filename);
					}
	
					$count++;
	
				}
	
				// Save XML
				$str = $doc->saveXML();
				//$str = $doc->ownerDocument->saveXML($doc->ownerDocument->documentElement);
				//¡@Remove xml heading
				$str = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n", '', $str);
				$str = str_replace("<?xml encoding=\"utf-8\"?>\n", '', $str);
				$str = str_replace("<!--?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?-->\n", '', $str);
				$str = str_replace("<!--?xml encoding=\"utf-8\"?-->\n", '', $str);
				$baseObject->logging($str);
	
				//$str = $doc->saveXML($doc->documentElement, ENT_QUOTES,'utf-8');
				//echo substr_count($content, 'src="data:image'); // Find how many image inside content
				
				// Data for insert
				// Pack data into an array
				
					
				$messageData = array();
				if (strcmp($noteOption, "insert") == 0) {
					// perform insrt process
					// Initailize the data for insert
					$baseObject->logging($logPrefix."Perform insert note process");
					$data = array (
							'NOTES_THREAD' => $thread,
							'NOTES_CONTENT' => $str,
							'MODIFY_BY' => $this->viewData['currentUserName'],
							'LAST_MODIFY' => date("Y-m-d H:i:s")
					);
					if ($this->notes->insertNote($data)) {
						// Success
					
						$msg = array (
								'type' => 'success',
								'content' => lang('boxMsgInsertNoteSuccess'),
								'remainTime' => 5000
						);
						array_push($messageData,$msg);
						$this->session->set_userdata('boxMsg', $messageData);
						//redirect('welcome/noteEditorTest');
						// Should redirect by JS
						$baseObject->logging("Note instered successfully!");
						echo "insert-success";
						return;
					} else {
						// Failed
						$baseObject->logging("Note insert failed!");
						echo "insert-failed";
						return;
					}
				} else if (strcmp($noteOption, "update") == 0) {
					// perform update process
					// Initailize the data for update
					$baseObject->logging($logPrefix."Perform update note process");
					$seq = $this->input->post('noteSeq');
					$data = array (
							'NOTES_THREAD' => $thread,
							'NOTES_CONTENT' => $str,
							'MODIFY_BY' => $this->viewData['currentUserName'],
							'LAST_MODIFY' => date("Y-m-d H:i:s")
					);
					
					if ($this->notes->updateNote($data, $seq)) {
						// Success
							
						$msg = array (
								'type' => 'success',
								'content' => lang('boxMsgUpdateNoteSuccess'),
								'remainTime' => 5000
						);
						array_push($messageData,$msg);
						$this->session->set_userdata('boxMsg', $messageData);
						//redirect('welcome/noteEditorTest');
						// Should redirect by JS
						$baseObject->logging("Note updated successfully!");
						echo "insert-success";
						return;
					} else {
						// Failed
						$baseObject->logging("Note update failed!");
						echo "insert-failed";
						return;
					}
					
				}
				
				
	
				//echo $str; // return html structure
				//echo $count;
				//echo $content;
			} else {
				echo "source is empty";
			}
		} else {
			// No session stored
			$baseObject->logging("Note insert failed! Reason: Not logged in");
			echo "insert-failed";
		}
	}
	
	public function index() {
		//pagination settings
		$config['base_url'] = site_url('threads/index');
		$config['total_rows'] = $this->notes->getNotesCount();
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
		$data['notelist'] = $this->notes->getNotes($config["per_page"], $data['page']);
	
		$data['pagination'] = $this->pagination->create_links();
	
		// Get Box message data from session
		$messgaeData = $this->session->userdata('boxMsg');
		$this->session->set_userdata('boxMsg',null);  // Reset msgbox data
	
		//load the note Test view
		$this->load->view('header.php', $this->viewData);
		$this->load->view('note.php',$data);
		$this->load->view('footer.php', $this->viewData);
	}
	
	
	public function delNote() {
	
	
	
		$msg1 = array (
				'type' => 'success',
				'content' => 'Test 123',
				'remainTime' => 5000
		);
		$msg2 = array (
				'type' => 'danger',
				'content' => 'Test 234',
				'remainTime' => 5000
		);
	
		$messageData = array(
				$msg1, $msg2
		);
		$this->session->set_userdata('boxMsg', $messageData);
	}
	
	public function noteContent($threadId=NULL) {
		$logPrefix = "noteContent(): ";
		$baseObj = new MY_Controller();
		
		$this->load->view('header.php', $this->viewData);
		
		if (!empty($threadId)) {
			$data['noteContent'] = $this->notes->getNoteContent($threadId);
			$baseObj->logging($logPrefix.$data['noteContent'][0]->NOTES_CONTENT);
			$this->load->view('noteContentView.php',$data);
		} else {
			// Error Request			
			$this->load->view('errorPage.php');
		}
				
		$this->load->view('footer.php', $this->viewData);
		
	}

}