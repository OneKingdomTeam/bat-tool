class Questions {
    constructor( currentQuestions ) {
        this.elements = {};
        this.questions = currentQuestions;

    }

    getCurrentBeamTitle( activeBeam ){

        let currentBeamNumber = activeBeam.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let retrieveBeamTitle = this.questions['beam' + currentBeamNumber ].title;
        return( retrieveBeamTitle );

    }

    getCurrentMainQuestionContentAndDescription( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = activeBeam.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentSegmentNumber = activeSegment.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentQuestionNumber = activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        let questionContent = this.questions['beam' + currentBeamNumber ]['segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['title'];
        let questionDescription = this.questions['beam' + currentBeamNumber ]['segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['description'];

        return( { 
            'title' : questionContent,
            'description' : questionDescription
        } );

    }

    getCurrentMainQuestionContent( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = activeBeam.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentSegmentNumber = activeSegment.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentQuestionNumber = activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        let questionTitle = this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['title'];
        return( questionTitle );

    }
}

