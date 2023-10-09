
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


class ListingController {

    constructor() {

        let self = this;
        this.fetchInterval = setInterval( function(){
            self.updateSaveTime();
        }, 5000 );

    }

    async updateSaveTime(){

        let newData = await this.getLastSaveTimes();

        let self = this;
        Object.entries( newData ).forEach( function( entry ){

            const[key, value] = entry;

            let wrapper = document.querySelector('.application-id-' + key.toString());
            let timeField = wrapper.querySelector('.thet-last-save-time span');

            let formattedTime = self.formatUnixTime( parseInt( value['last_save_time'] ) );

            timeField.innerHTML = formattedTime;

        } );

    

    }

    async getLastSaveTimes(){

        let response = await fetch( '/wp-admin/admin-ajax.php?' + new URLSearchParams({ action: 'get_recent_update_time' }) );
        let data = await response.json();
        this.latestResponse = data;
        this.latestResponseTime = Date.now();
        return data;

    }

    formatUnixTime( unixTime ){
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"]; 
        // Create a new JavaScript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds.
        var date = new Date( parseInt( unixTime ) * 1000);
        // Hours part from the timestamp
        var hours = date.getHours();
        // Minutes part from the timestamp
        var minutes = "0" + date.getMinutes();
        // Seconds part from the timestamp
        var seconds = "0" + date.getSeconds();

        // Will display time in 10:30:23 format
        var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);

        formattedTime += " - " + date.getDate() + " " + month[date.getUTCMonth()] + " " + date.getFullYear();

        return formattedTime;

    }

}

const thetListing = new ListingController();
