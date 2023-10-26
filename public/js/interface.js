class Interface {

    constructor() {

        thetAnswers.clearAnswersFromBrowser();

        this.sessionKey = this.generateSessionKey();

        this.hoverWindow = '';

        this.interactiveCircleClass = 'thet-interactive-circle';
        this.beamClass = 'beam-';
        this.segmentClass = 'segment-';
        this.subsegmentClass = 'subsegment-';

        this.activeBeam = null;
        this.activeSegment = null;
        this.activeQuestion = null;
        this.activeAnswer = null;

        this.introContent = document.querySelector('.intro-content')
        this.mainContent = document.querySelector('.main-content')

        this.interactiveCircle = document.querySelector('.' + this.interactiveCircleClass );
        this.beams = document.querySelectorAll('.beam');
        this.questions = document.querySelectorAll('.question');
        this.answers = document.querySelectorAll('.answer');
        this.radioBtns = document.querySelectorAll('.thet-radio-option');
        this.radioBtnClear = document.querySelector('.thet-radio-clear');

        this.beamTitle = document.querySelector('.beam-title');

        this.mainQuestion = document.querySelector('.main-question');
        this.mainQuestionDescription = document.querySelector('.main-question-description');

        this.textarea = document.querySelector('textarea');

        this.previousButton = document.querySelector('.previous-button');
        this.nextButton = document.querySelector('.next-button');

        this.lastQuestionWarning = document.querySelector('.last-question-warning');

        this.popupHtmlContent = '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background-color: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;" class="thet-interactive-form-popup-wrapper"> <div class="thet-interactive-form-popup-window is-flex is-flex-direction-column is-justify-content-space-evenly p-5" style="width: 550px; height: 300px; background-color: #fff; border-radius: 0.5rem; box-shadow: 0px 0px 2rem 0px rgba(0,0,0,0.75);"> <div class="container thet-interactive-form-popup-title"> <h3 class="title is-3">Title placeholder</h3> </div><div class="container thet-interactive-form-popup-message"> <div class="content has-text-centered"> Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat. </div></div><div class="thet-interactive-form-popup-buttons" style="width: 100%"> <div class="columns"> <div class="column"> <button class="button is-warning is-fullwidth thet-interactive-form-popup-warning-button">Back to listing</button> </div><div class="column"> <button class="button is-success is-fullwidth thet-interactive-form-popup-warning-button">Reload the page</button> </div></div></div></div>';

        this.prepareHoverElement();
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

        let currentQuestionTitle = thetQuestions.getCurrentBeamTitle( this.activeBeam );
        this.beamTitle.innerText = currentQuestionTitle;

    }
    
    updateMainQuestion(){

        let currentQuestionTitle = thetQuestions.getCurrentMainQuestionContent( this.activeBeam, this.activeSegment, this.activeQuestion );
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
        let currentData = thetAnswers.getAnswersFromBrowser();


        // In this version we only have 1 segment in each beam
        let segmentClass = 'segment-1';

        for (const [key, value] of Object.entries(currentData)) {

            let currentBeamClass = this.beamClass + key.replace( /[^0-9]/g,"");
            // Key is 'beam1' 'beam2'...
            // Value is { 'segment1': {....}}

            for (const [segmentKey, segmentValue] of Object.entries( value )) {

                segmentKey;

                for (const [subKey, subValue] of Object.entries( segmentValue.answers )) {

                    let currentQuestionSubsegmentClass = this.subsegmentClass + subKey.replace( /[^0-9]/g,"");
                    let currentQuestionSubsegmentElement = document.querySelector('.' + currentBeamClass + ' .' + segmentClass + ' .' + currentQuestionSubsegmentClass);
                    this.updateSubsegment( currentQuestionSubsegmentElement, subValue );

                }

            }

        }

    }

    prepareHoverElement(){

        this.hoverWindow = document.createElement('div');
        this.hoverWindow.style.minWidth = '10rem';
        this.hoverWindow.style.minHeight = '1rem';
        this.hoverWindow.style.padding = '1rem';
        this.hoverWindow.style.borderRadius = '0.4rem' ;
        this.hoverWindow.style.backgroundColor = '#ffffff' ;
        this.hoverWindow.style.boxShadow = '10px 10px 25px 0px rgba(0,0,0,0.57)';
        this.hoverWindow.style.fontSize = '1.2rem';
        this.hoverWindow.style.textAlign  = 'center';
        this.hoverWindow.style.display = 'block';
        this.hoverWindow.style.position = 'absolute';
        this.hoverWindow.style.zIndex = '9999999';
        this.hoverWindow.innerText = 'Loren Ipsum';
        this.hoverWindow.classList.add('thet-interactive-form-hover-window');
        this.hoverWindow.style.display = 'none';

        document.body.appendChild( this.hoverWindow );

    }

    handleBeamMouseEnter( event ){

        let hoverBeam = event.target;
        let hoverBeamTitle = thetQuestions.getCurrentBeamTitle( hoverBeam );
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

        thetAnswers.updateAnswers( this.activeBeam, this.activeSegment, this.activeQuestion, this.activeAnswer, this.textarea );
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
        
        let mainQuestionContentAndDescription = thetQuestions.getCurrentMainQuestionContentAndDescription( this.activeBeam, this.activeSegment, this.activeQuestion );
        this.mainQuestion.innerText = mainQuestionContentAndDescription['title'];
        this.mainQuestionDescription.innerText = mainQuestionContentAndDescription['description'];

    }

    fillInRadioValues(){

        let values = thetQuestions.getCurrentQuestionRadioValues( this.activeBeam, this.activeSegment, this.activeQuestion );

        let i = 0;
        this.radioBtns.forEach( function( radioButton ){

            radioButton.querySelector('span').innerText = values[i];
            i++;

        } );

    }

    fillInAnswer(){

        let value = thetAnswers.returnStoredAnswerValue( this.activeBeam, this.activeSegment, this.activeQuestion );

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

        let value = thetAnswers.returnStoredCommentValue( this.activeBeam, this.activeSegment, this.activeQuestion );

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

    }

    showError( title = 'Error',  content = 'Error occured' ){

        console.log(`Error orrucred: \nTitle: ${ title }\nContent: ${ content }`);

    }

    showLoading( show ){

        if ( show === true ){

            if ( document.querySelector('.thet-loader') === null ) {

                let loadingScreenString = '<div class="thet-loader" >' + thetAjax.loaderHTML + '</div>';
                let parser = new DOMParser();
                let loadingScreenObj = parser.parseFromString( loadingScreenString, 'text/html' );
                document.body.appendChild( loadingScreenObj.querySelector('.thet-loader') );
 
            }

            document.querySelector('.thet-loader').classList.remove('is-hidden');

        }

        if ( show === false ){

            if ( document.querySelector('.thet-loader') !== null ){

                document.querySelector('.thet-loader').classList.add('is-hidden');

            }

        }
        

    }

    autoSaveing( status ){

        if ( status === true ) {

            var self = this;
            this.autoSaveInterval = setInterval(() => {
                console.log( 'Saving to server...' );
                thetConnector.saveApplicationData( thetAnswers.answers, self.sessionKey );
            }, 15000 );

        } else {

            clearInterval( this.autoSaveInterval );

        }

    }

    showPopup( visible, title, message, btnToListing, btnReload ){

        if ( visible == false ){

            let popupWrapper = document.querySelector('.thet-interactive-form-popup-wrapper');
            if ( popupWrapper !== null ){
                popupWrapper.outerHTML = "";
            }

            return;
            
        };

        if ( visible == true ){

            const parser = new DOMParser();
            const parsedHtml = parser.parseFromString( this.popupHtmlContent, 'text/html');
            const actuallContent = parsedHtml.querySelector('.thet-interactive-form-popup-wrapper').cloneNode(true);

            actuallContent.querySelector('.thet-interactive-form-popup-title h3').innerText = title;
            actuallContent.querySelector('.thet-interactive-form-popup-message .content').innerText = message;
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

}

