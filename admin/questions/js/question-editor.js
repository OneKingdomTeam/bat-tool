
const thetJsonEditorElement = document.querySelector('.thet-question-editor-wrapper');

const jsonEditor = new JSONEditor( thetJsonEditorElement, {
    schema: {
        title: "Segment 1",
        type: "object",
        properties: {
            Question_1: {
                type: "string",
                default: thetQEditor.data[0].segment1.question1.title
            },
            Description_1: {
                type: "string",
                default: thetQEditor.data[0].segment1.question1.description
            },
            Question_2: {
                type: "string",
                default: thetQEditor.data[0].segment1.question2.title
            },
            Description_2: {
                type: "string",
                default: thetQEditor.data[0].segment1.question2.description
            },
            Question_3: {
                type: "string",
                default: thetQEditor.data[0].segment1.question3.title
            },
            Description_3: {
                type: "string",
                default: thetQEditor.data[0].segment1.question3.description
            },
            Question_4: {
                type: "string",
                default: thetQEditor.data[0].segment1.question4.title
            },
            Description_4: {
                type: "string",
                default: thetQEditor.data[0].segment1.question4.description
            },
        }
    },

} );
