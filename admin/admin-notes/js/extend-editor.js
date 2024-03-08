class EditorNotes {

    constructor(){

        this.Settings = {};
        this.Settings.grabPostHeaderSettingsMaxAttempts = 5;


        console.log('Initialized thetEditorExtentnion');
        this.UIElements = {};

        this.Temp = {};
        this.Temp.grabPostHeaderSettingsAttempts = 0;

        this.selectPostHeaderSettings();
        this.localizeThis();
        this.loadApplicaitonNotes();


    }

    localizeThis(){
        if ( !thetNotesEditorLocalization ){
            throw new Error('Localization object not found!');
        } else {
            for (const [key, value] of Object.entries(thetNotesEditorLocalization)){
                this.Settings[key] = value;
            }
        }
    }

    createNotesIcon (){
        if ( this.UIElements.postHeaderSettings !== null ){

            this.UIElements.postHeaderNotesIcon = document.createElement('div');
            this.UIElements.postHeaderNotesIcon.style.minWidth = '30px';
            this.UIElements.postHeaderNotesIcon.style.height = '30px';
            this.UIElements.postHeaderNotesIcon.style.backgroundColor = 'blue';
            this.UIElements.postHeaderNotesIcon.style.cursor = 'pointer';
            this.UIElements.postHeaderNotesIcon.innerText = 'Show notes';

            this.UIElements.postHeaderNotesIcon.addEventListener('click', (event) => {
                this.handleShowNotesClick(event);
            })

            this.UIElements.postHeaderSettings.insertBefore( this.UIElements.postHeaderNotesIcon, this.UIElements.postHeaderSettings.firstElementChild )

        } else {
            throw new Error('Unable to locate edit-post-header__settings element');
        }
    }

    showAvailableApplicationsSelector( availableApplications ){

        let overlay = document.createElement('div');
        overlay.style.zIndex = 9999;
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.backgroundColor = '#ffffff99';
        overlay.style.backdropFilter = 'blur(10px)';
        overlay.style.position = 'fixed';
        overlay.style.top = '0px';
        overlay.style.left = '0px';
        overlay.style.display = 'flex';
        overlay.style.justifyContent = 'center';
        overlay.style.alignItems = 'center';

        let window = document.createElement('div');
        window.style.backgroundColor = '#ffffff';
        window.style.borderRadius = '0.5rem';
        window.style.padding = '3rem 3rem'; 
        window.style.boxShadow = '0rem 0rem 1rem #00000033'; 
        window.style.minWidth = '380px';
        window.style.minHeight = '160px';
        window.style.display = 'flex';
        window.style.flexDirection = 'column';
        window.style.alignItems = 'center';
        window.style.justifyContent = 'center';

        let title = document.createElement('h4');
        title.innerText = 'Select application';
        title.style.fontSize = '1.5rem';
        title.style.fontWeight = 'bold';
        let subTitle = document.createElement('p');
        subTitle.style.fontSize = '1.15rem';
        subTitle.innerText = 'It will allow you to view the notes and have them right here during the creation of the report';

        let select = document.createElement('select');
        select.style.minWidth = '19rem';
        select.style.fontSize = '1.15rem';
        select.style.padding = '0.35rem';
        select.style.textAlign = 'center';
        select.style.marginBottom = '18px';
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
            this.createNotesIcon();
        }
    }

    async loadApplicaitonNotes(){

        if( this.Settings.applicationId ){
            console.log('ApplicationID found. Fetching Application Notes');
            this.applicationNotes = this.fetchApplicationData();
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
        data.append('action', 'thet_ajax_reports');
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
        data.append('action', 'thet_ajax_reports');
        data.append('subaction', 'set_application_id_for_current_report');
        data.append('application_id', applicationId );
        data.append('report_id', this.Settings.reportId );

        const response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data});

        this.Temp.recentResponseCode = response.status;
        this.Temp.recentResponseData = response.json();
        return this.Temp.recentResponseData;

    }

    async fetchAvailableApplications(){

        let data = new FormData();
        data.append('action', 'thet_ajax_reports');
        data.append('subaction', 'get_available_applications');

        const response = await fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: data});

        this.Temp.recentResponseCode = response.status;
        this.Temp.recentResponseData = response.json();
        return this.Temp.recentResponseData;
        
    }

    handleShowNotesClick( event ){
        console.log('Clicked element: ', event.target);
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



let thetEdex;
window.addEventListener('load', () => {

    thetEdex = new EditorNotes();

});
