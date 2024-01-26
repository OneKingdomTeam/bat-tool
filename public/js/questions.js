class Questions {
    constructor( currentQuestions ) {
        this.elements = {};
        this.questions = currentQuestions;

    }

    getCurrentBeamTitle( activeBeam ){

        let currentBeamNumber = this.getActiveElementNumberFromClassList(activeBeam);
        let retrieveBeamTitle = this.questions['beam' + currentBeamNumber ].title;
        return( retrieveBeamTitle );

    }

    getCurrentMainQuestionContentAndDescription( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = this.getActiveElementNumberFromClassList( activeBeam );
        let currentSegmentNumber = this.getActiveElementNumberFromClassList( activeSegment );
        let currentQuestionNumber = this.getActiveElementNumberFromClassList( activeQuestion );

        let questionContent = this.questions['beam' + currentBeamNumber ]['segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['title'];
        let questionDescription = this.questions['beam' + currentBeamNumber ]['segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['description'];

        return( { 
            'title' : questionContent,
            'description' : questionDescription
        } );

    }

    getCurrentMainQuestionContent( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = this.getActiveElementNumberFromClassList( activeBeam );
        let currentSegmentNumber = this.getActiveElementNumberFromClassList( activeSegment );
        let currentQuestionNumber = this.getActiveElementNumberFromClassList( activeQuestion );

        let questionTitle = this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['title'];
        return( questionTitle );

    }

    getCurrentQuestionRadioValues( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = this.getActiveElementNumberFromClassList( activeBeam );
        let currentSegmentNumber = this.getActiveElementNumberFromClassList( activeSegment );
        let currentQuestionNumber = this.getActiveElementNumberFromClassList( activeQuestion );

        let radioValues = [
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option1'],
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option2'],
            this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['radio']['option3'],
        ];

        return radioValues;
    }

    getCurrentCommentTitleAndCommentDescription( activeBeam, activeSegment, activeQuestion ){

        let currentBeamNumber = this.getActiveElementNumberFromClassList( activeBeam );
        let currentSegmentNumber = this.getActiveElementNumberFromClassList( activeSegment );
        let currentQuestionNumber = this.getActiveElementNumberFromClassList( activeQuestion );

        let commentSectionData = {
            "commentTitle" : this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['commentTitle'],
            "commentDescription" : this.questions['beam' + currentBeamNumber ][ 'segment' + currentSegmentNumber ]['question' + currentQuestionNumber ]['commentDescription']
        };

        return commentSectionData;
    }

    getActiveElementNumberFromClassList( element ){
        let classList = element.classList;
        let outputNumber = undefined;
        classList.forEach(classVal => {
            // Checks for either beam, segment or subsegment and extract the number from that class
            if( classVal.includes('beam-') || classVal.includes('segment-') || classVal.includes('subsegment-') || classVal.includes('question-') ){
                outputNumber = classVal.replace( /[^0-9]/g,"");
            }
        });
        return outputNumber;
    }
}







