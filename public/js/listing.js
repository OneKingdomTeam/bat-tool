
let timeFields = document.querySelectorAll('.thet-last-save-time span');
window.addEventListener('DOMContentLoaded', function(){
    timeFields.forEach( function( timeField ){
        thetConvertTimeToLocal( timeField );
    } );
} );

function thetConvertTimeToLocal( timeField ){
    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"]; 
    // Create a new JavaScript Date object based on the timestamp
    // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date( parseInt( timeField.innerText ) * 1000);
    // Hours part from the timestamp
    var hours = date.getHours();
    // Minutes part from the timestamp
    var minutes = "0" + date.getMinutes();
    // Seconds part from the timestamp
    var seconds = "0" + date.getSeconds();

    // Will display time in 10:30:23 format
    var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

    formattedTime += " - " + date.getDate() + " " + month[date.getUTCMonth()] + " " + date.getFullYear();

    timeField.innerText = formattedTime;        
}
