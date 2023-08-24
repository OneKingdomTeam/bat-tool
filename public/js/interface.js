class Interface {

    constructor() {

        thetAnswers.clearAnswersFromBrowser();

        this.beamClass = 'beam-';
        this.segmentClass = 'segment-';
        this.subsegmentClass = 'subsegment-';

        this.activeBeam = null;
        this.activeSegment = null;
        this.activeQuestion = null;
        this.activeAnswer = null;

        this.introContent = document.querySelector('.intro-content')
        this.mainContent = document.querySelector('.main-content')

        this.beams = document.querySelectorAll('.beam');
        this.questions = document.querySelectorAll('.question');
        this.answers = document.querySelectorAll('.answer');

        this.beamTitle = document.querySelector('.beam-title');

        this.mainQuestion = document.querySelector('.main-question');
        this.mainQuestionDescription = document.querySelector('.main-question-description');

        this.textarea = document.querySelector('textarea');

        this.previousButton = document.querySelector('.previous-button');
        this.nextButton = document.querySelector('.next-button');

        this.lastQuestionWarning = document.querySelector('.last-question-warning');

        this.appendEventListeners();

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

    callSaveData(){

        thetAnswers.updateAnswers( this.activeBeam, this.activeSegment, this.activeQuestion, this.activeAnswer, this.textarea );
        this.updateWheel();

    }

    deOrActivateButton(){

        let currentQuestionNumber = this.activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        this.nextButton.classList.remove('disabled');
        this.previousButton.classList.remove('disabled');
        this.lastQuestionWarning.classList.add('is-hidden');

        if ( currentQuestionNumber == "1" ){
            this.previousButton.classList.add('disabled');
        }

        if ( currentQuestionNumber == "4" ){
            this.nextButton.classList.add('disabled');
            this.lastQuestionWarning.classList.remove('is-hidden');
        }

    }

    fillInMainquestionAndDecription(){
        
        let mainQuestionContentAndDescription = thetQuestions.getCurrentMainQuestionContentAndDescription( this.activeBeam, this.activeSegment, this.activeQuestion );
        this.mainQuestion.innerText = mainQuestionContentAndDescription['title'];
        this.mainQuestionDescription.innerText = mainQuestionContentAndDescription['description'];

    }

    fillInAnswer(){

        let value = thetAnswers.returnStoredAnswerValue( this.activeBeam, this.activeSegment, this.activeQuestion );

        this.answers.forEach( function( answer ){

            answer.checked = false;

        })

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

        self.beams.forEach( function( beam ){
            beam.addEventListener( 'click', function( event ){
                self.handleBeamClick( event );
            } );
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
}

