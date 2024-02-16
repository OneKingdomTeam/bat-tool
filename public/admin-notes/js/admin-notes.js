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
        this.getAndPrepareDataToSidebar();

    }

    async getAndPrepareDataToSidebar(){

        this.notesData = await this.getNotesFromDatabase();

        if ( this.sideBar === undefined ){
            this.createSideBar();
        } else {
            this.sideBar.innerHTML = "";
        }

        if ( this.floatingIcon === undefined ) {
            this.createFloatingIcon();
        } 

        this.prepopulateContent();
        this.attachEventListeners();

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

    async lockNotesFromOtherEditors( lockStatus ) {

        let data = new FormData();

        if ( lockStatus === true ) {
            data.append( 'action', 'thet_ajax_lock_note');
        } else if ( lockStatus === false ) {
            data.append( 'action', 'thet_ajax_unlock_note');
        } else {
            throw new Error('LockStatus variable was not provided for the function');
        }

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

            let noteTopicHeaderWraper = document.createElement( 'div' );
            noteTopicHeaderWraper.classList.add( 'thet-admin-notes-topic-header-wrapper' );

            let noteTopicHeader = document.createElement( 'h3' );
            noteTopicHeader.classList.add( 'thet-admin-notes-topic-header' );
            noteTopicHeader.innerText = values.title;

            let noteTopicEditIcon = document.createElement( 'div' );
            noteTopicEditIcon.classList.add('thet-admin-notes-topic-edit-icon');

            let noteTopicContent = document.createElement( 'div' );
            noteTopicContent.classList.add( 'thet-admin-notes-topic-content' );
            noteTopicContent.innerHTML = this.notesData['note' + noteTopicWrapper.dataset.note_id ];

            
            noteTopicWrapper.appendChild( noteTopicHeaderWraper );
            noteTopicHeaderWraper.appendChild( noteTopicHeader );
            noteTopicHeaderWraper.appendChild( noteTopicEditIcon );
            noteTopicWrapper.appendChild( noteTopicContent );

            this.sideBar.appendChild( noteTopicWrapper );
        };

    }


    attachEventListeners() {

        this.sideBar.addEventListener('click', event => {
            
            if( event.target.classList.contains('thet-admin-notes-topic-edit-icon') ){
                this.handleTopicEditIconClick( event );
            }

            if( event.target.classList.contains('thet-admin-notes-topic-editor-save-btn') ){
                this.handleTopicEditSaveBtnClick( event );
            }

            if( event.target.classList.contains('thet-admin-notes-topic-editor-cancel-btn') ){
                this.handleTopicEditCancelBtnClick( event );
            }

        } );

    }

    handleTopicEditIconClick( event ) {

        let topicWrapper = event.target.closest('.thet-admin-notes-topic-wrapper');
        this.openTopicEditor( topicWrapper );

    }

    handleTopicEditSaveBtnClick( event ){

        let content = this.topicEditor[0].getContent();
        let openedNoteId = event.target.closest('.thet-admin-notes-topic-wrapper').dataset.note_id;
        this.notesData['note' + openedNoteId] = content;

        this.saveNotesToDatabase();

        this.destroyTopicEditors();

        setTimeout(() => {
            this.getAndPrepareDataToSidebar();
        }, 250);


    }

    handleTopicEditCancelBtnClick( event ){

        this.destroyTopicEditors();
        this.getAndPrepareDataToSidebar();

    }

    openTopicEditor( topicWrapper ){

        this.hideAllOtherTopics( topicWrapper );
        this.replaceTopicContentWithEditor( topicWrapper );

    }

    destroyTopicEditors() {

        console.log('Current list of editors: ', tinymce.editors );

        while( this.topicEditor.length !== 0 ) {
            console.log('Removing editor: ', tinymce.editors[0] );
            this.topicEditor[0].remove();
        }

        console.log('After deletion: ', tinymce.editors );


    }

    hideAllOtherTopics( topicWrapper ){

        let allTopics = this.sideBar.querySelectorAll('.thet-admin-notes-topic-wrapper');

        allTopics.forEach( topic => {
            
            if ( topic.dataset.note_id === topicWrapper.dataset.note_id ) {
                return;
            } else {
                topic.classList.add('is-display-none');
            }

        } );

    }

    async replaceTopicContentWithEditor( topicWrapper ){
        
        let topicContentWrapper = topicWrapper.querySelector('.thet-admin-notes-topic-content');
        topicContentWrapper.innerText = '';

        let editor = document.createElement('textarea');
        editor.classList.add('thet-admin-notes-topic-textarea');

        topicContentWrapper.appendChild( editor );

        let buttonWrapper = document.createElement('div');
        buttonWrapper.classList.add('thet-admin-notes-topic-editor-button-wrapper');

        let cancelButton = document.createElement('div');
        cancelButton.classList.add('thet-admin-notes-topic-editor-cancel-btn');
        cancelButton.innerText = 'Cancel';

        let saveButton = document.createElement('div');
        saveButton.classList.add('thet-admin-notes-topic-editor-save-btn');
        saveButton.innerText = 'Save';

        buttonWrapper.appendChild( cancelButton );
        buttonWrapper.appendChild( saveButton );

        topicContentWrapper.appendChild( buttonWrapper );

        this.topicEditor = await tinymce.init({
            selector: '.thet-admin-notes-topic-textarea',
            themes: "modern",
            menubar: false,
            plugins: 'lists',
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            height: '60vh',
        });

        this.topicEditor[0].setContent( this.notesData['note' + topicWrapper.dataset.note_id ] );

    }

}

const thetNotes = new AdminNotes();

window.addEventListener( 'load', ()=>{

    thetNotes.init();

});
