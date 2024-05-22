class EditorNotes {

    constructor(){

        this.Settings = {};
        this.Settings.grabPostHeaderSettingsMaxAttempts = 5;
        this.Settings.iconCreationMaxAttempts = 10;

        this.Settings.updateButtonClass = '.editor-post-publish-button';

        console.log('Initialized batEditorExtentnion');
        this.UIElements = {};

        this.Temp = {};
        this.Temp.grabPostHeaderSettingsAttempts = 0;
        this.Temp.iconCreationAttempts = 0;

        this.selectPostHeaderSettings();
        this.localizeThis();
        this.loadApplicaitonNotes();
        this.createIconsWhenReady();

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

    createNotesIcon(){

        let notesToggleButton = $jq('<div>', {
            'class': 'components-button button is-warning is-light is-flex is-align-items-center',
        });

        let notesToggleButtonText = $jq('<div>', {
            'html': 'Show notes'
        });

        let innerIcon = $jq('<img>', {
            'src': this.Settings.pluginDirUrl + 'public/media/notes-icon.svg',
            'style': 'width: 20px; height: 20px; margin-left: 6px;',
        });

        notesToggleButton.append(notesToggleButtonText);
        notesToggleButton.append(innerIcon);

        notesToggleButton.on('click', () => {
            this.handleShowNotesClick();
        })

        notesToggleButton.insertBefore(this.UIElements.postHeaderSettings.firstElementChild);

        this.UIElements.notesToggleButton = notesToggleButton;
        this.UIElements.notesToggleButtonText = notesToggleButtonText;

    }

    createSendReportButton(){

        let sendReportButton = $jq('<div>', {
            'class': 'components-button button is-info is-light is-flex is-align-items-center',
        });

        let sendReportButtonText = $jq('<div>', {
            'html': 'Send report'
        });

        let sendReportInnerIcon = $jq('<img>', {
            'src': this.Settings.pluginDirUrl + 'public/media/mail-icon-report.svg',
            'style': 'width: 20px; height: 20px; margin-left: 6px;',
        });

        sendReportButton.append(sendReportButtonText);
        sendReportButton.append(sendReportInnerIcon);

        sendReportButton.on('click', () => {
            this.handleSendreportButtonClick();
        })

        sendReportButton.insertBefore(this.UIElements.postHeaderSettings.firstElementChild);

        this.UIElements.sendReportButton = sendReportButton;
        this.UIElements.sendReportButtonText = sendReportButtonText;

    }


    createIconsWhenReady(){

        if ( this.Settings.applicationId === "" ){
            console.log('No application ID set. Going without it');
            return;
        }

        if ( this.Temp.iconCreationAttempts >= this.Settings.iconCreationMaxAttempts ) {
            throw new Error('Coudn\'t create an icon.');
        }

        // attempts to create notes icon after the notes are loaded. 
        if ( this.UIElements.postHeaderSettings === undefined || this.UIElements.postHeaderSettings === null ){
            setTimeout(()=>{
                this.createIconsWhenReady();
            }, 250);
        } else {
            if ( this.UIElements.postHeaderSettings !== null ){
                this.createSendReportButton();
                this.createNotesIcon();
            } else {
                throw new Error('Unable to locate edit-post-header__settings element');
            }
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
        subTitle.style.paddingBottom = '2rem';
        subTitle.innerText = 'It will allow you to view the notes and have them right here during the creation of the report';

        let select = document.createElement('select');
        select.setAttribute('style', 'min-width: 19rem; font-size: 1.15rem; padding: 0.35rem; text-align: center; margin-bottom: 2rem;');
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
        button.innerText = 'Select';
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

    handleShowNotesClick(){

        this.UIElements.notesToggleButtonText.text('Hide notes');
        this.UIElements.notesToggleButton.removeClass('is-warning');
        this.UIElements.notesToggleButton.addClass('is-danger');
        if ( this.UIElements.notesSidebar ){
            this.UIElements.notesToggleButtonText.text('Show notes');
            this.UIElements.notesToggleButton.removeClass('is-danger');
            this.UIElements.notesToggleButton.addClass('is-warning');
            $jq(this.UIElements.notesSidebar).remove();
            this.UIElements.notesSidebar = undefined;
            return;
        }

        let wpSideBar = $jq('.interface-interface-skeleton__sidebar');
        let sideBarWidth;
        if( wpSideBar.length === 0 ){
            sideBarWidth = '280px';
        } else {
            sideBarWidth = wpSideBar.width().toString() + 'px';
        }

        let notesSidebar = document.createElement('div');
        notesSidebar.setAttribute('style', 'position: fixed; top: ' + wpSideBar.position().top.toString() + 'px; right: 0; height: ' + wpSideBar.height().toString() + 'px; padding: 1rem; overflow: scroll; background-color: #ffffff; z-index: 9999; width: ' + sideBarWidth + ';');

        let notesSideBarShadow = document.createElement('div');
        notesSidebar.appendChild( notesSideBarShadow );
        let shadowRoot = notesSideBarShadow.attachShadow({mode: 'open'});

        let bulmaStyles = document.createElement('link');
        bulmaStyles.setAttribute('rel', 'stylesheet');
        bulmaStyles.setAttribute('media', 'all');
        bulmaStyles.setAttribute('href', this.Settings.pluginDirUrl + 'public/css/bulma.min.css');
        
        let buttonWrapper = document.createElement('div');
        buttonWrapper.classList.add('buttons');

        let notesContent = document.createElement('div');
        notesContent.setAttribute('class', 'content ');
        notesContent.innerHTML = this.applicationNotes;

        shadowRoot.appendChild( bulmaStyles );
        shadowRoot.appendChild( buttonWrapper );
        shadowRoot.appendChild( notesContent );

        this.UIElements.notesSidebar = notesSidebar;
        document.body.appendChild( this.UIElements.notesSidebar );
        
    }

    handleSendreportButtonClick(){

        $jq(this.Settings.updateButtonClass).click();


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

var $jq;
var batEdex;

jQuery(document).ready( function(){

    $jq = jQuery.noConflict();
    batEdex = new EditorNotes();

});
