<?php 

	include('pages/required/db_connection.php');
	include('pages/required/tables.php');
	require('pages/required/functions.php');

	if(isset($_GET['lab_id'])){
		$query_text = "AND l.labid=".$_GET['lab_id'];
	}else{
		$query_text = '';
	}
	

	//echo $lab_id;
	
	$data = array();
	
	/*$query = "SELECT la.*,
				l.labid, 
				l.labname,
				c.deptid,
				DATE_FORMAT(la.date_start,'%h:%m:%s')AS start_time,
				DATE_FORMAT(la.date_end,'%h:%m:%s') AS end_time
			FROM 
				laballocations la,labs l,courses c
			WHERE 
				la.labid = l.labid and c.cid=la.coursecode */
	$query="SELECT 
				LA.allocationid,l.labid,la.date_start,la.date_end,l.labname,c.deptid,C.coursename,C.coursecode,DATE_FORMAT(la.date_start,'%h:%m:%s')AS start_time,DATE_FORMAT(la.date_end,'%h:%m:%s') AS end_time
			FROM LABS L, laballocations LA, courses C
			WHERE L.labid=LA.labid AND C.cid=LA.coursecode ".$query_text;

	//echo $query;
	$result = db_all($query);
	//echo 
	//print_r($result);
	
	foreach($result AS $rs){

		//for setting up the background color for different depts with different colors.
		if($rs['deptid'] == 1){ // Aero
			$backgroundcolor = '#F3940B';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 2){ //Arch
			$backgroundcolor = '#F7E505';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 3){ //Civil
			$backgroundcolor = '#AAF705';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 4){ //CSE
			$backgroundcolor = '02C4F9';
			$bordercolor = '#080808';
			$event_color = '#080808';
		}else if($rs['deptid'] == 5){ //EEE
			$backgroundcolor = '#B105F7';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 6){//ECE
			$backgroundcolor = '#4F0A52';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 7){// First Year CCP
			$backgroundcolor = '#0A5250';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 8){//ISE
			$backgroundcolor = '#DE104B';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 9){ //MBA
			$backgroundcolor = '#7E956C';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 10){// MCA
			$backgroundcolor = '#58B9FC';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else if($rs['deptid'] == 11){ // MECH
			$backgroundcolor = '#030FFE';
			$bordercolor = '#056FF7';
			$event_color = '#FBF8F8';
		}else{    /// Other depts.
			$backgroundcolor = '#FEFE02';
			$bordercolor = '#FE0211';
			$event_color = '#FBF8F8';
		}
		$data [] = array(
			'id' => $rs['allocationid'],
			'title' => $rs['labname'].': '.$rs['coursename'].'-'.$rs['coursecode'],
			'start' => $rs['date_start'],
			'end' => $rs['date_end'],
			'daysOfWeek' => [ '3' ],
			'allDay' => false,
			'eventTextColor' => $event_color,
			'startStr' =>  $rs['start_time'],
			'endStr' => $rs['end_time'],
          	'backgroundColor'=> $backgroundcolor, //Info (aqua)
          	'borderColor' => $bordercolor //Info (aqua)
			);
	}
		//var_dump($data);
	echo json_encode($data,JSON_HEX_TAG);
?>