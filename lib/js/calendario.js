/* 
 * File creato da Carlo Centofanti
 */


$(mainFunction);

function mainFunction(){
    createCalendar();
}

function createCalendar(){
    $('#calendar').fullCalendar(calendarConfig);
}


function addTemporaryEvent(event){
    $('#calendar').fullCalendar( 'renderEvent', event, true);
}

var calendarConfig=({
   
    //some configuration first
    defaultView: 'agendaWeek',
    handleWindowResize: 'true',
    allDaySlot: false, // do not visualize the all day cell
    slotDuration: '00:30:00', //visualize 30 minutes per cell
    hiddenDays: [0,6] , //do not show sat and sun
    
    //use custom theme
    theme: true, 
    themeButtonIcons: true,
        
    //time shown on the vertical index
    minTime: '08:00:00',
    maxTime: '21:30:00',
    
    slotEventOverlap: 'true', //http://fullcalendar.io/docs/agenda/slotEventOverlap/
    header: {
            center:'title',
            left: 'agendaWeek,agendaDay',
            right:'today prev,next'
        },
    
    //selection settings
    selectable: 'true',
    unselectAuto: false,
    unselectCancel:'html-id',// an id wich do not auto cancel
    selectHelper: true, //draw a "placeholder" event while the user is dragging
    selectOverlap:false,//verificare se funziona quando creo eventi. dovrebbe impedi di selezionare sopra gli eventi
    selectConstraint:{
        start: '08:00:00', //start da do clicchi
        end: '19:00:00'     //end a do clicchi +1... come se fa??
    },
    eventClick: function( event, jsEvent, view ){
        addTemporaryEvent(event);
    },
});

        
     