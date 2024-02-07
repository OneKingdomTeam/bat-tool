class AdminNotes {

    constructor () {

        this.error = 0;
        this.errorNote = '';
        this.applicationId = this.extractApplicationIdFromUrl();
        this.sessionId = this.generateSessionKey();

        this.checkForLocalization();

        // Fetch response codes and messages
        this.recentResponseCode;
        this.recentResponseMessage;

        // Get's the notes from database
        this.notesData = this.getNotesFromDatabase();

    }

    checkForLocalization() {

        if ( typeof( adminNotesLoc ) == 'undefined' ){

            throw new Error('Localization of AdminNotes script not found.');

        } else {

            this.ajaxNonce = adminNotesLoc.nonce;
            this.ajaxUrl = adminNotesLoc.ajax_url;

        }

    }

    extractApplicationIdFromUrl() {

        let params = new URL(document.location).searchParams;
        let parsedApplicationId = params.get("application_id");
        return parsedApplicationId;

    }

    generateSessionKey(){

        let length = 32;
        const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        let randomString = '';

        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            randomString += characters.charAt(randomIndex);
        }

        return randomString;

    }

    async getNotesFromDatabase() {

        let data = new FormData();

        data.append( 'action', 'thet_ajax_get_notes');
        data.append( 'application_id', parseInt( this.applicationId ));
        data.append( 'session_key', this.sessionId );
        data.append( 'nonce', this.ajaxNonce );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data});

        this.recentResponseCode = response.status;
        let responseData = response.json();
        return responseData;
        
    }

}


window.addEventListener( 'load', ()=>{

    const thetNotes = new AdminNotes();

} );
