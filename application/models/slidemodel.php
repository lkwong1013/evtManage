<?php
Class SlideModel extends CI_Model {
	
	
	function insertSlide($source) {	
	
		if($this -> db -> insert('SLIDE', $source)) {
			return true;
		} else {
			return false;
		}
	
	}
	
	function updateSlide($source, $seq) {
		
		$this -> db -> where('SEQ', $seq);
		if ($this -> db -> update('SLIDE', $source)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function deleteSlide($seq) {
		
		$this->db->where('SEQ', $seq);
		if ($this->db->delete('SLIDE')) {
			return true;
		} else {
			return false;
			
		}
		
	}
	
	function getSlideList() {
		$this -> db -> select('SEQ, SLIDE_NAME, ORDER, MODIFY_DATE, MODIFY_BY, VISIBLE');		
		$query = $this -> db -> get('SLIDE');
		
		if ($query -> num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
		
	}
	
	function getSlideContent($seq) {
		$this -> db -> select('SEQ , SLIDE_NAME, SLIDE_CONTENT, ORDER, VISIBLE');
		$this -> db -> where ('SEQ', $seq);
		$query = $this -> db -> get('SLIDE');
		
		if ($query -> num_rows() > 0) {
			return $query->result();	
		} else {
			return false;
		}
		
	}
	
	function getSlideView() {
		$this -> db -> select('SLIDE_CONTENT');		
		$this -> db -> where ('VISIBLE', 'T');		
		$this -> db -> order_by("ORDER", "DESC");
		$this -> db -> order_by("SEQ", "ASC");
		
		$query = $this -> db -> get('SLIDE');
		
		if ($query -> num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

}
