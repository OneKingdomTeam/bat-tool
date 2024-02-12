class AdminNotes {

    constructor () {

        this.error = 0;
        this.errorNote = '';
        this.applicationId = this.extractApplicationIdFromUrl();
        this.sessionId = this.generateSessionKey();

        this.checkForLocalization();

    }

    async init(){

        // Fetch response codes and messages
        this.recentResponseCode;
        this.recentResponseMessage;

        // Get's the notes from database
        this.notesData = await this.getNotesFromDatabase();
        this.createSideBar();
        this.createFloatingIcon();

        this.prepopulateContent();

    }

    checkForLocalization() {

        if ( typeof( adminNotesLoc ) == 'undefined' ){

            throw new Error('Localization of AdminNotes script not found.');

        } else {

            this.ajaxNonce = adminNotesLoc.nonce;
            this.ajaxUrl = adminNotesLoc.ajax_url;
            this.notesMap = adminNotesLoc.notes_map;

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
            body: data})

        this.recentResponseCode = response.status;

        let responseData = await response.json();

        this.recentResponseData = responseData.data;
        this.notesData = responseData.data;
        return responseData.data;
        
    }

    async saveNotesToDatabase() {

        let data = new FormData();

        data.append( 'action', 'thet_ajax_update_note');
        data.append( 'application_id', parseInt( this.applicationId ));
        data.append( 'session_key', this.sessionId );
        data.append( 'nonce', this.ajaxNonce );
        data.append( 'notes_data', JSON.stringify(this.notesData) );

        const response = await fetch( '/wp-admin/admin-ajax.php' , {
            method: "POST", // *GET, POST, PUT, DELETE, etc.
            body: data})

        this.recentResponseCode = response.status;

        let responseData = await response.json();

        this.recentResponseData = responseData.data;
        return responseData.data;

    }

    createSideBar(){
        
        this.sideBar = document.createElement( 'div' );
        this.sideBar.classList.add( 'thet-admin-notes-sidebar' );
        this.sideBar.classList.add( 'is-display-none' );
        document.body.appendChild( this.sideBar );

    }

    createFloatingIcon(){

        this.floatingIcon = document.createElement( 'div' );
        this.floatingIcon.classList.add( 'thet-admin-notes-floating-icon' );
        this.floatingIcon.addEventListener( 'click', (event) => {
            if ( this.sideBar.classList.contains('is-display-none') ){
                this.showSideBar(true);
                event.target.classList.add('is-open');
            } else {
                this.showSideBar(false);
                event.target.classList.remove('is-open');

            }
        });
        document.body.appendChild( this.floatingIcon );

    }

    showSideBar( show ){

        if( show === true ){
            this.sideBar.classList.remove( 'is-display-none' );
        }

        if( show === false ){
            this.sideBar.classList.add( 'is-display-none' );
        }

    }

    prepopulateContent(){

        for (const [key, values] of Object.entries(thetQuestions.questions)) {
            let noteTopicWrapper = document.createElement( 'div' );
            noteTopicWrapper.classList.add( 'thet-admin-notes-topic-wrapper' );
            noteTopicWrapper.classList.add( 'note-question-id-' + values.question_id.toString() );
            noteTopicWrapper.dataset.question_id = values.question_id;

            let foundMapping = this.notesMap.find( obj => obj.question_id === values.question_id.toString() );

            if ( foundMapping === undefined ) {
                throw new Error ('Question ID not found in notesMap');
            }

            noteTopicWrapper.dataset.note_id = parseInt( foundMapping.note_id ).toString().padStart(2, '0');

            let noteTopicHeader = document.createElement( 'h3' );
            noteTopicHeader.classList.add( 'thet-admin-notes-topic-header' );
            noteTopicHeader.innerText = values.title;

            let noteTopicContent = document.createElement( 'div' );
            noteTopicContent.classList.add( 'thet-admin-notes-topic-content' );
            noteTopicContent.innerText = this.notesData['note' + noteTopicWrapper.dataset.note_id ];

            noteTopicWrapper.appendChild( noteTopicHeader );
            noteTopicWrapper.appendChild( noteTopicContent );

            this.sideBar.appendChild( noteTopicWrapper );
        };



    }


}

const thetNotes = new AdminNotes();

window.addEventListener( 'load', ()=>{

    thetNotes.init();

});
