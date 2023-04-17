<template>
    <div class="container pt-5 pb-5">
        <div class="card p-3" v-if="lesson_details && !show_result">
            <div class="card-body">
                <p>Question:</p>
                <h5 class="card-title text-sans-serif pb-5">{{ lesson_details[currentQuestion].question }}</h5>
                <div class="row" v-if="lesson_details[currentQuestion].answers !== null">
                    <!-- Display multiple-choice answer options -->
                    <div class="col-md-6 pb-3" v-for="(answer, index) in lesson_details[currentQuestion].answers"
                        :key="index">
                        <div class="card ans-card" :id="'answer-' + index"
                            :class="{ 'bg-secondary': lesson_details[currentQuestion].isSelected === index }"
                            @click="checkAnswer(index)">
                            <div class="card-body d-flex align-items-center">
                                <span class="card-text p-2 rounded-circle d-flex justify-content-center align-items-center"
                                    :style="'width: 2rem; height: 2rem; background-color:' + bg[index]">
                                    {{ ans_icon[index] }}
                                </span>
                                <p class="card-text ps-2">{{ answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <!-- Display textarea for essay answer -->
                    <textarea class="form-control" v-model="essayAnswer" rows="4"></textarea>
                </div>
                <div class="row mt-5">
                    <!-- Pagination buttons -->
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="d-flex justify-content-start flex-wrap w-100 ml-auto">
                            <button v-for="(question, index) in lesson_details" :key="index"
                                class="rounded-circle btn-pagination mb-3" :class="{
                                    'btn-success': question.isSelected !== null,
                                    'btn-secondary': question.isSelected === null || show_result
                                }" @click="currentQuestion = index">
                                {{ index + 1 }}
                            </button>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mb-3 mt-3" @click="finishTest">Finish test</button>
            </div>
        </div>
    </div>
    <div class="container mx-md-5 pt-5" v-if="show_result">
        <h2>Learn Results</h2>
        <p>You answered {{ numCorrectAnswers }} out of {{ lesson_details.length }} questions correctly.</p>
        <h3>Your score: {{ numCorrectAnswers / lesson_details.length * 10 }}</h3>
        <!-- Correct Answers -->
        <h3>Correct Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <div v-for="(answer, index) in userAnswers.correctAnswers" :key="'correct_' + index" class="col">
                <div class="card text-dark bg-light correct-answer">
                    <div class="card-body">
                        <h5 class="card-title">{{ answer.question }}</h5>
                        <p class="card-text text-success">Your answer: {{ answer.selectedAnswer }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Incorrect Answers -->
        <h3 class="p-3">Incorrect Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <div v-for="(answer, index) in userAnswers.incorrectAnswers" :key="'incorrect_' + index" class="col">
                <div class="card text-dark bg-light incorrect-answer">
                    <div class="card-body">
                        <h5 class="card-title">{{ answer.question }}</h5>
                        <p class="card-text text-danger">Your answer: {{ answer.selectedAnswer }}</p>
                        <p class="card-text text-success">Correct answer: {{ answer.correctAnswer }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary position-fixed bottom-0 end-0 mt-3 ms-3" @click="comebackHome"
                    type="button"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    name: "Self test",
    data() {
        return {
            id: this.$route.params.id,
            lesson_details: null,
            currentQuestion: 0,
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
            userAnswers: [],
            show_result: false,
            type: "getSelfTest",
            question: null,
            user: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "learn/request") {
            } else if (mutation.type === "learn/requestSuccess") {
                if (this.type === 'getSelfTest') {
                    this.lesson_details = mutation.payload;
                    console.log(this.lesson_details);
                    this.lesson_details.forEach((question) => {
                        question.isSelected = null;
                        question.isCorrect = null;
                    });
                }
            } else if (mutation.type === "learn/requestFailure") {
            }
        });
        this.$store.dispatch("learn/getSelfTest", { id: this.id, roleName: this.user.role.name});
    },
    computed: {
        numCorrectAnswers() {
            return this.lesson_details.filter(q => q.isCorrect).length;
        }
    },
    methods: {
        ...mapActions({
            getQ: 'learn/getSelfTest'
        }),
        checkAnswer(index) {
            // Set the selected answer for the current question
            this.lesson_details[this.currentQuestion].isSelected = index;
            // Check if the selected answer is correct and set the isCorrect property accordingly
            const question = this.lesson_details[this.currentQuestion];
            question.isCorrect = question.answer_option[index] === question.answer_option;

            // Remove the "bg-secondary" class from all answers
            const answer_option = document.getElementsByClassName("ans-card");
            for (let i = 0; i < answers.length; i++) {
                answer_option[i].classList.remove("bg-secondary");
            }

            // Add the "bg-secondary" class to the selected answer
            document.getElementById("answer-" + index).classList.add("bg-secondary");

        },
        finishTest() {
            this.show_result = true;
            const correctAnswers = [];
            const incorrectAnswers = [];

            this.lesson_details.forEach((question) => {
                if (question.isSelected !== null) { // If an answer was selected
                    const selectedAnswer = question.answers[question.isSelected];
                    if (selectedAnswer === question.correct_answer) { // If selected answer is correct
                        correctAnswers.push({
                            question: question.question,
                            selectedAnswer: selectedAnswer
                        });
                        question.isCorrect = true;
                    } else { // If selected answer is incorrect
                        incorrectAnswers.push({
                            question: question.question,
                            selectedAnswer: selectedAnswer,
                            correctAnswer: question.correct_answer
                        });
                        question.isCorrect = false;
                    }
                }
            });

            this.userAnswers = {
                correctAnswers,
                incorrectAnswers
            };
        },

        comebackHome() {
            this.$router.push({ name: 'lesson.index' });
        },
    },
};
</script>

<style scoped>
.btn-pagination {
    width: 3rem;
    height: 3rem;
    margin-right: 1rem;
}

.card-title {
    white-space: pre-line;
}
</style>
