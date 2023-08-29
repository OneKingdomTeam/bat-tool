class Connector {

    constructor( nonce ){

        this.nonce = nonce;
        this.ajaxUrl = '/wp-admin/admin-ajax.php';
        this.applicationId = this.extractReuiredPostId();
        this.applicationData = null;

        this.init();

    };

    async getApplicationData(){

        const data = await this.fetchApplicationData( this.applicationId ).then( (data) => { return data });
        this.applicationData = data;
        return this.applicationData;

    }

    async saveApplicationData( applicationData ){

        const data = await this.fetchSaveApplicationData( this.applicationId, applicationData ).then( (data) => { return data });
        this.recentResponse =  data;
        return this.recentResponse;

    }

    async fetchApplicationData( applicationId ){

        let data = new FormData();

        data.append( 'action', 'get_application_data');
        data.append( 'application_id', parseInt( applicationId ));
        data.append( 'nonce', thetAjax.nonce );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data});
        return response.json();

    }

    async fetchSaveApplicationData( applicationId,  applicationData ){

        let data = new FormData();

        data.append( 'action', 'save_application_data');
        data.append( 'application_id', parseInt( applicationId ));
        data.append( 'data', JSON.stringify( applicationData ));
        data.append( 'nonce', thetAjax.nonce );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data});
        return response.json();

    };

    extractReuiredPostId(){

        let params = new URL(document.location).searchParams;
        let parsedApplicationId = params.get("application_id");
        return parsedApplicationId;

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
