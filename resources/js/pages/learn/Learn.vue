<template>
    <div class="container">
        <div class="card p-3">
            <div class="card-body">
                <p>Question</p>
                <h5 class="card-title text-sans-serif pb-5">{{ question }}</h5>
                <div class="row">
                    <div
                        class="col-md-6 pb-3"
                        v-for="(ans, index) in answer"
                        :key="index"
                        :id="'answer-' + index"
                    >
                        <div
                            class="card"
                            :class="{ 'bg-success': selected === ans && isCorrect, 'bg-danger': selected === ans && !isCorrect }"
                            @click="checkAnswer(index)"
                        >
                            <div class="card-body d-flex align-items-center ">
                <span
                    class="card-text p-2 rounded-circle d-flex justify-content-center align-items-center"
                    :style="'width: 2rem; height: 2rem; background-color:'+ bg[index]"
                >{{ ans_icon[index] }}</span>
                                <p class="card-text ps-2">{{ ans }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            question: "",
            answer: "",
            correctAnswer: "",
            selected: "",
            progress: 0,
            totalQuestions: 5,
            showSettings: false,// Change this to the number of questions you have
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
        };
    },
    created() {
        this.setQuestion();
    },
    methods: {
        setQuestion() {
            // example questions
            const questions = [
                {
                    question:
                        "Costs included in the Merchandise Inventory account can include:",
                    answers: [
                        "Invoice price minus any discount, Transportation-in, Storage, Insurance",
                        "Invoice price plus any discount, Transportation-in, Storage, Insurance",
                        "Invoice price minus any discount, Transportation-out, Storage, Insurance",
                        "All of these",
                    ],
                    correctAnswer:
                        "Invoice price minus any discount, Transportation-in, Storage, Insurance",
                },
                {
                    question: "Who invented the telephone?",
                    answers: [
                        "Alexander Graham Bell",
                        "Thomas Edison",
                        "Nikola Tesla",
                        "Michael Faraday",
                    ],
                    correctAnswer: "Alexander Graham Bell",
                },
                {
                    question:
                        "Hefty Company wants to know the effect of different inventory methods on financial statements. Given below is information about beginning inventory and purchases for the current year.\n" +
                        "January 2 Beginning Inventory: 500 units at $3.00\n" +
                        "April 7 Purchased : 1,100 units at $3.20\n" +
                        "June 30 Purchased : 400 units at $4.00\n" +
                        "December 7 Purchased : 1,600 units at $4.40\n" +
                        "Sales during the year were 2,700 units at $5.00. If Hefty used the periodic LIFO method, cost of goods sold would be:",
                    answers: ["$2,780", "$3,960", "$3,780", "$1,964"],
                    correctAnswer: "$3,960",
                },
                {
                    question: "Who invented the car?",
                    answers: [
                        "Alexander Graham Bell",
                        "Long",
                        "Nikola Tesla",
                        "Michael Faraday",
                    ],
                    correctAnswer: "Long",
                },
                {
                    question: "What is the largest planet in our solar system?",
                    answers: ["Saturn", "Jupiter", "Mars", "Uranus"],
                    correctAnswer: "Jupiter",
                },
            ];

            // Get a random question and its answers from the array
            const randomQuestion =
                questions[Math.floor(Math.random() * questions.length)];
            this.question = randomQuestion.question;
            this.answer = randomQuestion.answers;
            this.correctAnswer = randomQuestion.correctAnswer;
            this.selected = '';
            this.isCorrect = false;
        },
        checkAnswer(selectedAnswerIndex) {
            const selectedAnswer = this.answer[selectedAnswerIndex];
            this.selected = selectedAnswer;
            this.isCorrect = selectedAnswer === this.correctAnswer;

            if (this.isCorrect) {
                const progressIncrement = 100 / this.totalQuestions;
                this.progress += progressIncrement;
                this.$emit('change-progress', this.progress);
            }

            setTimeout(() => {
                this.setQuestion();
            }, 500);
        },
        toggleSettings() {
            // add toggleSettings method
            this.showSettings = !this.showSettings;
        },
    },
}
;
</script>

<style scoped>

</style>
