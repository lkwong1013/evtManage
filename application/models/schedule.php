<?php
Class schedule extends CI_Model {
	function getSchedule($currentLang) {
		if ($currentLang == "en") {
			$this -> db -> select('SEQ AS `id`, EVENT_TITLE AS `title`, START AS `start`, END AS `end`, URL AS `url`, ALL_DAY AS `allDay`');
		} else if ($currentLang == "tc") {
			$this -> db -> select('SEQ AS `id`, EVENT_TITLE_CHT AS `title`, START AS `start`, END AS `end`, URL AS `url`, ALL_DAY AS `allDay`');
		}
		$this ->db -> from('SCHEDULE');
		
		$query = $this ->db -> get();
		
		// Moved to controller 
		// Convert to allDay field to boolean for feeding the data into fullCalendar plugin
		// 		foreach ($scheduleData as $row) {
		// 			// Reparse all Day column
		// 			if ($row->allDay == "false") {
		// 				$row->allDay = false;
		// 			} else {
		// 				$row->allDay = true;
		// 			}
		// 			// Reparse the url from DB
		// 			$row->url = base_url().$currentLang."/".$row->url;
		// 		}
		
		if ($query -> num_rows() > 0) {
			return json_encode($query->result(), JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE: This is a soultion for display CHT
		} else {
			return false;
		}
		
	}
	
	function getLastEventID() {
		$this -> db -> select_max('EVENT_ID');
		$query = $this -> db -> get('SCHEDULE');
		
		if ($query -> num_rows() > 0) {
			return json_encode($query->result(), JSON_UNESCAPED_UNICODE); //JSON_UNESCAPED_UNICODE: This is a soultion for display CHT
		} else {
			return false;
		}
		
	}
	
	function insertSchedule($source) {
	
	
		if($this -> db -> insert('SCHEDULE', $source)) {
			return true;
		} else {
			return false;
		}
	
	}
	
	function getEventList($limit, $start, $searchCriteria=NULL) {
		$this -> db -> select('EVENT_ID, EVENT_TITLE, EVENT_TITLE_CHT, START, END, URL, ALL_DAY');		
		$query = $this -> db -> get('SCHEDULE', $limit,$start);
		
		if ($query -> num_rows() > 0) {
			return json_encode($query->result(), JSON_UNESCAPED_UNICODE); //JSON_UNESCAPED_UNICODE: This is a soultion for display CHT
		} else {
			return false;
		}
	}
	
	function getEventDetail($id=NULL) {
		if (empty($id)) {
			return false;
		}
		$this -> db -> select('EVENT_ID, EVENT_TITLE, EVENT_TITLE_CHT, START, END, URL, ALL_DAY');
		$this -> db -> where('EVENT_ID', $id);
		$this ->db -> from('SCHEDULE');		
		$query = $this -> db -> get();
		
		
		if ($query -> num_rows() > 0) {
			return json_encode($query->result(), JSON_UNESCAPED_UNICODE); //JSON_UNESCAPED_UNICODE: This is a soultion for display CHT
		} else {
			return false;
		}
	}
	
	function getEventCount($searchCriteria=NULL) {
		$this -> db -> select('SEQ');
		$this -> db -> from('SCHEDULE');
		
		$query = $this -> db -> get();
		
		return $query -> num_rows();
	}
}