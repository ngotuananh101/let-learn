<template>
    <div class="container">
        <div class="card p-3" v-if="questions && !show_result">
            <div class="card-body">
                <p>Question</p>
                <h5 class="card-title text-sans-serif pb-5">{{ questions[currentQuestion].question }}</h5>
                <button class="btn btn-primary mb-3" @click="finishTest()">Finish test</button>
                <div class="row">
                    <div
                        class="col-md-6 pb-3"
                        v-for="(answer, index) in questions[currentQuestion].answer_option"
                        :key="index"
                    >
                        <div
                            class="card ans-card"
                            :id="'answer-' + index"
                            :class="{ 'bg-secondary': questions[currentQuestion].isSelected === index }"
                            @click="checkAnswer(index)"
                        >
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

        <div class="card mt-4" v-if="questions && !show_result">
            <div class="card-body d-flex justify-content-center">
                <div class="d-flex justify-content-start flex-wrap w-100 ml-auto">
                    <button v-for="(question, index) in questions"
                            :key="index"
                            class="rounded-circle btn-pagination mb-3"
                            :class="{
                'btn-success': question.isSelected !== null,
                'btn-secondary': question.isSelected === null || show_result
              }"
                            @click="currentQuestion = index">
                        {{ index + 1 }}
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4" v-if="show_result">
            <div class="card-body">
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
                                    <p class="card-text text-danger">Your answer:
                                        {{ question.answer_option[question.isSelected] }}</p>
                                    <p class="card-text text-success">Correct answer: {{ question.correct_answer }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary position-fixed bottom-0 end-0 mt-3 ms-3" @click="backPage" type="button"><i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapActions} from "vuex";

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
            getQ: 'home/doQuiz'
        }),
        checkAnswer(index) {
// Set the selected answer for the current question
            this.questions[this.currentQuestion].isSelected = index;
            // Check if the selected answer is correct and set the isCorrect property accordingly
            const question = this.questions[this.currentQuestion];
            question.isCorrect = question.answer_option[index] === question.correct_answer;

            // Remove the "bg-secondary" class from all answers
            const answers = document.getElementsByClassName("ans-card");
            for (let i = 0; i < answers.length; i++) {
                answers[i].classList.remove("bg-secondary");
            }

            // Add the "bg-secondary" class to the selected answer
            document.getElementById("answer-" + index).classList.add("bg-secondary");

            // Move to the next question if all questions have been answered
            if (this.questions.every(q => q.isSelected !== null)) {
                this.finishTest();
            }
        },
        finishTest() {
            this.show_result = true;
            this.questions.forEach(question => {
                this.userAnswers.push({
                    question: question.question,
                    selectedAnswer: question.answer_option[question.isSelected],
                    isCorrect: question.isCorrect
                });
            });
        },
        backPage() {
            this.$router.push({name: 'home.class'});
        }
    },
};
</script>

<style scoped>

.btn-pagination {
    width: 3rem;
    height: 3rem;
    margin-right: 1rem;
}
</style>
