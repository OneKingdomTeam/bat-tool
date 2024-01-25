class AdminNotes {

    constructor () {

        this.ajaxUrl = '/wp-admin/admin-ajax.php';
        this.error = 0;
        this.errorNote = '';

        // Checks for existence of nonce variable otherwise exit the function
        if ( typeof( adminNotesNonce ) == 'undefined' ){

            this.error = 1;
            this.errorNote = 'Nonce is not set';
            console.log (this.errorNote);
            return;

        } else {

            this.ajaxNonce = adminNotesNonce;

        }

        


    }


}
