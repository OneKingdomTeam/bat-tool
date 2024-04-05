class Answers {

    constructor() {

        this.answers = {

            'beam00' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },
            'beam01' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam02' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam03' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam04' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam05' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam06' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam07' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam08' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam09' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam10' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam11' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            },

            'beam12' : {
                'question_id': 0,

                'segment1': {

                    'answers' : {
                        'answer1' : null,
                        'answer2' : null,
                        'answer3' : null,
                        'answer4' : null,
                    },
                    'comments' : {
                        'comment1' : null,
                        'comment2' : null,
                        'comment3' : null,
                        'comment4' : null,
                    }
                },


            }

        }

        this.storeQuestionsIDtoBeam( batQuestions.questions );

    }

    saveAnswersToBrowser(){

        let answers = JSON.stringify( this.answers );
        window.localStorage.setItem( 'batAnswers', answers );

    }

    getAnswersFromBrowser(){

        let answers = JSON.parse( window.localStorage.getItem( 'batAnswers' ) );
        this.answers = answers;
        return( this.answers );

    }

    clearAnswersFromBrowser(){

        window.localStorage.removeItem( 'batAnswers' );

    }

    updateAnswers( activeBeam, activeSegment, activeQuestion, activeAnswer, textarea ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );

        let answerValue = this.extractAnswerValue( activeAnswer );
        let commentValue = textarea.value;

        this.answers['beam' + beamNumber]['segment' + segmentNumber]['answers']['answer' + questionNumber] = answerValue;
        this.answers['beam' + beamNumber]['segment' + segmentNumber]['comments']['comment' + questionNumber] = commentValue;
        this.saveAnswersToBrowser();

    }

    returnStoredAnswerValue( activeBeam, activeSegment, activeQuestion ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );
        let value = this.answers['beam' + beamNumber]['segment' + segmentNumber]['answers']['answer' + questionNumber];
        return( value );

    }

    returnStoredCommentValue( activeBeam, activeSegment, activeQuestion ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );
        let value = this.answers['beam' + beamNumber]['segment' + segmentNumber]['comments']['comment' + questionNumber];
        return( value );

    }

    extractAnswerValue( activeAnswer ){

        if( activeAnswer == null ){
            return ( null );
        }

        let classList = activeAnswer.getAttribute( 'class' );

        if ( classList.includes('answer-1' )){
            return ( 0 );
        }

        if ( classList.includes('answer-2' )){
            return ( 50 );
        }

        if ( classList.includes('answer-3' )){
            return ( 100 );
        }

    }

    getElementClassNumber( element ){

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

    storeQuestionsIDtoBeam( questionsData ){

        for (const [questionKey, questionValue] of Object.entries( questionsData )) {
            this.answers[ questionKey ]['question_id'] = questionValue['question_id'];
        }
    }

    checkQuestionAnswerAlignment(){

        var updatedAnswers = {};
        for( const [key,value] of Object.entries( batAjax.questionsData )){
            let foundObject = Object.values(this.answers).find(obj => obj.question_id === value.question_id );
            updatedAnswers[key] = foundObject;
        }
        this.answers = updatedAnswers;

    }

}
