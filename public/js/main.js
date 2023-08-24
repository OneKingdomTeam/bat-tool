
let thetQuestions = new Questions( thetAjax.questionsData );
let thetAnswers = new Answers();
let thetInterface = new Interface();

window.addEventListener('load', function(){

    thetAnswers.clearAnswersFromBrowser();
    thetAnswers.saveAnswersToBrowser();
    thetInterface.updateWheel();

})
