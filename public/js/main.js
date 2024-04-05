// Order matters. It is important first to load Quesions data to the batQuestions object
// then those data are used to match BEAM to it's propriate question_id
const batQuestions = new Questions( batAjax.questionsData );
const batAnswers = new Answers();
const batInterface = new Interface();
const batConnector = new Connector( batAjax.nonce );

batInterface.showLoading( true );

window.addEventListener('load', async function(){

    if ( batConnector.applicationIdExists === true ){

        batAnswers.clearAnswersFromBrowser();

        await batConnector.getApplicationData( batInterface.sessionKey );

        if ( batConnector.applicationData === false || batConnector.applicationData === "" ) {

            batAnswers.saveAnswersToBrowser();
            console.log( 'No data yet stored' );

        } else {

            batAnswers.answers = batConnector.applicationData;
            batAnswers.checkQuestionAnswerAlignment();
			batAnswers.saveAnswersToBrowser();
            console.log( 'Answers configured' );

        }

        if ( batConnector.recentResponseCode !== 200 ) {

            console.log('Data not loaded...');
            console.log(batConnector.applicationData.response);
            console.log(batConnector.applicationData.message);
            return;

        }

        batInterface.autoSaving( true );
        batInterface.updateWheel();
        batInterface.showLoading( false );

    } else {

        batInterface.showError( title = 'No application ID found', content = 'Try going to the list of the applications and opening it again' );

    }

})


