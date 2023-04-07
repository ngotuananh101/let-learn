<template>
    <div class="container" v-if="questions && !show_result">
        <div class="card p-3">
            <div class="card-body">
                <p>Question</p>
                <h5 class="card-title text-sans-serif pb-5">{{ questions[currentQuestion].question }}</h5>
                <div class="row">
                    <div
                        class="col-md-6 pb-3"
                        v-for="(answer, index) in questions[currentQuestion].answer_option"
                        :key="index"
                        @click="checkAnswer(index)"
                    >
                        <div class="card ans-card" :id="'answer-' + index">
                            <div class="card-body d-flex align-items-center">
                                <span
                                    class="card-text p-2 rounded-circle d-flex justify-content-center align-items-center"
                                    :style="'width: 2rem; height: 2rem; background-color:' + bg[index]"
                                >
                                  {{ ans_icon[index] }}
                                </span>
                                <p class="card-text ps-2">{{ answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- create a table containing pagination of questions -->
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td v-for="(question, index) in questions" :key="index">
                            <button
                                class="rounded-circle"
                                :class="{ 'btn-primary': question.isSelected === null, 'btn-outline-primary': question.isSelected !== null }"
                                @click="currentQuestion = index"
                            >
                                {{ index + 1 }}
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button @click="finishTest">Finish Test</button>
            </div>
        </div>
    </div>

    <div class="container mx-md-5" v-if="show_result">
        <h2>Quiz Results</h2>
        <p>You answered {{ numCorrectAnswers }} out of {{ questions.length }} questions correctly.</p>
        <h3>Your score: {{ numCorrectAnswers / questions.length * 10 }}</h3>
        <h3>Correct Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <template v-for="(question, index) in questions">
                <!-- Check if the user's selected answer is correct -->
                <div :key="'correct_' + index" v-if="question.isCorrect == true" class="col">
                    <div class="card text-dark bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ question.question }}</h5>
                            <p class="card-text text-success">Correct answer: {{ question.correct_answer }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <h3 class="p-3">Incorrect Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <template v-for="(question, index) in questions">
                <!-- Check if the user's selected answer is not correct -->
                <div :key="'incorrect_' + index" v-if="question.isCorrect == false" class="col">
                    <div class="card text-dark bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ question.question }}</h5>
                            <p class="card-text text-danger">Your answer: {{ question.answer_option[question.isSelected] }}</p>
                            <p class="card-text text-success">Correct answer: {{ question.correct_answer }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "HomeQuiz",
    data() {
        return {
            id: this.$route.params.id,
            questions: null,
            currentQuestion: 0,
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
            userAnswers: [],
            show_result: false,
        };
    },
    created() {
        this.$store.dispatch("home/doQuiz", this.id).then(
            question => {
                this.questions = question;
                console.log(this.questions);
            });
    },
    computed: {
        numCorrectAnswers() {
            return this.questions.filter(q => q.isCorrect).length;
        }
    },
    methods: {
        ...mapActions({
            getQ: 'home/doQuiz'
        }),
        checkAnswer(index) {
            // Remove the "bg-secondary" class from all answers
            const answers = document.getElementsByClassName("ans-card");
            for (let i = 0; i < answers.length; i++) {
                answers[i].classList.remove("bg-secondary");
            }

            // Add the "bg-secondary" class to the selected answer
            document.getElementById("answer-" + index).classList.add("bg-secondary");
            // Set the selected answer for the current question
            this.questions[this.currentQuestion].isSelected = index;
            // Change the pagination button to show that the question has been answered
            this.questions[this.currentQuestion].isSelected = null;

            // Get the current question object
            const question = this.questions[this.currentQuestion];
            // Set the selected answer for the current question
            question.isSelected = index;
            // Compare the selected answer with the correct answer
            if (question.answers[index] === question.correct_answer) {
                question.isCorrect = true;
            } else {
                question.isCorrect = false;
            }

        },
        finishTest() {
            this.show_result = true;
            this.questions.forEach(question => {
                this.userAnswers.push({
                    question: question.question,
                    selectedAnswer: question.answers[question.isSelected],
                    isCorrect: question.answers[question.isCorrect]
                });
            });
        }

    },
};
</script>

<style scoped>
.answered {
    background-color: #ffc107;
    color: white;
}

.not-answered {
    background-color: #dc3545;
    color: white;
}

.congrat {
    font-weight: bold;
    color: green;
}

.encourage {
    font-weight: bold;
    color: red;
}
</style>
