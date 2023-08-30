const thetQuestions = new Questions( thetAjax.questionsData );
const thetAnswers = new Answers();
const thetInterface = new Interface();
const thetConnector = new Connector( thetAjax.nonce );

thetInterface.showLoading( true );

window.addEventListener('load', async function(){

    if ( thetConnector.applicationIdExists === true ){

        thetAnswers.clearAnswersFromBrowser();

        await thetConnector.getApplicationData();

        if ( thetConnector.applicationData === false || thetConnector.applicationData === "" ) {

            thetAnswers.saveAnswersToBrowser();
            console.log( 'No data yet stored' );

        } else {

            thetAnswers.answers = thetConnector.applicationData;
			thetAnswers.saveAnswersToBrowser();
            console.log( 'Answers configured' );

        }

        thetInterface.autoSaveing( true );
        thetInterface.updateWheel();
        thetInterface.showLoading( false );

    } else {

        thetInterface.showError( title = 'No application ID found', content = 'Try going to the list of the applications and opening it again' );

    }

})


