<?php
    ini_set('display_errors', 1);
    require_once('../ajax/allocatefunctions.php');
    if (session_id() == '') {
        session_start();
        $login_id = $_SESSION['s_id'];
        //$dept_id = $_SESSION['dept'];
    }
    
     if(!isset($_SESSION['logged_in'])) {
          header("Location: login.php"); 
     }  
    
    
     $lab_3hrs_status=array();
$lab_2hrs_status=array();
$lab_4hrs_status=array();

$lab_ids=db_all("select * from labs where labid not in (1,11, 12,13, 16)");
// print_r($lab_ids);
$week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
 foreach($lab_ids as $labs){
   foreach($week as $day){
       $lab_3hrs_status[$labs["labid"].$day."1"]="";
       $lab_3hrs_status[$labs["labid"].$day."2"]="";
       $lab_2hrs_status[$labs["labid"].$day."4"]="";
       $lab_2hrs_status[$labs["labid"].$day."5"]="";
       $lab_4hrs_status[$labs["labid"].$day."8"]="";
       if($day!="Saturday"){
           $lab_3hrs_status[$labs["labid"].$day."3"]=""; 
           $lab_2hrs_status[$labs["labid"].$day."6"]=""; 
           $lab_2hrs_status[$labs["labid"].$day."7"]="";
           $lab_4hrs_status[$labs["labid"].$day."9"]="";
       }
   }
 
 }
       
     $cnt=0;
   $allocation_id = 'NULL';

    $academic_year_row=db_one("select * from academic_year ORDER BY id DESC LIMIT 1");
    $academic_year=$academic_year_row['id'];
   
    $courses_dept_sem=db_all("select * from courses where academicyear=$academic_year and allocation_status=0 group by deptid,sem; ");
    $startDate=$academic_year_row['from_date'];

    $endDate=$academic_year_row['to_date'];
    
    $find_laballocation_id=db_one("select count(*) as c from laballocations");
   // $allocation_id=$find_laballocation_id['c']+1;
    $fetch_lab_slots=db_all("select * from slot_list where duration=3");
    //Allocate CS BYOD classes
    $csebyod=db_all("select * from courses where coursecode='CSEBYOD' and academicyear=$academic_year and allocation_status=0");
    if(count($csebyod)!==0){
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
                        $value1="$allocation_id,'$day','$date_start','$date_end',$slot_id,23,1,'$academic_year',".$restricted_lab_id[0];
                       db_update("courses","allocation_status=1", "coursecode='CSEBYOD'");

                        $value2="$allocation_id,'$day','$date_start','$date_end',$slot_id,23,2,'$academic_year',".$restricted_lab_id[1];
                        $result1=db_insert(3,"laballocations",$value1); 
                        $result2=db_insert(3,"laballocations",$value2);
                    }
                }
            }
        }
     }
     echo "CSE Integrated Class Room Allocated Successfully";
     
    //Allocate First Year CCP labs
    $get_fy_courses=db_all("select * from courses where deptid=7 and academicyear=$academic_year and allocation_status=0");
   // echo "select * from courses where deptid=7 and academicyear=$academic_year and allocation_status=0";
    $status="";
    if(count($get_fy_courses)>0){
        $count_courses=count($get_fy_courses);
        $week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        $restricted_lab_id=[1,13,16];
        $no_divisions=$get_fy_courses[0]['divisions'];
        //check if courses have different lab duration requirements
        $different_durations=db_all("SELECT * FROM `courses` where academicyear=$academic_year and deptid=7 and allocation_status=0 GROUP by duration");
        if(count($different_durations)===1){
            $lab_duration=$different_durations[0]['duration'];
        
            $get_lab_slots=db_all("select * from slot_list where duration=$lab_duration");
        
            $total_slots_available= ((2*count($get_lab_slots))+(0.5*count($get_lab_slots)))*7;
    
            $total_slots_required=(($no_divisions*$count_courses));
    
        
            if($total_slots_required <= $total_slots_available){ 
                if($no_divisions>1 && count($different_durations)===1){
                // echo $count_courses;
                    if($count_courses==1){
                        $batch=0;
                        foreach($week as $day)
                        {
                            $batch++;
                            //  echo $date." ".$batch;
                            for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                            {
                                $date=date('Y-m-d', $i);
                           
                                if($day=="Saturday" && ($slot==3 ||$slot==6 ||$slot==7 || $slot==9)){
                                //do not allocate this slot
                                }
                                else{
                                    $timings=explode('-',$slot['slot_time']);
                            
                                    $date_start=$date." ".$timings[0];
                            
                                    $date_end=$date.$timings[1];
                                    $slot_id1=$get_lab_slots[0]["slot_id"];
                                    $slot_id2=$get_lab_slots[1]["slot_id"];
                                    $value1="$allocation_id,'$day','$date_start','$date_end',$slot_id1,".$get_fy_courses['cid'].",$batch,'$academic_year',".$restricted_lab_id[0];
                                
                                    if($no_divisions>6 and ($batch+6)<=$no_divisions and ($batch+6)<12){
                                        $value2="$allocation_id,'$day','$date_start','$date_end',$slot_id2,".$get_fy_courses['cid'].",$batch+6,'$academic_year',".$restricted_lab_id[0];
                                
                                        $result2=db_insert(3,"laballocations",$value2);
                                    }
                                    $status="Allocated lab slots for first year Successfully";
                                    $sql_query = "UPDATE courses SET allocation_status=1 WHERE cid=".$get_fy_courses['cid'];
                        
                                    $result1=db_insert(3,"laballocations",$value1); 
                                }
                            }
                        }
                    }
            
                    else  if($count_courses===2){
                        $course1=$get_fy_courses[0]["cid"];
                        $course2=$get_fy_courses[1]["cid"];
                        $batch1=0;
                        $batch2=$no_divisions;
                        foreach($week as $day)
                        {
                            $batch1++;
                            $batch2--;
                           // echo "batch and day ".$batch." ".$day;
                            $slot_id1=$get_lab_slots[0]["slot_id"];
                            $slot_id2=$get_lab_slots[1]["slot_id"];
                            for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                            {
                                $date=date('Y-m-d', $i);
                               
                                if($day=="Saturday" && ($slot_id1==3 ||$slot_id1==6 ||$slot_id1==7 || $slot_id1==9) || ($slot_id2==3 ||$slot_id2==6 ||$slot_id2==7 || $slot_id2==9)){
                                //do not allocate this slot
                                }
                                else{
                                    $timings=explode('-',$slot['slot_time']);
                                
                                    $date_start=$date." ".$timings[0];
                                
                                    $date_end=$date.$timings[1];
                                    
                                    $value1="$allocation_id,'$day','$date_start','$date_end',$slot_id1,$course1,$batch1,'$academic_year',".$restricted_lab_id[0];
                                    $value2="$allocation_id,'$day','$date_start','$date_end',$slot_id1,$course2,$batch2,$academic_year,$restricted_lab_id[1]";
                                    $sql_query = "UPDATE courses SET allocation_status=1 WHERE cid=".$course1. " and cid=".$course2;
                                    
                                    $result1=db_insert(3,"laballocations",$value1); 
                                    $result2=db_insert(3,"laballocations",$value2);
                                    //echo $batch+6+ " "+$batch1+6<=$no_divisions;
                                    if($no_divisions>6 and ($batch+6)<=$no_divisions and ($batch+6)<=12){
                                        $value3="'$day','$date_start','$date_end',$slot_id2,$course1,$batch1+6,'$academic_year',".$restricted_lab_id[0];
                                        $value4="$allocation_id,'$day','$form_date','$date_end',$slot_id2,$course2,($batch2-6),$academic_year,$restricted_lab_id[1]";
                                        $result3=db_insert(3,"laballocations",$value3);
                                        $result4=db_insert(3,"laballocations",$value4);
                                    }
                                    $status="Allocated lab slots for first year Successfully";
                                    db_update("courses","allocation_status=1","cid=$course1 or cid=$course2");
                                }
                            }
                        }
                    }
                    else if($count_courses===3){

                            $course1=$get_fy_courses[0]["cid"];
                            $course2=$get_fy_courses[1]["cid"];
                            $course3=$get_fy_courses[2]["cid"];
                            $batch=0;
                            foreach($week as $day)
                            {
                                $batch++;
                                // echo "batch and day ".$batch." ".$day;
                                $slot_id1=$get_lab_slots[0]["slot_id"];
                                $slot_id2=$get_lab_slots[1]["slot_id"];
                                for($i = strtotime($day, strtotime($startDate));(date('Y-m-d', $i)<$endDate)==1; $i = strtotime('+1 week', $i))
                                {
                                    $date=date('Y-m-d', $i);
                                   
                                   if($day=="Saturday" && ($slot_id1==3 ||$slot_id1==6 ||$slot_id1==7 || $slot_id1==9) || ($slot_id2==3 ||$slot_id2==6 ||$slot_id2==7 || $slot_id2==9)){
                                    //do not allocate this slot
                                    }
                                    else
                                    {
                                        $timings=explode('-',$get_lab_slots[0]['slot_time']);
                                        
                                    
                                       $date_start=$date." ".$timings[0];
                                        
                                        $date_end=$date.$timings[1];
                                            
                                        $value1="$allocation_id,'$day','$date_start','$date_end',$slot_id1,$course1,$batch,'$academic_year',".$restricted_lab_id[0];
                                        $value2="$allocation_id,'$day','$date_start','$date_end',$slot_id1,$course2,($batch+1),$academic_year,$restricted_lab_id[1]";
                                        $value3="$allocation_id,'$day','$date_start','$date_end',$slot_id1,$course3,($batch+2),$academic_year,$restricted_lab_id[2]";
                                           
                                        $result1=db_insert(3,"laballocations",$value1); 
                                        $result2=db_insert(3,"laballocations",$value2);
                                        $result3=db_insert(3,"laballocations",$value3);
                                    
                                        if($no_divisions>6 and ($batch+6)<=$no_divisions and ($batch+6)<12){
                                            $timings=explode('-',$get_lab_slots[1]['slot_time']);
                                            $date_start=$date." ".$timings[0];
                                            $date_end=$date.$timings[1];
                                            $value4="$allocation_id,'$day','$date_start','$date_end',$slot_id2,$course1,$batch+6,'$academic_year',".$restricted_lab_id[0];
                                            $value5="$allocation_id,'$day','$date_start','$date_end',$slot_id2,$course2,($batch+7)%($no_divisions),$academic_year,$restricted_lab_id[1]";
                                            $value6="$allocation_id,'$day','$date_start','$date_end',$slot_id2,$course3,($batch+8)%($no_divisions),$academic_year,$restricted_lab_id[2]";
                                            $result4=db_insert(3,"laballocations",$value4);
                                            $result5=db_insert(3,"laballocations",$value5);
                                            $result6=db_insert(3,"laballocations",$value6);
                                                
                                        }
                                        $status="Allocated lab slots for first year Successfully";
                                            //echo $status;
                                        db_update( "courses" ," allocation_status=1"," cid=$course1 or cid=$course2 or cid=$course3");
                                    }
                                }
                            }
        
                    }
                }
            }
            else{
                $status="<b style={color='red'}>No. of slots avaialble(=$total_slots_available) is less than no. of slots required (=$total_slots_required)  </b>";
            }
            
        }
    }
            //first year allocation status
        echo "<br/>".$status;


    //allocate IOT courses
  $check_IOT_required=db_all("select * from courses where coursename like '%IOT%'
   or coursename like 'internet of things' or softwarereq like '%IOT%' 
   or softwarereq like 'Internet of Things' and academicyear=$academic_year and allocation_status=0 order by duration Desc");
   
   foreach($check_IOT_required as $IOTcourse){
        $sem=$IOTcourse['sem'];
        $dept=$IOTcourse['deptid'];
        $othercourses=db_all("select * from courses where deptid=$dept and sem=$sem and cid!=".$IOTcourse['cid']." and academicyear=$academic_year and allocation_status=0");
       
        $count_other_courses=count($othercourses);
     
        if($count_other_courses>0)
        {
            
            $no_divisions=$IOTcourse['divisions'];
            
            if($no_divisions==1)
            {
                $no_batches=2;
                $lab_capacity=$IOTcourse['strength']/2;
            }
            else
            {
                $no_batches=$no_divisions;
                $lab_capacity=60;
            }
            //get Lab 6 free slots
            $lab_slots_to_allocate=get_free_slots("7",$no_batches,$IOTcourse['duration'],$lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status);
    
            $cid=array();  //array of courses to be allocated 
            $cid[0]=$IOTcourse['cid'];
            $labs=array();
            $durations="";
           
            for($i=0;$i<$count_other_courses;$i++)
            {
                $cid[$i+1]=$othercourses[$i]['cid'];
            }
            
           
            //check the labs that belongs to the department of which the course is and the capacity of the lab required for each batch.
            $query_dept_labs=db_all("select * from `labs` where deptid=$dept and labid not in (1,11, 12,13, 16)");
            
            //also query the other department labs which is having the no_of_system >= batch strength
            $query_otherlabs=db_all("select *, no_of_systems-$lab_capacity as lab_capacity from labs where no_of_systems-$lab_capacity>0 and deptid!=$dept and labid not in (1,11, 12,13, 16) order by lab_capacity");
            

            if(count($query_dept_labs)>0)
            {
                   
                //array of labs to which the courses need to be allocated  
                for($i=0;$i<count($query_dept_labs);$i++)
                    $labs[$i]=$query_dept_labs[$i]['labid'];
                for($j=0;$j<count($query_otherlabs);$j++){
                        $labs[$i++]=$query_otherlabs[$j]['labid'];
                    }
                  
            }
            else{
                for($i=0;$i<count($query_otherlabs);$i++){
                    $labs[$i]=$query_otherlabs[$i]['labid'];
                }
            }
            $labs_to_allocate=get_labs($labs,$lab_slots_to_allocate, $lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status);
            $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
            $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
              
            for($b=0;$b<$no_batches;$b++){
                $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
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
                        $value="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[0],1,'$academic_year',7";
                        $result=db_insert(3,"laballocations",$value); 
                        if($slot==1||$slot==2||$slot==3){
                            $lab_3hrs_status["7".$day.$slot]=$IOTcourse['cid'];
                        }
                        else if($slot==4||$slot==5||$slot==6||$slot==7){
                            $lab_2hrs_status["7".$day.$slot]=$IOTcourse['cid'];
                        }
                        else if($slot==8||$slot==9){
                            $lab_4hrs_status["7".$day.$slot]=$IOTcourse['cid'];
                        }
                        for($c=0;$c<$count_other_courses;$c++){
                            
                            $no=$c+1;
                            $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$no],1,'$academic_year',$labs_to_allocate[$c]";
                            $result=db_insert(3,"laballocations",$value1);
                            if($slot==1||$slot==2||$slot==3){
                                $lab_3hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$no];
                                
                            }
                            else if($slot==4||$slot==5||$slot==6||$slot==7){
                                $lab_2hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$no];
                            }
                            else if($slot==8||$slot==9){
                                $lab_4hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$no];
                            } 
                        }

                    }
                    
                }
  
            }
            for($c=0;$c<count($cid);$c++){
                
                db_update("courses","allocation_status=1","cid=".$cid[$c]);
                echo "cid=$cid[$c] allocation_status=1";
            }
        } 
    }
    
    //lab allocation for BE higher semesters and Masters
  
  $getdeptids=db_all("select deptid from courses where deptid!=7 and coursecode!='CSEBYOD' and academicyear=$academic_year and allocation_status=0 group by (deptid) order by divisions DESC");
  foreach($getdeptids as $dept){
    $query="select *, count(*) as no_of_courses from courses where deptid!=7 and coursecode!='CSEBYOD' and academicyear=$academic_year and allocation_status=0 and deptid=".$dept['deptid']." group by(sem)";
    $getsems=db_all($query);
    for($i=0;$i<count($getsems);$i++){
        $week=["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        $No_of_days=$getsems[$i]['divisions'];
        $No_of_labs=$getsems[$i]['no_of_courses'];
        $sem=$getsems[$i]['sem'];
        $courses_for_sem=db_all("select * from courses where deptid!=7 and coursecode!='CSEBYOD' and 
                                    academicyear=$academic_year  and allocation_status=0 and 
                                    deptid=".$dept['deptid']." and sem=".$sem." order by duration");
       
        if($courses_for_sem[0]['divisions']==1)
        {
            $no_batches=2;
            $lab_capacity=$courses_for_sem[0]['strength']/2;
        }
        else
        {
            $no_batches=$courses_for_sem[0]['divisions'];
            $lab_capacity=60;
        }
        $cid=array();  //array of courses to be allocated 
        $labs=array();
        $durations=$courses_for_sem[0]['duration'];
        $different_durations=0;
        
        for($i=0;$i<count($courses_for_sem);$i++)
        {
            $cid[$i]=$courses_for_sem[$i]['cid'];
            if($durations!=$courses_for_sem[$i]['duration'])
                $durations=$courses_for_sem[$i]['duration'];
        }
        
        

        //check the labs that belongs to the department of which the course is and the capacity of the lab required for each batch.
        $query_dept_labs=db_all("select * from `labs` where deptid=".$dept['deptid']. " and labid not in (1,11, 12,13, 16)");
        //also query the other department labs which is having the no_of_system >= batch strength
        $query_otherlabs=db_all("select *, no_of_systems-$lab_capacity as lab_capacity from labs where no_of_systems-$lab_capacity>=0 and deptid!=".$dept['deptid']." and labid not in (1,11, 12,13, 16) order by lab_capacity");
       

        if(count($query_dept_labs)>0)
        {
             
            //array of labs to which the courses need to be allocated  
            for($i=0;$i<count($query_dept_labs);$i++)
                $labs[$i]=$query_dept_labs[$i]['labid'];
            for($j=0;$j<count($query_otherlabs);$j++){
                    $labs[$i++]=$query_otherlabs[$j]['labid'];
                }
        }
        else{
            for($i=0;$i<count($query_otherlabs);$i++){
                $labs[$i]=$query_otherlabs[$i]['labid'];
            }
        }

        for($labno=0;$labno<count($labs);$labno++){
            
                $lab_slots_to_allocate=get_free_slots($labs[$labno],$no_batches,$durations,$lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status);
                if(count($lab_slots_to_allocate)!=0)
                    break;
            }
      
        $otherlabs=array_slice($labs,$labno+1);
        
        $labs_to_allocate=get_labs($otherlabs,$lab_slots_to_allocate, $lab_2hrs_status,$lab_3hrs_status,$lab_4hrs_status);
            if($cid[0]==28 || $cid[1]==29){
                echo "labs to allocate";
                print_r($labs_to_allocate);
            }
        if($labs_to_allocate<count($cid)){
            echo "courses are more than slots available";
        }
        else{
        $slot=substr($lab_slots_to_allocate[0],strlen($lab_slots_to_allocate[0])-1);
        $get_lab_slots=db_all("select * from slot_list where slot_id=$slot");  
        for($b=0;$b<$no_batches;$b++){
            $day=substr($lab_slots_to_allocate[$b],1,strlen($lab_slots_to_allocate[$b])-2); 
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
                    $value="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[0],1,'$academic_year',$labs[$labno]";
                    $result=db_insert(3,"laballocations",$value); 
                    if($slot==1||$slot==2||$slot==3){
                        $lab_3hrs_status[$labs[$labno].$day.$slot]=$cid[0];
                    }
                    else if($slot==4||$slot==5||$slot==6||$slot==7){
                        $lab_2hrs_status[$labs[$labno].$day.$slot]=$cid[0];
                    }
                    else if($slot==8||$slot==9){
                        $lab_4hrs_status[$labs[$labno].$day.$slot]=$cid[0];
                    }
                    for($c=1;$c<count($cid);$c++){
                    
                        $value1="$allocation_id,'$day','$date_start','$date_end',$slot,$cid[$c],0,'$academic_year',$labs_to_allocate[$c]";
                         $result=db_insert(3,"laballocations",$value1);
                        if($slot==1||$slot==2||$slot==3){
                            $lab_3hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$c];
                            echo "$ lab_3hrs_status [ $labs_to_allocate[$c].$day.$slot ]=$cid[$c]";
                        }
                        else if($slot==4||$slot==5||$slot==6||$slot==7){
                            $lab_2hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$c];
                        }
                        else if($slot==8||$slot==9){
                            $lab_4hrs_status[$labs_to_allocate[$c].$day.$slot]=$cid[$c];
                            
                        } 
                        
                    }

                }
                
            }

        }
        
        for($c=0;$c<count($cid);$c++){
            db_update("courses","allocation_status=1","cid=".$cid[$c]);
        }
    }
    
        
    } 
}

       
    
$query_unallocated_courses=db_all("select * from courses where allocation_status=0");
if($query_unallocated_courses>0){
    echo count($query_unallocated_courses)." are not allocated any labs";
}
else{
    db_update("academic_year","status=1","id=".$academic_year);
}
    
        
    
  


     
    
?>