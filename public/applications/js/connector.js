class Connector {

    constructor( nonce ){

        this.nonce = nonce;
        this.ajaxUrl = '/wp-admin/admin-ajax.php';
        this.applicationId = this.extractRequiredPostId();
        this.isForceOpen = this.extractForceOpen();
        this.applicationData = null;
        this.recentResponse = null;
        this.recentResponseCode = null;
        this.recentSaveTime = null;

        this.init();

    };

    async getApplicationData( sessionKey ){

        const data = await this.fetchApplicationData( this.applicationId, sessionKey ).then( (data) => { return data });
        this.applicationData = data;
        this.recentResponse =  data;

        if( this.recentResponseCode !== 200 ){
            thetInterface.showPopup(true, this.recentResponse.response, this.recentResponse.message );
        }

        return this.applicationData;

    }

    async saveApplicationData( applicationData, sessionKey ){

        thetInterface.showSavingProgress( true );
        const data = await this.fetchSaveApplicationData( this.applicationId, applicationData, sessionKey).then( (data) => { return data });
        this.recentResponse =  data;

        if( this.recentResponseCode !== 200 ){
            thetInterface.showPopup(true, this.recentResponse.response, this.recentResponse.message );
            thetInterface.autoSaving( false );
        }

        if( this.recentResponseCode === 200 ){
            thetInterface.showSavingProgress( false );
        }

        this.recentSaveTime = Math.round( Date.now() / 1000 );

        return this.recentResponse;

    }

    async fetchApplicationData( applicationId, sessionKey ){

        let data = new FormData();

        data.append( 'action', 'get_application_data');
        data.append( 'application_id', parseInt( applicationId ));
        data.append( 'session_key', sessionKey );
        if ( this.isForceOpen == true ){
            data.append( 'force_open', true );
        }
        data.append( 'nonce', thetAjax.nonce );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data});
        this.recentResponseCode = response.status;
        let responseData = response.json();
        return responseData;

    }

    async fetchSaveApplicationData( applicationId,  applicationData, sessionKey ){

        let data = new FormData();

        data.append( 'action', 'save_application_data');
        data.append( 'application_id', parseInt( applicationId ));
        data.append( 'data', JSON.stringify( applicationData ));
        data.append( 'session_key', sessionKey );
        data.append( 'nonce', thetAjax.nonce );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data});

        this.recentResponseCode = response.status;
        let responseData = response.json();
        return responseData;

    };

    extractRequiredPostId(){

        let params = new URL(document.location).searchParams;
        let parsedApplicationId = params.get("application_id");
        return parsedApplicationId;

    };

    extractForceOpen(){

        let params = new URL(document.location).searchParams;
        let parsedForceOpen = params.get("force_open");

        // Expanded for option to do this through the localStorage as well
        let localStorageForceOpen = window.localStorage.getItem('thet_force_open');

        window.localStorage.removeItem('thet_force_open');
        
        if( parsedForceOpen === "true" || localStorageForceOpen === "true" ){
            return true;
        } else {
            return false;
        }

    };

    init(){

        let gotApplicationId;
        this.applicationId === null ? gotApplicationId = false : gotApplicationId = true;

        if ( gotApplicationId === false ){

            this.applicationIdExists = false;
            
        } else {

            this.applicationIdExists = true;

        };

    }
    

};
