class Interface {

    constructor() {

        batAnswers.clearAnswersFromBrowser();

        this.sessionKey = this.generateSessionKey();

        this.hoverWindow = null;


        this.interactiveCircleClass = 'bat-interactive-circle';
        this.beamClass = 'beam-';
        this.segmentClass = 'segment-';
        this.subsegmentClass = 'subsegment-';

        this.appendQuestionIdClassToBeam(batQuestions.questions);

        this.activeBeam = null;
        this.activeSegment = null;
        this.activeQuestion = null;
        this.activeAnswer = null;

        this.introContent = document.querySelector('.intro-content')
        this.mainContent = document.querySelector('.main-content')

        this.interactiveCircle = document.querySelector('.' + this.interactiveCircleClass );
        this.beams = document.querySelectorAll('.beam');
        this.questions = document.querySelectorAll('.question');

        this.startButton = document.querySelector('.bat-start-assessment');

        this.answers = document.querySelectorAll('.answer');
        this.radioBtns = document.querySelectorAll('.bat-radio-option');
        this.radioBtnClear = document.querySelector('.bat-radio-clear');
        this.commentTitle = document.querySelector('.bat-comment-title');
        this.commentDescription = document.querySelector('.bat-comment-description');
        this.saveButton = document.querySelector('.bat-save-progress-button');
        this.saveButtonLabel = document.querySelector('.bat-save-progress-label');


        this.beamTitle = document.querySelector('.beam-title');

        this.mainQuestion = document.querySelector('.main-question');
        this.mainQuestionDescription = document.querySelector('.main-question-description');

        this.textarea = document.querySelector('textarea');

        this.previousButton = document.querySelector('.previous-button');
        this.nextButton = document.querySelector('.next-button');

        this.lastQuestionWarning = document.querySelector('.last-question-warning');

        this.popupHtmlContent = '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background-color: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;" class="bat-interactive-form-popup-wrapper"> <div class="bat-interactive-form-popup-window is-flex is-flex-direction-column is-justify-content-space-evenly p-5" style="width: 550px; height: 300px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0px 0px 2rem 0px rgba(0,0,0,0.75);"> <div class="container bat-interactive-form-popup-title"> <h3 class="title is-3">Title placeholder</h3> </div><div class="container bat-interactive-form-popup-message"> <div class="content has-text-centered"> Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat. </div></div><div class="bat-interactive-form-popup-buttons" style="width: 100%"> <div class="columns"> <div class="column"> <button class="button is-warning is-fullwidth bat-interactive-form-popup-warning-button">Back to listing</button> </div><div class="column"> <button class="button is-success is-fullwidth bat-interactive-form-popup-warning-button">Reload the page</button> </div></div></div></div>';

        this.prepareHoverElements();
        this.appendEventListeners();

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

    updateBeamTitle(){

        let currentQuestionTitle = batQuestions.getCurrentBeamTitle( this.activeBeam );
        this.beamTitle.innerText = currentQuestionTitle;

    }
    
    updateMainQuestion(){

        let currentQuestionTitle = batQuestions.getCurrentMainQuestionContent( this.activeBeam, this.activeSegment, this.activeQuestion );
        this.beamTitle.innerText = currentQuestionTitle;

    }

    updateSubsegment( element, value ){

        if ( value == null ){
            element.setAttribute( 'fill', '#1b49c1' );
        };
        if ( value == 0 ){
            element.setAttribute( 'fill', '#f11627' );
        };
        if ( value == 50 ){
            element.setAttribute( 'fill', '#ff9726' );
        };
        if ( value == 100 ){
            element.setAttribute( 'fill', '#3eb05d' );
        };

    }

    updateWheel() {
        let currentData = batAnswers.getAnswersFromBrowser();


        // In this version we only have 1 segment in each beam
        let segmentClass = 'segment-1';

        for (const [key, value] of Object.entries(currentData)) {

            let currentBeamClass = this.beamClass + key.replace( /[^0-9]/g,"");
            // Key is 'beam1' 'beam2'...
            // Value is { 'segment1': {....}}

            for (const [segmentKey, segmentValue] of Object.entries( value )) {

                if (!segmentKey.includes('segment')) {
                    continue;
                }

                segmentKey;

                for (const [subKey, subValue] of Object.entries( segmentValue.answers )) {

                    let currentQuestionSubsegmentClass = this.subsegmentClass + subKey.replace( /[^0-9]/g,"");
                    let currentQuestionSubsegmentElement = document.querySelector('.' + currentBeamClass + ' .' + segmentClass + ' .' + currentQuestionSubsegmentClass);
                    this.updateSubsegment( currentQuestionSubsegmentElement, subValue );

                }

            }

        }

    }

    prepareHoverElements(){

        this.hoverWindow = document.createElement('div');
        this.hoverWindow.style.minWidth = '10rem';
        this.hoverWindow.style.minHeight = '1rem';
        this.hoverWindow.style.padding = '1rem';
        this.hoverWindow.style.borderRadius = '0.4rem';
        this.hoverWindow.style.backgroundColor = '#ffffff';
        this.hoverWindow.style.boxShadow = '10px 10px 25px 0px rgba(0,0,0,0.57)';
        this.hoverWindow.style.fontSize = '1.2rem';
        this.hoverWindow.style.textAlign  = 'center';
        this.hoverWindow.style.display = 'block';
        this.hoverWindow.style.position = 'fixed';
        this.hoverWindow.style.zIndex = '9999999';
        this.hoverWindow.innerText = 'Loren Ipsum';
        this.hoverWindow.classList.add('bat-interactive-form-hover-window');
        this.hoverWindow.style.display = 'none';

        document.body.appendChild( this.hoverWindow );


    }

    handleBeamMouseEnter( event ){

        let hoverBeam = event.target;
        let hoverBeamTitle = batQuestions.getCurrentBeamTitle( hoverBeam );
        this.hoverWindow.innerText = hoverBeamTitle;
        this.hoverWindow.style.display = 'block';

    }

    handleBeamMouseLeave(){

        this.hoverWindow.style.display = 'none';

    }

    handleCircleHover( event ){

        let mouseX = event.clientX;
        let mouseY = event.clientY;
        let windowPositionX = mouseX + 30;
        let windowPositionY = mouseY + 30;
        this.hoverWindow.style.top = windowPositionY.toString() + 'px';
        this.hoverWindow.style.left = windowPositionX.toString() + 'px';

    }

    handleBeamClick( event ){

        this.introContent.classList.add('is-hidden');

        this.mainContent.classList.remove('is-hidden');

        this.beams.forEach( function( beam ){
            beam.classList.remove('active');
        });

        let currentBeam = event.target.closest('.beam')
        currentBeam.classList.add('active');

        this.activeBeam = currentBeam;

        this.activeSegment = event.target.closest('.segment');

        this.questions[0].click();

        this.updateBeamTitle();       

    }

    handleStartButtonClick(){
        
        let firstBeamTarget = {};
        firstBeamTarget.target = this.beams[0].querySelector('.subsegment-1');
        this.handleBeamClick( firstBeamTarget );

    }

    handleQuestionClick( event ){

        event.preventDefault();
        this.questions.forEach( function( question ){
            question.classList.remove('is-active');
        });
        let currentQuestion = event.target.closest('.question');

        currentQuestion.classList.add('is-active');

        this.activeQuestion = currentQuestion;
        this.fillInMainquestionAndDecription();
        this.fillInRadioValues();
        this.fillInCommentTitleAndCommentDescription();
        this.fillInAnswer();
        this.fillInComment();

        this.deOrActivateButton();

    }

    handleAnswerClick( event ){

        let currentAnswer = event.target.closest('.answer');
        this.activeAnswer = currentAnswer;
        this.callSaveData();

    }

    handleCommentChange(){

        this.callSaveData();

    }

    handleNextClick( event ){

        event.preventDefault();

        let buttonClassList = event.target.classList;
        if( !buttonClassList.contains( 'disabled' ) ) {

            let currentQuestionNumber = this.activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");
            let nextQuestionNumberStr = ( parseInt( currentQuestionNumber ) + 1 ).toString();
            let targetElement = '.question-' + nextQuestionNumberStr;
            document.querySelector(targetElement).click();

        }

    }

    handlePreviousClick( event ){

        event.preventDefault();

        let buttonClassList = event.target.classList;
        if( !buttonClassList.contains( 'disabled' ) ) {

            let currentQuestionNumber = this.activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");
            let previousQuestionNumberStr = ( parseInt( currentQuestionNumber ) - 1 ).toString();
            let targetElement = '.question-' + previousQuestionNumberStr;
            document.querySelector(targetElement).click();

        }

    }

    handleClearRadioClick(){
        this.radioBtns.forEach( function(radio){
            radio.firstElementChild.checked = false;
        } );
        this.activeAnswer = null;
        this.callSaveData();
    }

    callSaveData(){

        batAnswers.updateAnswers( this.activeBeam, this.activeSegment, this.activeQuestion, this.activeAnswer, this.textarea );
        this.updateWheel();

    }

    deOrActivateButton(){

        let currentQuestionNumber = this.activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        this.nextButton.disabled = false;
        this.previousButton.disabled = false;
        this.lastQuestionWarning.classList.add('is-hidden');

        if ( currentQuestionNumber == "1" ){
            this.previousButton.disabled = true;
        }

        if ( currentQuestionNumber == "4" ){
            this.nextButton.disabled = true;
            this.lastQuestionWarning.classList.remove('is-hidden');
        }

    }

    fillInMainquestionAndDecription(){
        
        let mainQuestionContentAndDescription = batQuestions.getCurrentMainQuestionContentAndDescription( this.activeBeam, this.activeSegment, this.activeQuestion );
        this.mainQuestion.innerText = mainQuestionContentAndDescription['title'];
        this.mainQuestionDescription.innerText = mainQuestionContentAndDescription['description'];

    }

    fillInRadioValues(){

        let values = batQuestions.getCurrentQuestionRadioValues( this.activeBeam, this.activeSegment, this.activeQuestion );

        let i = 0;
        this.radioBtns.forEach( function( radioButton ){

            radioButton.querySelector('span').innerText = values[i];
            i++;

        } );

    }

    fillInCommentTitleAndCommentDescription(){

        let commentData = batQuestions.getCurrentCommentTitleAndCommentDescription( this.activeBeam, this.activeSegment, this.activeQuestion );

        this.commentTitle.innerText = commentData['commentTitle'];
        this.commentDescription.innerText = commentData['commentDescription'];

    }

    fillInAnswer(){

        let value = batAnswers.returnStoredAnswerValue( this.activeBeam, this.activeSegment, this.activeQuestion );

        this.answers.forEach( function( answer ){

            answer.checked = false;

        })

        document.querySelector('.answer-1').checked = false;
        document.querySelector('.answer-2').checked = false;
        document.querySelector('.answer-3').checked = false;

        if ( value == 0 ) { document.querySelector('.answer-1').checked = true };
        if ( value == 50 ) { document.querySelector('.answer-2').checked = true };
        if ( value == 100 ) { document.querySelector('.answer-3').checked = true };

    }

    fillInComment(){

        let value = batAnswers.returnStoredCommentValue( this.activeBeam, this.activeSegment, this.activeQuestion );

        this.textarea.value = "";
        this.textarea.value = value;

    }

    appendEventListeners(){

        var self = this;

        self.interactiveCircle.addEventListener('mousemove', (event)=>this.handleCircleHover(event));

        self.beams.forEach( function( beam ){
            // Checks if current beam is disabled
            if( beam.classList.contains('beam-disabled') ) { return };
            
            beam.addEventListener( 'click', function( event ){
                self.handleBeamClick( event );
            } );

            beam.addEventListener( 'mouseenter', (event)=>self.handleBeamMouseEnter(event) );
            beam.addEventListener( 'mouseleave', (event)=>self.handleBeamMouseLeave(event) );
        });

        self.startButton.addEventListener('click', ( event ) => {
            event.preventDefault();
            this.handleStartButtonClick();
        });

        self.questions.forEach( function( question ){
            question.addEventListener( 'click', function( event ){
                self.handleQuestionClick( event );
            } );
        });

        self.answers.forEach( function( answer ){
            answer.addEventListener( 'click', function( event ){
                self.handleAnswerClick( event );
            } );
        });

        self.radioBtnClear.addEventListener('click', function(){
            self.handleClearRadioClick();
        } );

        self.textarea.addEventListener('change', function(){
            self.handleCommentChange();
        });

        self.previousButton.addEventListener('click', function( event ){
            self.handlePreviousClick( event );
        } );

        self.nextButton.addEventListener('click', function( event ){
            self.handleNextClick( event );
        } );

        self.saveButton.addEventListener('click', function( event ){
            batConnector.saveApplicationData( batAnswers.answers, self.sessionKey );
        });

    }

    showError( title = 'Error',  content = 'Error occured' ){

        console.log(`Error orrucred: 
Title: ${ title }
Content: ${ content }`);

    }

    showLoading( show ){

        if ( show === true ){

            if ( document.querySelector('.bat-loader') === null ) {

                let loadingScreenString = '<div class="bat-loader" >' + batAjax.loaderHTML + '</div>';
                let parser = new DOMParser();
                let loadingScreenObj = parser.parseFromString( loadingScreenString, 'text/html' );
                document.body.appendChild( loadingScreenObj.querySelector('.bat-loader') );
 
            }

            document.querySelector('.bat-loader').classList.remove('is-hidden');

        }

        if ( show === false ){

            if ( document.querySelector('.bat-loader') !== null ){

                document.querySelector('.bat-loader').classList.add('is-hidden');

            }

        }
        

    }

    showSavingProgress( status ){
        if ( status === true ) {
            this.saveButton.classList.add('is-loading');
        }

        if ( status === false ){
            setTimeout( ()=>{
                this.saveButton.classList.remove('is-loading');
            }, 500 );
        }
    }

    autoSaving( status ){


        if ( status === true ) {

            var self = this;
            this.autoSaveInterval = setInterval(() => {
                console.log( 'Saving to server...' );
                batConnector.saveApplicationData( batAnswers.answers, self.sessionKey );
            }, 15000 );

            this.saveNotificationInterval = setInterval( () => {
                if ( batConnector.recentSaveTime !== null ) {
                    let timeNow = Math.round( Date.now() / 1000 );
                    let timeDifference = timeNow - batConnector.recentSaveTime;
                    let timeDifferenceStr = timeDifference.toString();
                    this.saveButtonLabel.innerText = `Last save:  ${timeDifferenceStr}s ago`;
                    this.saveButtonLabel.style.visibility = 'visible';
                } 
            }, 1000 );

        } else {

            clearInterval( this.autoSaveInterval );
            clearInterval( this.saveNotificationInterval );

        }

    }

    showPopup( visible, title, message, btnToListing, btnReload ){

        if ( visible == false ){

            let popupWrapper = document.querySelector('.bat-interactive-form-popup-wrapper');
            if ( popupWrapper !== null ){
                popupWrapper.outerHTML = "";
            }

            return;
            
        };

        if ( visible == true ){

            const parser = new DOMParser();
            const parsedHtml = parser.parseFromString( this.popupHtmlContent, 'text/html');
            const actuallContent = parsedHtml.querySelector('.bat-interactive-form-popup-wrapper').cloneNode(true);

            actuallContent.querySelector('.bat-interactive-form-popup-title h3').innerText = title;
            actuallContent.querySelector('.bat-interactive-form-popup-message .content').innerText = message;
            actuallContent.querySelector('.button.is-warning').addEventListener('click', function(){ window.location.href = '/' });
            actuallContent.querySelector('.button.is-success').addEventListener('click', function(){ window.location.reload()});

            if ( btnToListing == false ) {
                actuallContent.querySelector('.button.is-warning').closest('.column').outerHTML = "";
            }
            if ( btnReload == false ) {
                actuallContent.querySelector('.button.is-success').closest('.column').outerHTML = "";
            }

            document.body.appendChild( actuallContent );
        };

    }

    appendQuestionIdClassToBeam( questionData ){

        for (const [questionKey, questionValue] of Object.entries( questionData )) {
            document.querySelector('.' + questionKey.replace('beam', 'beam-')).classList.add('question_id-' + questionValue['question_id']);
        }
    }

}

