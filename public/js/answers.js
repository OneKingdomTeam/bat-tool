class Answers {

    constructor() {

        this.answers = {

            'set0' : {

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
            'set1' : {

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

            'set2' : {

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

            'set3' : {

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

            'set4' : {

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

            'set5' : {

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

            'set6' : {

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

            'set7' : {

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

            'set8' : {

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

            'set9' : {

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

            'set10' : {

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

            'set11' : {

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

            'set12' : {

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

        this.initLoad();

    }

    initLoad(){

        if ( window.localStorage.getItem( 'thetAnswers' ) == null ) {

            this.saveAnswersToBrowser();

        } else {

            this.getAnswersFromBrowser();

        }

    }

    saveAnswersToBrowser(){

        let answers = JSON.stringify( this.answers );
        window.localStorage.setItem( 'thetAnswers', answers );

    }

    getAnswersFromBrowser(){

        let answers = JSON.parse( window.localStorage.getItem( 'thetAnswers' ) );
        this.answers = answers;
        return( this.answers );

    }

    clearAnswersFromBrowser(){

        window.localStorage.removeItem( 'thetAnswers' );
        location.reload();

    }

    updateAnswers( activeBeam, activeSegment, activeQuestion, activeAnswer, textarea ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );

        let answerValue = this.extractAnswerValue( activeAnswer );
        let commentValue = textarea.value;

        this.answers['set' + beamNumber]['segment' + segmentNumber]['answers']['answer' + questionNumber] = answerValue;
        this.answers['set' + beamNumber]['segment' + segmentNumber]['comments']['comment' + questionNumber] = commentValue;
        this.saveAnswersToBrowser();

    }

    returnStoredAnswerValue( activeBeam, activeSegment, activeQuestion ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );
        let value = this.answers['set' + beamNumber]['segment' + segmentNumber]['answers']['answer' + questionNumber];
        return( value );

    }

    returnStoredCommentValue( activeBeam, activeSegment, activeQuestion ){

        let beamNumber = this.getElementClassNumber( activeBeam );
        let segmentNumber = this.getElementClassNumber( activeSegment );
        let questionNumber = this.getElementClassNumber( activeQuestion );
        let value = this.answers['set' + beamNumber]['segment' + segmentNumber]['comments']['comment' + questionNumber];
        return( value );

    }

    extractAnswerValue( activeAnswer ){

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

        let classList = element.getAttribute('class');
        let number = classList.replace( /[^0-9]/g,"");
        return( number );

    }

}
