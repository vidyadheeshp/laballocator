<?php
    ini_set('display_errors', 1);
    ini_set('max_execution_time', 500); 
    require_once('../ajax/allocatefunctions.php');
    if (session_id() == '') {
        session_start();
        $login_id = $_SESSION['s_id'];
        //$dept_id = $_SESSION['dept'];
    }
    
     if(!isset($_SESSION['logged_in'])) {
          header("Location: login.php"); 
     }  
    
    
$lab_ids=db_all("select * from labs where labid not in (1,11, 12,13, 16)");
// print_r($lab_ids);
$week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
       
     $cnt=0;
   $allocation_id = 'NULL';

    $academic_year_row=db_one("select * from academic_year ORDER BY id DESC LIMIT 1");
    $academic_year=$academic_year_row['id'];
   
    $courses_dept_sem=db_all("select * from courses where academicyear=$academic_year and allocation_status=0 group by deptid,sem; ");
    $startDate=$academic_year_row['from_date'];

    $endDate=$academic_year_row['to_date'];
    

    $fetch_lab_slots=db_all("select * from slot_list where duration=3");
      
    //Allocate CS BYOD classes
   $insert_csebyod="null, 'CSEBYOD','CSE Class Room',5,6,2,120,0,3,$academic_year,0";
   db_insert(1,"courses",$insert_csebyod);
    $csebyod=db_all("select * from courses where coursecode='CSEBYOD' and academicyear=$academic_year and allocation_status=0");
    
    if(count($csebyod)!=0){
        $cid=$csebyod[0]['cid'];
        $week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        $restricted_lab_id=[10,11];
        foreach($week as $day)
        {
            $cnt=0;
            for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
            {
                $cnt++;
          
                $date=date('Y-m-d', $i);
        
                foreach($fetch_lab_slots as $slot){
                    $slot_id=$slot["slot_id"];

                    if($day=="Saturday" && $slot_id==3){
                        //do not allocate this slot
                    }
                    else{
            
                        $timings=explode('-',$slot['slot_time']);
             //           echo $timings[0];
                        $date_start=$date." ".$timings[0];
                    
                        $date_end=$date.$timings[1];
                        $value1="$allocation_id,'$day','$date_start','$date_end',$slot_id,$cid,1,'$academic_year',".$restricted_lab_id[0];
                       db_update("courses","allocation_status=1", "coursecode='CSEBYOD'");

                        $value2="$allocation_id,'$day','$date_start','$date_end',$slot_id,$cid,2,'$academic_year',".$restricted_lab_id[1];
                        $result1=db_insert(3,"laballocations",$value1); 
                        $result2=db_insert(3,"laballocations",$value2);
                    }
                }
            }
        }
     }
     echo "<br/>CSE Integrated Class Room Allocated Successfully<br/>";
   
    //Allocate First Year CCP labs
    $get_fy_courses=db_all("select * from courses where deptid=7 and academicyear=$academic_year and allocation_status=0 order by duration");
   $startDate=$academic_year_row['from_date'];
    $endDate=$academic_year_row['to_date'];
   $restricted_lab_id=[1,13,16]; 
   if(count($get_fy_courses)>0){
   
   $week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
   $labno=0;
 
    foreach($get_fy_courses as $fy_course){
        $batch=0;
        $no_divisions=$fy_course['divisions'];
        if($batch==$no_divisions) break;
        $get_lab_slots=db_all("select * from slot_list where duration=".$fy_course['duration']);
       
        if(strpos(strtoupper($fy_course['coursecode']),"CCP") ==true ||strpos(strtoupper($fy_course['coursecode']),"CPL")==true || strpos(strtoupper($fy_course['coursecode']),"EGR")==true)
        {
            foreach($get_lab_slots as $slot)
            {
                foreach($week as $day)
                {
                    if($batch<$no_divisions){
                
                        $batch++;
                        //  echo $date." ".$batch;
                        for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                        {
                            $date=date('Y-m-d', $i);
                            $timings=explode('-',$slot['slot_time']);
                            $date_start=$date." ".$timings[0];
                    
                            $date_end=$date.$timings[1];
                        
                            $slot_id=$slot['slot_id'];

                            if($day=="Saturday" && ($slot_id==3 ||$slot_id==6 ||$slot_id==7 || $slot_id==9)){
                                //do not allocate this slot
                            }
                            else{        
                                if(strpos(strtoupper($fy_course['coursecode']),"CCP") ==true ||strpos(strtoupper($fy_course['coursecode']),"CPL")==true  )
                                {
                                    $value="$allocation_id,'$day','$date_start','$date_end',$slot_id,".$fy_course['cid'].",$batch,'$academic_year',13";
                                    $result=db_insert(3,"laballocations",$value);
                                }
                                if(strpos(strtoupper($fy_course['coursecode']),"EGR")==true){
                                    //echo strpos(strtoupper($fy_course['coursecode']),"EGR");
                                    if($batch<6){
                                        $value="$allocation_id,'$day','$date_start','$date_end',$slot_id,".$fy_course['cid'].",$batch,'$academic_year',1";
                                        // echo $value."<br/>";
                                        $result=db_insert(3,"laballocations",$value);
                                    }
                                    if($no_divisions>5 && $batch<4){
                                        $value="$allocation_id,'$day','$date_start','$date_end',$slot_id,".$fy_course['cid'].",$batch,'$academic_year',16";
                                        $result=db_insert(3,"laballocations",$value);
                                    }
                                }
                            }
                        }
                    }
                    else{
                        break;
                    }
                }
            }
        }
        else{
            $flag=0;
            $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$fy_course['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (10,11)");               $lab_slots_to_allocate=array();
            foreach($query_labs as $lab){
                $lab_slots_to_allocate=get_free_slots($lab['labid'],$no_divisions,$fy_course['duration'],$startDate,$endDate);
                if(count($lab_slots_to_allocate)>0) break;
            }
            if($flag==0){
                foreach($lab_slots_to_allocate as $lab_slots){
                       
                    $slot=substr($lab_slots,strlen($lab_slots)-1);
                    $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
                        
                        
                    if(ctype_alpha($lab_slots[1]))
                        $day=substr($lab_slots,1,strlen($lab_slots)-2); 
                    else
                        $day=substr($lab_slots,2,strlen($lab_slots)-3); 
                    
                  
                    for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                    {
                        $date=date('Y-m-d', $i);
                                                    
                        if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                            //do not allocate this slot
                        }
                        else{
                            $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                            $date_start=$date." ".$timings[0];
                   
                            $date_end=$date.$timings[1];
                            $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$fy_course['cid'].",1,'$academic_year',".$lab['labid'];
                            $result=db_insert(3,"laballocations",$value); 
                            $flag=1;
                        }
                    }
                }
            }
                    
         
        }
            //$sql_query = "UPDATE courses SET allocation_status=1 WHERE cid=".$fy_courses['cid'];
        db_update("courses","allocation_status=1","cid=".$fy_course['cid']);
        
    }

            
    //first year allocation status
    echo "<br/>First Year Allocation Successfully<br/>";

   }
   
    //allocate IOT courses
   
  $check_IOT_required=db_all("select * from courses where coursename like '%IOT%'
   or coursename like '%internet of things%' or softwarereq like '%IOT%' 
   or softwarereq like '%Internet of Things%' and academicyear=$academic_year and allocation_status=0 order by duration Desc");
   
   foreach($check_IOT_required as $IOTcourse){
        $sem=$IOTcourse['sem'];
        $dept=$IOTcourse['deptid'];
        $othercourses=db_all("select * from courses where deptid=$dept and sem=$sem and cid!=".$IOTcourse['cid']." and academicyear=$academic_year and allocation_status=0");
       
        $count_other_courses=count($othercourses);
     
        if($count_other_courses>0)
        {
            
            $no_divisions=$IOTcourse['divisions'];
            
            if($no_divisions==1 && $IOTcourse['strength']>60)
            {
                $no_batches=2;
                $lab_capacity=$IOTcourse['strength']/2;
            }
            else
            {
                $no_batches=$no_divisions;
                if($IOTcourse['strength']>60){
                    $lab_capacity=60;
                }
                else{
                    $lab_capacity=$IOTcourse['strength'];
                }
            }
            //check if the number of batches are less than courses to allocate.
            //if courses are more than divisions, allocate courses on different days.
            if($no_batches<$count_other_courses+1){
                //if single division then allocate the courses one per day.
                if($no_batches==1){
                   
                    $lab_slots_to_allocate=get_free_slots("7",$no_batches,$IOTcourse['duration'],$startDate,$endDate);
                    $cid=array();  //array of courses to be allocated 
                    $labs=array();
                    $durations="";
                    $labs=array();
             
                    $b=0;
                    $lab_day=array();
                    $startDate=$academic_year_row['from_date'];
                    $endDate=$academic_year_row['to_date'];
                    if(ctype_alpha($lab_slots_to_allocate[$b][1]))
                        $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
                    else
                        $day=substr($lab_slots_to_allocate[$b],2,strlen($lab_slots_to_allocate[$b])-3);  
                    $slot=substr($lab_slots_to_allocate[$b],strlen($lab_slots_to_allocate[$b])-1);
                    $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");
                    $ld=0;
                    $lab_day[$ld++]=$day;
                    for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                    {
                        $date=date('Y-m-d', $i);
                                                    
                        if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                            //do not allocate this slot
                        }
                        else{
                            $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                            $date_start=$date." ".$timings[0];
                   
                            $date_end=$date.$timings[1];
                            $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$IOTcourse['cid'].",1,'$academic_year',7";
                    
                            $result=db_insert(3,"laballocations",$value); 
                            db_update("courses","allocation_status=1","cid=".$IOTcourse['cid']);
                        }
                    }
                    $lab_free_slots=array();
                    $labs=array();
                    for($c=0;$c<$count_other_courses;$c++)
                    {
                       $labs[$c]=array();
                        $cid[$c]=$othercourses[$c]['cid'];
                        $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$othercourses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");
                            
                        for($l=0;$l<count($query_labs);$l++) 
                            $labs[$c][$l]=$query_labs[$l]['labid'];
                        
                        $query_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,10, 11,13, 16)");
                        for($j=0;$j<count($query_labs);$j++) 
                            $labs[$c][$l++]=$query_labs[$j]['labid'];
                        
                        $query_labs=db_all("select * from `labs` where deptid!=$dept and no_of_systems>=$lab_capacity and labid not in (1, 10, 11, 13, 16) order by no_of_systems");
                        for($j=0;$j<count($query_labs);$j++) 
                            $labs[$c][$l++]=$query_labs[$j]['labid'];
                        
                        $lab_free_slots[$c]=get_all_free_slots($labs[$c],$othercourses[$c]['duration'],$startDate,$endDate);
                
                            
                        
                        $course_lab_id=0;
                        $day="";
                            
                        for($lb=0;$lb<count($lab_free_slots[$c]);$lb++){
                            $lab_day_flag=0;
                            if(ctype_alpha($lab_free_slots[$b][$lb][1]))
                                $day=substr($lab_free_slots[$b][$lb],1,strlen($lab_free_slots[$b][$lb])-2); 
                            else
                                $day=substr($lab_free_slots[$b][$lb],2,strlen($lab_free_slots[$b][$lb])-3); 
                                  
                            for($l=0;$l<count($lab_day);$l++){
                                
                                if($day==$lab_day[$l]){
                                
                                    $lab_day_flag=1;
                                    break;
                                }
                            }
                            if($lab_day_flag==0){
                                if(ctype_alpha($lab_free_slots[$c][$lb][1])){
                                    $course_lab_id=substr($lab_free_slots[$c][$lb],0,1);
                                }
                                else{
                                    $course_lab_id=substr($lab_free_slots[$c][$lb],0,2);
                                }
                                $lab_day[$ld++]=$day;
                                break;
                            }
                        }
                            
                        for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                        {
                            $date=date('Y-m-d', $i);
                                                    
                            if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                //do not allocate this slot
                            }
                            else{
                                $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                                    $date_start=$date." ".$timings[0];
                   
                                    $date_end=$date.$timings[1];
                                    $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$cid[$c].",1,'$academic_year',$course_lab_id";
                                    $result=db_insert(3,"laballocations",$value); 
                                    db_update("courses","allocation_status=1","cid=".$cid[$c]);
                                }
                            }
                        
                    }
                }
                //allocating IOT courses if number of divisions are more than one and courses are more than divisons
                //allocate courses equal to divisions on same day and others on different day
                else {
                    //get Lab 6 free slots
                    $lab_slots_to_allocate=get_free_slots("7",$no_batches,$IOTcourse['duration'],$startDate,$endDate);
                    $cid=array();  //array of courses to be allocated 
                    $labs=array();
                    $durations="";
                    $lab_day=array();
                    $lab_free_slots=array();
                    for($c=0;$c<$count_other_courses;$c++)
                    {
                       $labs[$c]=array();
                        $cid[$c]=$othercourses[$c]['cid'];
                        $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$othercourses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");
                       
                                                
                            for($l=0;$l<count($query_labs);$l++){
                                $labs[$c][$l]=$query_labs[$l]['labid'];
                            }
                            $query_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,10, 11,13, 16)");
                            for($j=0;$j<count($query_labs);$j++){
                                $labs[$c][$l++]=$query_labs[$j]['labid'];
                            }

                                $query_labs=db_all("select * from `labs` where deptid!=$dept and no_of_systems>=$lab_capacity and labid not in (1, 10, 11, 13, 16) order by no_of_systems");
                                for($j=0;$j<count($query_labs);$j++){
                                    $labs[$c][$l++]=$query_labs[$j]['labid'];
                                }
                                $lab_free_slots[$c]=get_labs($labs[$c],$lab_slots_to_allocate,$startDate,$endDate);
                            
                        }
                
                    
                    $startDate=$academic_year_row['from_date'];

                    $endDate=$academic_year_row['to_date'];
                    $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
                    $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
                    $ld=0;
                    for($b=0;$b<$no_batches;$b++){
                    if(ctype_alpha($lab_slots_to_allocate[$b][1]))
                        $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
                    else
                        $day=substr($lab_slots_to_allocate[$b],2,strlen($lab_slots_to_allocate[$b])-3); 
                        $lab_day[$ld++]=$day;
                        
                        for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                        {
                            $date=date('Y-m-d', $i);
                                                    
                            if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                //do not allocate this slot
                            }
                            else{
                                $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                                $date_start=$date." ".$timings[0];
                   
                                $date_end=$date.$timings[1];
                                $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$IOTcourse['cid'].",1,'$academic_year',7";
                               
                                $result=db_insert(3,"laballocations",$value); 
                                db_update("courses","allocation_status=1","cid=".$IOTcourse['cid']);
                            
                                for($c=0;$c<$no_batches-1;$c++){
                                   if(ctype_alpha($lab_free_slots[$c][0][1])){
                                        $course_lab_id=substr($lab_free_slots[$c][0],0,1);
                                   }
                                   else{
                                        $course_lab_id=substr($lab_free_slots[$c][$lb],0,2);
                                   }
                                    $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$c],1,'$academic_year',$course_lab_id";
                                    $result=db_insert(3,"laballocations",$value1);
                                }
                            }
                            
                            for($c=0;$c<$no_batches-1;$c++){
                                db_update("courses","allocation_status=1","cid=".$cid[$c]);
                                
                            }
                        }
           
                    } 
                   
                    $startDate=$academic_year_row['from_date'];
                    $endDate=$academic_year_row['to_date'];
                    for($b=0;$b<$no_batches;$b++){
                    for($c=$no_batches-1;$c<$count_other_courses;$c++){
                        $course_lab_id=0;
                        $day="";
                        $lab_free_slots[$c]=get_all_free_slots($labs[$c],$IOTcourse['duration'],$startDate,$endDate);
                            
                        for($lb=0;$lb<count($lab_free_slots[$c]);$lb++){
                            $lab_day_flag=0;
                           
                            if(ctype_alpha($lab_free_slots[$b][1]))
                                $day=substr($lab_free_slots[$b],1,strlen($lab_free_slots[$b])-2); 
                            else
                                $day=substr($lab_free_slots[$b],2,strlen($lab_free_slots[$b])-3);  

                               for($l=0;$l<count($lab_day);$l++){
                                
                                if($day==$lab_day[$l]){
                                
                                    $lab_day_flag=1;
                                    break;
                                }
                            }
                            if($lab_day_flag==0){
                                if(ctype_alpha($lab_free_slots[$c][$lb][1])){
                                    $course_lab_id=substr($lab_free_slots[$c][$lb],0,1);
                                }
                                else{
                                    $course_lab_id=substr($lab_free_slots[$c][$lb],0,2);
                                }
                                $lab_day[$ld++]=$day;
                                break;
                            }
                        }
                        
                           
                        for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                        {
                            $date=date('Y-m-d', $i);
                                                    
                            if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                //do not allocate this slot
                            }
                            else{
                                $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                                $date_start=$date." ".$timings[0];
                   
                                $date_end=$date.$timings[1];
                                $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$cid[$c].",1,'$academic_year',$course_lab_id";
                                $result=db_insert(3,"laballocations",$value); 
                                db_update("courses","allocation_status=1","cid=".$cid[$c]);
                            }
                        }
                    }
                }
                    
                }
            
            }
            //allocate courses less than divisions
            else{
                //get Lab 6 free slots
                $lab_slots_to_allocate=get_free_slots("7",$no_batches,$IOTcourse['duration'],$startDate,$endDate);
                
                $cid=array();  //array of courses to be allocated 
                $labs=array();
                $durations="";
                $allocate_lab=array();
                
                
                for($c=0;$c<$count_other_courses;$c++)
                {
            
                    $cid[$c]=$othercourses[$c]['cid'];
                    $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$othercourses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");

                    for($l=0;$l<count($query_labs);$l++){
                        $labs[$l]=$query_labs[$l]['labid'];
                    
                    }
                    
                    $query_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,10, 11,13, 16)");
                    for($j=0;$j<count($query_labs);$j++){
                        $labs[$l++]=$query_labs[$j]['labid'];
                        
                    }
                    $query_labs=db_all("select * from `labs` where deptid!=$dept and no_of_systems>=$lab_capacity and labid not in (1, 10, 11, 13, 16) order by no_of_systems");
                   
                    for($j=0;$j<count($query_labs);$j++){
                        $labs[$l++]=$query_labs[$j]['labid'];
                    
                    }
                    $lab_free_slots[$c]=get_labs($labs,$lab_slots_to_allocate,$startDate,$endDate);
                
                    foreach($lab_free_slots[$c] as $free_lab){
                        $flag=0;
                        foreach($allocate_lab as $lab){
                            if($lab==$free_lab || $lab==7){
                                $flag=1;
                                break;
                            }
                        }
                            if($flag==0){
                                $allocate_lab[$c]=$free_lab;
                                break;
                            }
                        }
                    }
                
            
                                        
                $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
                $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
             
               
                for($b=0;$b<$no_batches;$b++){
                    $startDate=$academic_year_row['from_date'];

                    $endDate=$academic_year_row['to_date'];
                    if(ctype_alpha($lab_slots_to_allocate[$b][1])){
                        $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
                    }
                    else{
                        $day=substr($lab_slots_to_allocate[$b],2,strlen($lab_slots_to_allocate[$b])-3);
                    }
                    for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                    {
                        $date=date('Y-m-d', $i);
                                                    
                        if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                            //do not allocate this slot
                        }
                        else{
                            $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                            $date_start=$date." ".$timings[0];
                   
                            $date_end=$date.$timings[1];
                            $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$IOTcourse['cid'].",1,'$academic_year',7";
                    
                            $result=db_insert(3,"laballocations",$value); 
                            db_update("courses","allocation_status=1","cid=".$IOTcourse['cid']);
                            for($c=0;$c<$count_other_courses;$c++){
                                    $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$c],1,'$academic_year',$allocate_lab[$c]";
                                    $result=db_insert(3,"laballocations",$value1);
                            }
                        }
                        
                        for($c=0;$c<count($cid);$c++){
                            db_update("courses","allocation_status=1","cid=".$cid[$c]);
                               
                        }
                    }
                } 
            }
       
        }
   }
    
   
   echo "<br/>IOT courses completed<br/>"; 
    
   //lab allocation for BE higher semesters and Masters
 
    $query_sems=db_all("select *, count(*) as no_of_courses from courses where deptid!=7 and coursecode!='CSEBYOD' and academicyear=$academic_year and allocation_status=0 group by deptid,sem order by divisions desc,strength desc");
    foreach($query_sems as $sem)
    {
        $semno=$sem['sem'];
        $deptid=$sem['deptid'];
        $no_of_courses=$sem['no_of_courses'];
        $no_divisions=$sem['divisions'];
        $query_courses=db_all("select * from courses where sem=$semno and deptid=$deptid and allocation_status=0 and academicyear=$academic_year");
        if($no_divisions==1 && $sem['strength']>60){
            $no_batches=2;
            $lab_capacity=$sem['strength']/2;
        }
        else{
            $no_batches=$no_divisions;
            if($sem['strength']>60){
                $lab_capacity=60;
            }
            else{
                $lab_capacity=$sem['strength'];
            }
        }
        //check if the number of batches are less than courses to allocate
        //if courses are more than divisions, allocate courses on different days
        if(count($query_courses)>$no_batches){
            //if single division then allocate each of the course of different days
            if($no_batches==1){
                $labs=array();
               
                $ll=0;
                $labs[0]=array();
                $lno=-1;
                    
                $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$query_courses[0]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16) ");
                for($ll=0;$ll<count($query_labs);$ll++)
                    $labs[0][$ll]=$query_labs[$ll]['labid'];
                $query_labs=db_all("select * from labs where deptid=$deptid and labid not in (1,10, 11,13, 16)");
                for($j=0;$j<count($query_labs);$j++)
                    $labs[0][$ll++]=$query_labs[$j]['labid'];
                $query_labs=db_all("select * from labs where deptid!=$deptid and no_of_systems>=$lab_capacity and labid not in (1,10, 11,13, 16) order by no_of_systems");
                for($j=0;$j<count($query_labs);$j++)
                    $labs[0][$ll++]=$query_labs[$j]['labid'];
            
                for($l=0;$l<count($labs[0]);$l++){
                    $lab_slots_to_allocate=get_free_slots($labs[0][$l],$no_batches,$query_courses[0]['duration'],$startDate,$endDate);
                    if(count($lab_slots_to_allocate)>0)
                        break;
                }
                
                $lab_day=array();
                
                $startDate=$academic_year_row['from_date'];
                $endDate=$academic_year_row['to_date'];
                    
                if(ctype_alpha($lab_slots_to_allocate[0][1])){
                    $day=substr($lab_slots_to_allocate[0],1,strlen($lab_slots_to_allocate[0])-2); 
                    $labid=substr($lab_slots_to_allocate[0],0,1);
                }
                else{
                    $day=substr($lab_slots_to_allocate[0],2,strlen($lab_slots_to_allocate[0])-3);  
                    $labid=substr($lab_slots_to_allocate[0],0,2);                    
                }
                $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
            
                $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");
                $ld=0;
                $lab_day[$ld++]=$day;
                    
                for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                {
                    $date=date('Y-m-d', $i);
                                                    
                    if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                        //do not allocate this slot
                    }
                    else{
                        $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                        $date_start=$date." ".$timings[0];
                   
                        $date_end=$date.$timings[1];
                        $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$query_courses[0]['cid'].",1,'$academic_year',$labid";
                    
                        $result=db_insert(3,"laballocations",$value); 
                        db_update("courses","allocation_status=1","cid=".$query_courses[0]['cid']);
                    }
                }
                $lab_free_slots=array();
                for($c=1;$c<$no_of_courses;$c++){
            
                    $labs[$c]=array();
                    $cid[$c]=$query_courses[$c]['cid'];
                    $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$query_courses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");
                    for($l=0;$l<count($query_labs);$l++) 
                        $labs[$c][$l]=$query_labs[$l]['labid'];
                    $query_labs=db_all("select * from labs where deptid=$deptid and labid not in (1,10, 11,13, 16)");
                    for($j=0;$j<count($query_labs);$j++)
                        $labs[$c][$l++]=$query_labs[$j]['labid'];
                    $query_labs=db_all("select * from labs where deptid!=$deptid and no_of_systems>=$lab_capacity and labid not in (1,10, 11,13, 16) order by no_of_systems");
                    for($j=0;$j<count($query_labs);$j++)
                        $labs[$c][$l++]=$query_labs[$j]['labid'];
                   
                    $lab_free_slots=get_all_free_slots($labs[$c],$query_courses[$c]['duration'],$startDate,$endDate);
                    
                
                
                $course_lab_id=0;
                $day="";
                    
                foreach($lab_free_slots as $free_lab){
                    $lab_day_flag=0;
                    if(ctype_alpha($free_lab[1])){
                        $day=substr($free_lab,1,strlen($free_lab)-2); 
                        $slot_id=substr($free_lab,strlen($free_lab)-1);
                    }
                    else{
                        $day=substr($lab_free_slots[$b][$lb],2,strlen($lab_free_slots[$lb])-3); 
                        $slot_id=substr($lab_free_slots[$lb],strlen($lab_free_slots[$lb])-1);
                    }  
                    foreach($lab_day as $lday){
                        if($day==$lab_day_flag){
                            $lab_day_flag=1;
                            break;
                        }
                    }
                    if($lab_day_flag==0){
                        if(ctype_alpha($free_lab[1])){
                            $course_lab_id=substr($free_lab,0,1);
                        }
                        else{
                            $course_lab_id=substr($free_lab,0,2);
                        }
                        $lab_day[$ld++]=$day;
                        break;
                    }
                }
                for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                {
                    $date=date('Y-m-d', $i);
                                            
                    if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                        //do not allocate this slot
                    }
                    else{
                        $timings=explode('-',$get_lab_slots[0]['slot_time']);
            
                            $date_start=$date." ".$timings[0];
           
                            $date_end=$date.$timings[1];
                            $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$cid[$c].",1,'$academic_year',$course_lab_id";
                            $result=db_insert(3,"laballocations",$value); 
                            db_update("courses","allocation_status=1","cid=".$cid[$c]);
                        }
                    }
                
                }
    
            }// end of no-batches==1
            //allocate no_of_batches>1 but less than courses
            else{
                $cid=array();  //array of courses to be allocated 
                $labs=array();
                $durations="";
                $lab_day=array();
                $lab_free_slots=array();
                $lab_slots_to_allocate="";
                $allocated_lab=array();
                for($c=0;$c<$no_of_courses;$c++)
                {
                    $labs[$c]=array();
                    $cid[$c]=$query_courses[$c]['cid'];
                    $durations=$query_courses[$c]['duration'];
                    $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$query_courses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");
                    for($l=0;$l<count($query_labs);$l++){
                        $labs[$c][$l]=$query_labs[$l]['labid'];
                    }
                        
                    $query_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,10, 11,13, 16)");
                    for($l=0;$l<count($query_labs);$l++){
                        $labs[$c][$l]=$query_labs[$l]['labid'];
                    }
                    
                    $query_labs=db_all("select * from `labs` where deptid!=$dept and no_of_systems>=$lab_capacity and labid not in (1, 10, 11, 13, 16) order by no_of_systems");
                    for($l=0;$l<count($query_labs);$l++){
                        $labs[$c][$l]=$query_labs[$l]['labid'];
                    }
                   
                        
                    if($c==0){
                        for($l=0;$l<count($labs[0]);$l++){
                            $lab_slots_to_allocate=get_free_slots($labs[0][$l],$no_batches,$durations,$startDate,$endDate);
                            if(count($lab_slots_to_allocate)>0){
                                if(ctype_alpha($lab_slots_to_allocate[0][1])){
                                    $allocate_lab[0]=substr($lab_slots_to_allocate[0],0,1);
                                }
                                else{
                                    $allocate_lab[0]=substr($lab_slots_to_allocate[0],0,2);
                                }
                                break;
                                
                            }
                        }
                    }
                    else{
                        $lab_free_slots[$c]=get_labs($labs[$c],$lab_slots_to_allocate,$startDate,$endDate);
                        foreach($lab_free_slots[$c] as $free_lab){
                            $flag=0;
                            foreach($allocate_lab as $lab){
                                if($free_lab==$lab){
                                    $flag=1;
                                    break;
                                }
                            }
                            if($flag==0){
                                $allocate_lab[$c]=$free_lab;
                                break;
                            }
                        }
                    
                    }
                }
              
                $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
                $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
                $ld=0;
                $day="";
                
                for($b=0;$b<$no_batches;$b++){
                    $startDate=$academic_year_row['from_date'];
                    $endDate=$academic_year_row['to_date'];
                    if(ctype_alpha($lab_slots_to_allocate[$b][1])){
                        $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
                    }
                    else{
                        $day=substr($lab_slots_to_allocate[$b],2,strlen($lab_slots_to_allocate[$b])-3); 
                    }
                    $lab_day[$ld++]=$day;
                       
                    for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                    {
                        $date=date('Y-m-d', $i);
                                                    
                        if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                            //do not allocate this slot
                        }
                        else{
                            $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                            $date_start=$date." ".$timings[0];
                   
                            $date_end=$date.$timings[1];
                            
                            for($c=0;$c<$no_batches;$c++){
                                $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$c],1,'$academic_year',$allocate_lab[$c]";
                                $result=db_insert(3,"laballocations",$value1);
                            }
                        }
                            
                        for($c=0;$c<$no_of_courses;$c++){
                            db_update("courses","allocation_status=1","cid=".$cid[$c]);
                                
                        }
                    }
           
                } 
                
                $startDate=$academic_year_row['from_date'];
                $endDate=$academic_year_row['to_date'];
                if($no_batches<4){
                    for($b=0;$b<$no_batches;$b++){
                        for($c=$no_batches;$c<$no_of_courses;$c++){
                            $course_lab_id=0;
                            $day="";
                            for($l=0;$l<count($labs[$c]);$l++){
                                $lab_free_slots[$c]=get_free_slots($labs[$c][$l],$no_batches,$query_courses[$c]['duration'],$startDate,$endDate);
                                if(count($lab_free_slots[$c])>0){
                                    foreach($lab_free_slots[$c] as $free_lab){
                                        $flag==0;
                                        if(ctype_alpha($free_lab[1])){
                                            $d=substr($free_lab,1,strlen($free_lab)-2);
                                        }
                                        else{
                                            $d=substr($free_lab,2,strlen($free_lab)-3);
                                        }
                                        foreach($lab_day as $lday){
                                            if($lday==$d){
                                                $flag==1;
                                            }
                                        }
                                        if($flag==0){
                                            if(ctype_alpha($free_lab[1])){
                                                $day=substr($free_lab,1,strlen($free_lab)-2);
                                                $slot=substr($free_lab,strlen($free_lab)-1);
                                                $course_lab_id=substr($free_lab,0,1);
                                            }
                                            else{
                                                $d=substr($free_lab,2,strlen($free_lab)-3);
                                                $slot=substr($free_lab,strlen($free_lab)-1);
                                                $course_lab_id=substr($free_lab,0,2);
                                            }
                                            $lab_day[count($lab_day)]=$d;
                                            break;
                                        }
                                    }
                                }
                            }
                        
                            $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");
                            for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                            {
                                $date=date('Y-m-d', $i);
                                                    
                                if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                    //do not allocate this slot
                                }
                                else{
                                    $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                                    $date_start=$date." ".$timings[0];
                   
                                    $date_end=$date.$timings[1];
                                    $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$cid[$c].",1,'$academic_year',$course_lab_id";
                                    $result=db_insert(3,"laballocations",$value); 
                                    db_update("courses","allocation_status=1","cid=".$cid[$c]);
                                }
                            }
                        }
                    }
                }
                else{
                    for($b=0;$b<$no_batches;$b++){
                        for($c=$no_batches;$c<$no_of_courses;$c++){
                            $course_lab_id=0;
                            $day="";
                        
                                $lab_free_slots[$c]=get_all_free_slots($labs[$c],$no_batches,$query_courses[$c]['duration'],$startDate,$endDate);
                                if(count($lab_free_slots[$c])>0){
                                    foreach($lab_free_slots[$c] as $free_lab){
                                        $flag==0;
                                        if(ctype_alpha($free_lab[1])){
                                            $day=substr($free_lab,1,strlen($free_lab)-2);
                                            $slot=substr($free_lab,strlen($free_lab)-1);
                                        }
                                        else{
                                            $day=substr($free_lab,2,strlen($free_lab)-3);
                                            $slot=substr($free_lab,strlen($free_lab)-1);
                                        }
                                      
                                       
                                    }
                                }
                            
                        
                            $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");
                            for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                            {
                                $date=date('Y-m-d', $i);
                                                    
                                if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                    //do not allocate this slot
                                }
                                else{
                                    $timings=explode('-',$get_lab_slots[0]['slot_time']);
                    
                                    $date_start=$date." ".$timings[0];
                   
                                    $date_end=$date.$timings[1];
                                    $value="$allocation_id,'$day','$date_start','$date_end',$slot,".$cid[$c].",1,'$academic_year',$course_lab_id";
                                    $result=db_insert(3,"laballocations",$value); 
                                    db_update("courses","allocation_status=1","cid=".$cid[$c]);
                                }
                            }
                        }
                    }
                    
                }
             
            }
        } //end of count($query_courses)>$no_batches)
        //courses are less than or equal to number of divisions
        //allocate all courses parallelly
        else{
            $lab_free_slots=array();
            $cid=array();
            $lab_slots_to_allocate=array();
            for($c=0;$c<$no_of_courses;$c++)
            {
                $labs[$c]=array();
                $cid[$c]=$query_courses[$c]['cid'];
                $query_labs=db_all("select * from `labs`,lab_software_requirements where software_id=".$query_courses[$c]['softwarereq'] ." and labs.labid=lab_software_requirements.labid and labs.labid not in (1,10, 11,13, 16)");
                for($l=0;$l<count($query_labs);$l++){
                    $labs[$c][$l]=$query_labs[$l]['labid'];
                }
                $query_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,10, 11,13, 16)");
                for($j=0;$j<count($query_labs);$j++){
                    $labs[$c][$l++]=$query_labs[$j]['labid'];
                }
                $query_labs=db_all("select * from `labs` where deptid!=$dept and no_of_systems>=$lab_capacity and labid not in (1, 10, 11, 13, 16) order by no_of_systems");
                for($l=0;$l<count($query_labs);$l++){
                    $labs[$c][$l]=$query_labs[$l]['labid'];
                }
                
                if($c==0){
                    for($l=0;$l<count($labs[0]);$l++)
                    {
                        $lab_slots_to_allocate=get_free_slots($labs[$c][$l],$no_batches,$query_courses[$c]['duration'],$startDate,$endDate);
                        if(count($lab_slots_to_allocate)>0){
                        
                            if(ctype_alpha($lab_slots_to_allocate[0][1])){
                                $allocate_lab[$c]=substr($lab_slots_to_allocate[0],0,1);
                            }
                            else{
                                $allocate_lab[$c]=substr($lab_slots_to_allocate[0],0,2);
                            }
                            break;
                        }
                    }
                }
                else{
                    $lab_free_slots[$c]=get_labs($labs[$c],$lab_slots_to_allocate,$startDate,$endDate);
                    foreach($lab_free_slots[$c] as $free_lab){
                        $flag=0;
                        foreach($allocate_lab as $lab){
                            if($free_lab==$lab){
                                $flag=1;
                                break;
                            }
                        }
                        if($flag==0){
                            $allocate_lab[$c]=$free_lab;
                        }
                    }
             
                }
            }
           
            $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
            $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
          
            for($b=0;$b<$no_batches;$b++){
                $startDate=$academic_year_row['from_date'];

                $endDate=$academic_year_row['to_date'];
                if(ctype_alpha($lab_slots_to_allocate[$b][1]))
                    $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
                else
                    $day=substr($lab_slots_to_allocate[$b],2,strlen($lab_slots_to_allocate[$b])-3);
                
                    
                for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                {
                    $date=date('Y-m-d', $i);
                                                
                    if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                        //do not allocate this slot
                    }
                    else{
                        $timings=explode('-',$get_lab_slots[0]['slot_time']);
                
                        $date_start=$date." ".$timings[0];
               
                        $date_end=$date.$timings[1];
                        for($c=0;$c<$no_of_courses;$c++){
                            $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$c],1,'$academic_year',".$allocate_lab[$c];
                            $result=db_insert(3,"laballocations",$value1);
                        }
                    }
                    
                    for($c=0;$c<count($cid);$c++){
                        db_update("courses","allocation_status=1","cid=".$cid[$c]);
                           
                    }
                } 
            }
        }
    }


?>