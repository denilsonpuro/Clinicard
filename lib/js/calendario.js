/* 
 * File creato da Carlo Centofanti
 */


    
    
$(document).ready(function() {
    $('#calendar').fullCalendar({
        googleCalendarApiKey: 'AIzaSyAd1vx_92E0l2jbBPRk6u-P-Nz8S_Ks3lM',
        defaultView: 'agendaWeek',
        handleWindowResize: 'true',
        theme: true,
        allDaySlot: false,
        slotDuration: '01:00:00', //60 minutes
        hiddenDays: [0,6] , //do not show sat and sun
        
        
        //time shown on the vertical index
        minTime: '08:00:00',
        maxTime: '21:00:00',
        
        slotEventOverlap: 'false',
        
        header: {
            center:'title',
            left: 'month,agendaWeek,agendaDay',
            right:'today prev,next'
        },
        themeButtonIcons: 'false',
        
        //selection settings
        unselectAuto: 'false',
        selectable: 'true',
        selectHelper:  'true', //draw a "placeholder" event while the user is dragging
        // unselectCancel:'.mu-form', //http://fullcalendar.io/docs/selection/unselectCancel/
        selectOverlap:'true', //verificare se funziona quando creo eventi. dovrebbe impedi di selezionare sopra gli eventi
        selectConstraint:{
            start: '08:00:00', //start da do clicchi
            end: '19:00:00'     //end a do clicchi +1... come se fa??
            
        },
        select: function( start, end, jsEvent, view ){
            var dataInizio=$.fullCalendar.moment(start.format());
            var dataFine=$.fullCalendar.moment(end.format());
            alert("inizio:"+dataInizio+"   fine:"+dataFine);
            var event=[{
                title: 'Event1',
                start: dataInizio,
                end: dataFine
            }];
        
            $('#calendar').fullCalendar( 'renderEvent', event, true);
        },
        
        
        
        
        
        dayClick: function(date, jsEvent, view) {

            //alert('Clicked on: ' + date.format());
            var dataInizio=$.fullCalendar.moment(date.format());
            var stick=true;
            var event=[{
                title: 'Event1',
                start: dataInizio
            }];
        
            
            $('#calendar').fullCalendar( 'renderEvent', event, stick);

//            alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
//
//            alert('Current view: ' + view.name);

            // change the day's background color just for fun
            //$(this).css('background-color', 'red');

        },
        
        //event settings
        allDayDefault:'false',
        eventOverlap: 'false',
        
        eventSources: [{
            events: [
                {
                    title  : 'event1, no end',
                    start  : '2015-01-20T12:30:00',
                    allDay : false
                },
                {
                    title  : 'event2 con end',
                    start  : '2015-01-22T12:30:00',
                    end    : '2015-01-22T13:30:00',
                    allDay : false
                },
                {
                    title  : 'event3 con ora',
                    start  : '2015-01-21T12:30:00',
                    allDay : false // will make the time show
                }
            ],
            color: 'black',     // an option!
            textColor: 'yellow' // an option!
            
        }
        
        
        ]
        
       
        
        
        
    });
    
    $('#calendar').hide();
    $('#calendar').fadeIn(1000);
  
    
    
});




