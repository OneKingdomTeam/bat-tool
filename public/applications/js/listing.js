
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

        this.editButtons = document.querySelectorAll('.thet-listing-edit-button'); 
        this.popupHtmlContent = '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background-color: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;" class="thet-interactive-form-popup-wrapper"> <div class="thet-interactive-form-popup-window is-flex is-flex-direction-column is-justify-content-space-evenly p-5" style="width: 550px; height: 300px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0px 0px 2rem 0px rgba(0,0,0,0.75);"> <div class="container thet-interactive-form-popup-title"> <h3 class="title is-3">Title placeholder</h3> </div><div class="container thet-interactive-form-popup-message"> <div class="content has-text-centered"> Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat. </div></div><div class="thet-interactive-form-popup-buttons" style="width: 100%"> <div class="columns"> <div class="column"> <button class="button is-warning is-fullwidth thet-interactive-form-popup-warning-button">Close</button> </div><div class="column"> <button class="button is-success is-fullwidth thet-interactive-form-popup-warning-button">Open anyway</button> </div></div></div></div>';
        this.requestedUrl = "";

        self = this;
        this.editButtons.forEach( function( button ){
            self.hookTimeCheck( button );
        } );

        this.$reportButtons = $j('a.view-report-btn')
        this.$reportButtons.on('click', (event) => { this.handleReportButtonClick( event ) });
    }

    async updateSaveTime(){

        let newData = await this.getLastSaveTimes();

        Object.entries( newData ).forEach( ( entry ) => {

            const[key, value] = entry;

            let wrapper = document.querySelector('.application-id-' + key.toString());
            let timeField = wrapper.querySelector('.thet-last-save-time span');

            let formattedTime = this.formatUnixTime( parseInt( value['last_save_time'] ) );

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

    hookTimeCheck( button ){

        let self = this;
        button.addEventListener('click', function( event ){

            event.preventDefault();
            var clickedBtn = event.target;
            var clickedBtnUrl = clickedBtn.closest('a').href;
            self.requestedUrl = clickedBtnUrl;
            var lastSaveTime = parseInt( clickedBtn.closest('.columns').querySelector('.thet-last-save-time').getAttribute('data-last-save-time') );
            var timeSinceLastSave = parseInt( ( Date.now() - lastSaveTime * 1000 ) / 1000 );

            if ( timeSinceLastSave >= 30 ){
                window.location.href = clickedBtnUrl;
            } else {
                self.showPopup( true, "Warning", "Seems that someone have the application open right now, since time of the last save is less than 30 seconds. Try again later." );
            }


        } );

    }

    showPopup( visible, title, message, btnToListing, btnReload ){

        let self = this;

        if ( visible == false ){

            let popupWrapper = document.querySelector('.thet-interactive-form-popup-wrapper');
            if ( popupWrapper !== null ){
                popupWrapper.outerHTML = "";
            }

            return;
            
        };

        if ( visible == true ){

            const parser = new DOMParser();
            const parsedHtml = parser.parseFromString( this.popupHtmlContent, 'text/html');
            const actuallContent = parsedHtml.querySelector('.thet-interactive-form-popup-wrapper').cloneNode(true);

            actuallContent.querySelector('.thet-interactive-form-popup-title h3').innerText = title;
            actuallContent.querySelector('.thet-interactive-form-popup-message .content').innerText = message;
            actuallContent.querySelector('.button.is-warning').addEventListener('click', function(){ self.showPopup( false ); });
            actuallContent.querySelector('.button.is-success').addEventListener('click', function(){ window.location.href = self.requestedUrl;});

            if ( btnToListing == false ) {
                actuallContent.querySelector('.button.is-warning').closest('.column').outerHTML = "";
            }
            if ( btnReload == false ) {
                actuallContent.querySelector('.button.is-success').closest('.column').outerHTML = "";
            }

            document.body.appendChild( actuallContent );
        };

    }

    handleReportButtonClick( event ){

        let element = event.target;

        let overlay = $j('<div>', {
            'style':'z-index: 100; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: #ffffff33; backdrop-filter: blur(10px); display: flex; justify-content: center; align-items: center; cursor: pointer;'
        });

        let popupWindow = $j('<div>', {
            'class':'box is-flex is-flex-direction-column content has-text-centered',
            'style':'z-index: 200; max-width: 80%; width: 560px; min-height: 320px; cursor: default; position: fixed; top: 50vh; left: 50vw; transform: translateX(-50%) translatey(-50%)'
        });

        let header = $j('<h3>', {
            'class':'title is-3',
            'html':'View your report'
        });

        let message = $j('<p>', {
            'html': 'Make sure to copy the password first before you will go to the report page, since the password is always required to view them',
        });

        let input = $j('<input>', {
            'value': $j( event.target ).data().report_password,
            'class': 'input has-text-centered mx-auto mt-5',
            'type' : 'text',
            'style': 'width: 350px; max-width: 75%;'
        });

        let buttons = $j( '<div>', {
            'class': 'buttons'
        });

        let button = $j('<a/>', {
            'class': 'button is-link mx-auto mt-4',
            'html' : 'Open Report',
            'href' : $j( event.target ).data().report_url,
            'target': '_blank'
        });

        input.click( ()=>{
            input.select();
            document.execCommand('copy');
        } );

        header.appendTo( popupWindow );
        message.appendTo( popupWindow );
        input.appendTo( popupWindow );
        button.appendTo( buttons );
        buttons.appendTo( popupWindow );

        overlay.appendTo('body');
        popupWindow.appendTo('body');

        overlay.click( ()=>{ overlay.remove(); popupWindow.remove() });

    }

}

var $j;
var thetListing;
jQuery(document).ready( function() {

    $j = jQuery.noConflict();
    thetListing = new ListingController();

});
