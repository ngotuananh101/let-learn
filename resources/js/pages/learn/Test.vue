<template>
    <div class="container" v-if="questions && !show_result">
        <div class="row">
            <div class="card p-3 col-7 mx-5">
                <div class="card-body">
                    <p>Question</p>
                    <h5 class="card-title text-sans-serif pb-5">{{ questions[currentQuestion].question }}</h5>
                    <div class="row">
                        <div class="col-md-6 pb-3" v-for="(ans, index) in  questions[currentQuestion].answers" :key="index"
                            @click="checkAnswer(index)">
                            <div class="card ans-card" :id="'answer-' + index">
                                <div class="card-body d-flex align-items-center">
                                    <span
                                        class="card-text p-2 rounded-circle d-flex justify-content-center align-items-center"
                                        :style="'width: 2rem; height: 2rem; background-color:' + bg[index]">{{
                                            ans_icon[index]
                                        }}</span>
                                    <p class="card-text ps-2">{{ ans }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col-4">
                <div class="card-body">
                    <!-- create a table containing pagination of questions -->
                    <p>Question</p>
                    <table class="table table-borderless">
                        <tbody>
                            <tr v-for="n in Math.ceil(questions.length / 5)" :key="n">
                                <td v-for="m in 5" :key="m">
                                    <button class="rounded-circle"
                                        :class="{ 'btn-primary': questions[(n - 1) * 5 + m - 1].isSelected !== null }"
                                        @click="changeQuestion((n - 1) * 5 + m - 1)">
                                        {{ (n - 1) * 5 + m }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button @click="finishTest">Finish Test</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-md-5" v-if="show_result">
        <h2>Learn Results</h2>
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
                            <p class="card-text text-success">Correct answer: {{ question.answers[question.isSelected] }}
                            </p>
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
                            <p class="card-text text-danger">Your answer: {{ question.answers[question.isSelected] }}
                            </p>
                            <p class="card-text text-success">Correct answer: {{ question.correct_answer }}
                            </p>
                        </div>
                        <!-- <div class="card-body" v-if="question.isSelected === null">
                            <h5 class="card-title">{{ question.question }}</h5>
                            <p class="card-text text-danger">Your answer: You have not selected an answer
                            </p>
                            <p class="card-text text-success">Correct answer: {{ questions[currentQuestion].correct_answer
                            }}
                            </p>
                        </div> -->
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "Self-Testing",
    data() {
        return {
            id: this.$route.params.id,
            questions: null,
            currentQuestion: 0,
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
            userAnswers: [],
            show_result: false
        };
    },
    created() {
        this.$store.dispatch("learn/getTest", this.id).then(
            question => {
                this.questions = question.map(q => ({
                    ...q,
                    isSelected: null,
                    isCorrect: false
                }));
            });
    },
    computed: {
        numCorrectAnswers() {
            return this.questions.filter(q => q.isCorrect).length;
        }
    },
    methods: {
        ...mapActions({
            getTest: 'learn/getTest'
        }),
        //remove the bg-secondary class from all answers when the user selects a new question
        changeQuestion(index) {
            this.currentQuestion = index;
            const answers = document.getElementsByClassName("ans-card");
            for (let i = 0; i < answers.length; i++) {
                answers[i].classList.remove("bg-secondary");
            }
        },

        checkAnswer(index) {
    // Remove the "bg-secondary" class from all answers
    const answers = document.getElementsByClassName("ans-card");
    // Get the current question object
    const question = this.questions[this.currentQuestion];
    for (let i = 0; i < answers.length; i++) {
        answers[i].classList.remove("bg-secondary");
    }
    // Add the "bg-secondary" class to the selected answer
    document.getElementById("answer-" + index).classList.add("bg-secondary");
    // Set the selected answer for the current question
    question.isSelected = index;
    // Compare the selected answer with the correct answer
    if (index === -1) {
        question.isCorrect = null; // mark question as unanswered
    } else if (question.answers[index] === question.correct_answer) {
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
.rounded-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #007bff;
    background-color: #fff;
    color: #007bff;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 5px;
}
</style>
