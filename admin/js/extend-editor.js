class EditorNotes {
     constructor(){
        this.Settings = {};
        this.Settings.grabPostHeaderSettingsMaxAttempts = 5;
        this.Settings.iconCreationMaxAttempts = 10;


        console.log('Initialized batEditorExtentnion');
        this.UIElements = {};

        this.Temp = {};
        this.Temp.grabPostHeaderSettingsAttempts = 0;
        this.Temp.iconCreationAttempts = 0;

        this.selectPostHeaderSettings();
        this.localizeThis();
        this.loadApplicaitonNotes();
        this.createNotesIconWhenReady();


    }

    localizeThis(){
        if ( !batNotesEditorLocalization ){
            throw new Error('Localization object not found!');
        } else {
            for (const [key, value] of Object.entries(batNotesEditorLocalization)){
                this.Settings[key] = value;
            }
        }
    }

    createNotesIcon (){

        if ( this.UIElements.postHeaderSettings !== null ){

            let notesIcon = document.createElement('div');
            notesIcon.classList.add('components-button');
            notesIcon.setAttribute('style', 'min-width: 30px; border: solid 2px #001540; border-radius: 0.15rem; cursor: pointer; display: flex; justify-content: center; align-items: center;');
            notesIcon.innerText = 'Show notes';

            let innerIcon = document.createElement('img');
            innerIcon.src = this.Settings.pluginDirUrl + 'public/media/notes-icon.svg';
            innerIcon.setAttribute('style', 'width: 20px; height: 20px; margin-left: 6px;');

            notesIcon.appendChild( innerIcon );

            notesIcon.addEventListener('click', (event) => {
                this.handleShowNotesClick(event);
            })

            this.UIElements.postHeaderNotesIcon = notesIcon;
            this.UIElements.postHeaderSettings.insertBefore( this.UIElements.postHeaderNotesIcon, this.UIElements.postHeaderSettings.firstElementChild )

        } else {

            throw new Error('Unable to locate edit-post-header__settings element');

        }
    }

    createNotesIconWhenReady(){

        if ( this.Settings.applicationId === "" ){
            console.log('No application ID set. Going without it');
            return;
        }

        if ( this.Temp.iconCreationAttempts >= this.Settings.iconCreationMaxAttempts ) {
            throw new Error('Coudn\'t create an icon.');
        }

        if ( this.UIElements.postHeaderSettings === undefined || this.UIElements.postHeaderSettings === null ){
            setTimeout(()=>{
                this.createNotesIconWhenReady();
            }, 250);
        } else {
            this.createNotesIcon();
        }

    }


    showAvailableApplicationsSelector( availableApplications ){

        let overlay = document.createElement('div');
        overlay.setAttribute('style', 'z-index: 9999; width: 100vw; height: 100vh; background-color: #ffffff99; backdrop-filter: blur(10px); position: fixed; top: 0px; left: 0px; display: flex; justify-content: center; align-items: center;');

        let window = document.createElement('div');
        window.setAttribute('style', 'background-color: #ffffff; border-radius: 0.5rem; padding: 3rem; box-shadow: 0rem 0rem 1rem #00000033; min-width: 380px; min-height: 160px; display: flex; flex-direction: column; align-items: center; justify-content: center;');

        let title = document.createElement('h4');
        title.innerText = 'Select application';
        title.style.fontSize = '1.5rem';
        title.style.fontWeight = 'bold';
        let subTitle = document.createElement('p');
        subTitle.style.fontSize = '1.15rem';
        subTitle.innerText = 'It will allow you to view the notes and have them right here during the creation of the report';

        let select = document.createElement('select');
        select.setAttribute('style', 'min-width: 19rem; font-size: 1.15rem; padding: 0.35rem; text-align: center; margin-bottom: 18px;');
        select.id = 'availableApplications';

        let defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Skip for now';
        select.appendChild( defaultOption );

        availableApplications.forEach(application => {
            let option = document.createElement('option');
            option.value = application['ID'];
            option.text = application['title'];
            select.appendChild( option );
        });

        let button = document.createElement('button');
        button.innerText = 'Save';
        button.setAttribute('style', 'font-size: 1.15rem; color: #f2f2f2; padding: 0.5rem 5rem; background-color: #001540; border: solid 2px black; border-radius: 0.3rem; min-width: 19rem; cursor: pointer;');
        button.addEventListener('click', () => {
            this.handleSelectFromAvailableApplications( select );
        });

        window.appendChild( title );
        window.appendChild( subTitle );
        window.appendChild( select );
        window.appendChild( button );

        overlay.appendChild( window );

        this.UIElements.availableApplicationsOverlay = overlay;
        document.body.appendChild( this.UIElements.availableApplicationsOverlay );

    }

    selectPostHeaderSettings(){

        if ( this.Settings.grabPostHeaderSettingsMaxAttempts <= this.Temp.grabPostHeaderSettingsAttempts ){
            throw new Error('Timeout for grabbing the edit-post-header__settings');
        }

        if (this.UIElements.postHeaderSettings === null || this.UIElements.postHeaderSettings === undefined ){

            this.UIElements.postHeaderSettings = document.querySelector('.edit-post-header__settings');
            setTimeout( ()=>{
                this.selectPostHeaderSettings();
                this.Temp.grabPostHeaderSettingsAttempts += 1;
            }, 1000); 

        } else {
            console.log('postHeaderSettings is no longer null');
        }
    }

    async loadApplicaitonNotes(){

        if( this.Settings.applicationId ){
            console.log('ApplicationID found. Fetching Application Notes');
            this.applicationNotes = await this.fetchApplicationData();
        } else { 
            console.log('Report has not yet been connected with any Application. Showing popup.');
            this.Temp.availableApplications = await this.fetchAvailableApplications();

            if ( this.Temp.recentResponseCode !== 200 ){
                throw new Error('Error while fetching available applications ID: ' + this.Temp.recentResponseData['message']);
            }

            this.showAvailableApplicationsSelector( this.Temp.availableApplications );

        }
        
    }

    async fetchApplicationData(){

        let data = new FormData();
        data.append('action', 'bat_ajax_reports');
        data.append('subaction', 'get_application_notes');
        data.append('application_id', this.Settings.applicationId );

        const response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data});

        this.Temp.recentResponseCode = response.status;
        this.Temp.recentResponseData = response.json();
        return this.Temp.recentResponseData;

    }

    async fetchSetApplicationforCurrentReport( applicationId ){

        let data = new FormData();
        data.append('action', 'bat_ajax_reports');
        data.append('subaction', 'set_application_id_for_current_report');
        data.append('post_content', wp.data.select( 'core/editor' ).getEditedPostContent() );
        data.append('application_id', applicationId );
        data.append('report_id', this.Settings.reportId );

        const response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data
        });

        this.Temp.recentResponseCode = response.status;
        this.Temp.recentResponseData = response.json();
        return this.Temp.recentResponseData;

    }

    async fetchAvailableApplications(){

        let data = new FormData();
        data.append('action', 'bat_ajax_reports');
        data.append('subaction', 'get_available_applications');

        const response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data});

        this.Temp.recentResponseCode = response.status;
        this.Temp.recentResponseData = response.json();
        return this.Temp.recentResponseData;
        
    }

    handleShowNotesClick( event ){

        if ( this.UIElements.notesSidebar ){
            return;
        }

        let wpSideBar = document.querySelector('.interface-interface-skeleton__sidebar');
        let wpSideBarBounding = wpSideBar.getBoundingClientRect();
        let sideBarWidth;
        if( wpSideBar === null ){
            sideBarWidth = '281px';
        } else {
            sideBarWidth = wpSideBar.offsetWidth.toString() + 'px';
        }

        let notesSidebar = document.createElement('div');
        notesSidebar.setAttribute('style', 'position: fixed; top: ' + wpSideBarBounding.top.toString() + 'px; right: 0; height: ' + wpSideBarBounding.height.toString() + 'px; padding: 1rem; overflow: scroll; background-color: #ffffff; box-shadow: -10px 0px 15px #0003; z-index: 9999; width: ' + sideBarWidth + ';');

        let notesSideBarShadow = document.createElement('div');
        notesSidebar.appendChild( notesSideBarShadow );
        let shadowRoot = notesSideBarShadow.attachShadow({mode: 'open'});

        let bulmaStyles = document.createElement('link');
        bulmaStyles.setAttribute('rel', 'stylesheet');
        bulmaStyles.setAttribute('media', 'all');
        bulmaStyles.setAttribute('href', this.Settings.pluginDirUrl + 'public/css/bulma.min.css');
        
        let buttonWrapper = document.createElement('div');
        buttonWrapper.classList.add('buttons');

        let closeButton = document.createElement('button');
        closeButton.setAttribute('class', 'button is-warning is-light is-small');
        closeButton.innerText = 'Close';
        closeButton.addEventListener('click', () => {
            this.handleCloseButtonClick();
        });

        let notesContent = document.createElement('div');
        notesContent.setAttribute('class', 'content ');
        notesContent.innerHTML = this.applicationNotes;

        buttonWrapper.appendChild( closeButton );

        shadowRoot.appendChild( bulmaStyles );
        shadowRoot.appendChild( buttonWrapper );
        shadowRoot.appendChild( notesContent );

        this.UIElements.notesSidebar = notesSidebar;
        document.body.appendChild( this.UIElements.notesSidebar );
        
    }

    handleCloseButtonClick(){
        this.UIElements.notesSidebar.remove();
        this.UIElements.notesSidebar = undefined;
    }

    async handleSelectFromAvailableApplications( select ){
        
        if( select.value !== "" ){

            await this.fetchSetApplicationforCurrentReport( select.value );
            if ( this.Temp.recentResponseCode !== 200 ){
                console.log('Error: ', this.Temp.recentResponseData );
                throw new Error('Error while saving new application_id');
            }
            setTimeout(()=>{
                window.location.href ='/wp-admin/post.php?post='+ this.Settings.reportId + '&action=edit';
            }, 750);

        } else {
            console.log('No application set, continuting without any notes.');
            this.UIElements.availableApplicationsOverlay.remove();
        }

    }

}

var $j;
var batEdex;

jQuery(document).ready( function(){

    var $j = jQuery.noConflict();
    var batEdex = new EditorNotes();

});

