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

    getCurrentQuestionRadioValues( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = activeBeam.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentSegmentNumber = activeSegment.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentQuestionNumber = activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        let radioValues = [
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option1'],
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option2'],
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option3'],
        ];

        return radioValues;
    }

    getCurrentCommentTitleAndCommentDescription( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = activeBeam.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentSegmentNumber = activeSegment.getAttribute( 'class' ).replace( /[^0-9]/g,"");
        let currentQuestionNumber = activeQuestion.getAttribute( 'class' ).replace( /[^0-9]/g,"");

        let commentSectionData = {
            "commentTitle" : this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['commentTitle'],
            "commentDescription" : this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['commentDescription']
        };

        return commentSectionData;
    }
}

