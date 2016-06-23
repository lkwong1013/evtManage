<?php
Class Notes extends CI_Model {
	
	
	function getNotes($limit, $start) {
		
		$this -> db -> select('SEQ, NOTES_THREAD, LAST_MODIFY');
		$this -> db -> where ('VISIBLE', 'T'); 
		$query = $this -> db -> get('NOTES', $limit,$start);
		
		if ($query -> num_rows() > 0) {
			return $query -> result();			
		} else {
			return false;
		}
				
	}
	
	function getNotesCount() {
		
		$this -> db -> select('SEQ');
		$this -> db -> from('NOTES');
		$this -> db -> where ('VISIBLE', 'T'); // Only count for available thread
		$query = $this -> db -> get();
		
		return $query -> num_rows();
		
	}
	
	function insertNote($source) {
		
		
		if($this -> db -> insert('NOTES', $source)) {
			return true;
		} else {
			return false;
		}
				
	}
	
	function updateNote($source, $seq) {	
		
		$this -> db -> where('SEQ', $seq);
		if ($this -> db -> update('NOTES', $source)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function getNoteContent($seq) {
		$this -> db -> select('SEQ ,NOTES_TYPE, NOTES_THREAD, VISIBLE, NOTES_CONTENT, LAST_MODIFY, MODIFY_BY');
		$this -> db -> where ('SEQ', $seq);
		$query = $this -> db -> get('NOTES');
		
		if ($query -> num_rows() > 0) {
			return $query->result();	
		} else {
			return false;
		}
		
	}
	
}