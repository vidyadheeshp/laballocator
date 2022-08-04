<?php

if(isset($_POST['l1'])){
   // $query_text = "AND la.labid=".$lab_id;
    $lab_id = $_POST['l1'];
  }
  
  $calender_url = 'calender_events.php?lab_id='.$lab_id.'';
  //echo $lab_id;

echo '<div class="box box-primary" >
               <div class="box-body no-padding">
                 <!-- THE CALENDAR -->
                 <div id="calendar"></div>
               </div>
               <!-- /.box-body -->
             </div>
             <!-- /. box -->';
echo '<script>$(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesnt need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the elements text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data("eventObject", eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }

    ini_events($("#external-events div.external-event"));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    var lab_id_choosen = $("#lab_list").val();
    //var calender_content_url = ""
    
    $("#calendar").fullCalendar({
      header: {
        left: "prev,next today",
        center: "title",
        right: "month,agendaWeek,agendaDay"
      },
     defaultView: "agendaWeek",
      buttonText: {
        today: "today",
        month: "month",
        week: "week",
        day: "day"
      },
      hiddenDays: [ 0 ],
      minTime: "08:00:00",
      maxTime: "18:00:00",
      //Random default events
      events : "'.$calender_url.'",
      //viewing the clicked event
         eventClick:  function(event, jsEvent, view) {
         //alert(event.id);
           // $("#booked_by_name").text(event.title);
           // $("#booked_by_date").text(event.start.format());
        
            //$("#eventUrl").attr("href",event.url);
            $("#view_slot_alloted").modal();
          //for loading the booked seva
          var view_url = "ajax/view_slot_details.ajax.php";
      
         $("div #loading_image").removeAttr("style");
         $.post(
               view_url,{
                  p1 : event.id
               },
               function(data,status){
                  
                     $(".slot_slloted_data").html(data);
                     
                     // setTimeout(function () {
                           // window.location.reload();
                           // }, 2000);
               });
         //alert("seva booking modal";
     },
      //editable: true,
     
    });

   
  });</script>';
