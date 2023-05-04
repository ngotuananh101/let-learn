<template>
    <div class="container pt-8" v-if="lesson_details && !show_result">
        <div class="card p-3">
            <div class="card-body">
                <p>Question</p>
                <h5 class="card-title text-sans-serif pb-5">{{ lesson_details[currentQuestion].question }}</h5>
                <div class="row">
                    <div
                        class="col-md-6 pb-3"
                        v-for="(ans, index) in  lesson_details[currentQuestion].answers"
                        :key="index"
                        @click="checkAnswer(index)"
                    >
                        <div
                            class="card ans-card" :id="'answer-' + index"
                        >
                            <div class="card-body d-flex align-items-center">
                <span
                    class="card-text p-2 rounded-circle d-flex justify-content-center align-items-center"
                    :style="'width: 2rem; height: 2rem; background-color:'+ bg[index]"
                >{{ ans_icon[index] }}</span>
                                <p class="card-text ps-2">{{ ans }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none" id="next">
                    <button
                        class="btn btn-primary"
                        @keyup.enter="nextQuestion"
                        @click="nextQuestion"
                    >
                        Next Question
                    </button>
                    <div>
                        <p v-if="isCorrectAnswer" class="congrat">Congratulations!</p>
                        <p v-else class="encourage">Don't give up! Keep trying!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-md-5 pt-5" v-if="show_result">
        <h2>Learn Results</h2>
        <p>You answered {{ numCorrectAnswers }} out of {{ lesson_details.length }} questions correctly.</p>
        <h3>Correct Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <template v-for="(question, index) in lesson_details">
                <div :key="'correct_' + index" v-if="question.isCorrect && question.count_answered <= 1" class="col">
                    <div class="card text-dark bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ question.question }}</h5>
                            <p class="card-text text-success">Your answer: {{ question.correct_answer }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <h3 class="p-3">Incorrect Answers:</h3>
        <div class="row row-cols-1 g-4 p-3">
            <template v-for="(answer, index) in userAnswers">
                <div :key="'incorrect_' + index" v-if="!answer.isCorrect" class="col">
                    <div class="card text-dark bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ answer.question }}</h5>
                            <p class="card-text text-danger">Your answer: {{ answer.selectedAnswer }}</p>
                            <p class="card-text text-success">Correct answer:
                                {{ lesson_details.find(q => q.question === answer.question).correct_answer }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary position-fixed bottom-0 end-0 mt-3 ms-3" @click="reloadPage"
                        type="button"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
    <div class="container h-100 d-flex flex-column justify-content-center align-items-center" v-if="completed">
        <h1 class="text-success text-center pt-8">Congratulations on completing the lesson</h1>
        <div class="row pt-3 justify-content-center">
            <div class="col-md-auto col-sm-12">
                <button class="btn btn-primary d-flex align-items-center" @click="back" type="button">Back</button>
            </div>
            <div class="col-md-auto col-sm-12 mt-2 mt-md-0">
                <button class="btn btn-primary d-flex align-items-center" @click="relearn" type="button">Learn Again</button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "Self-Learning",
    data() {
        return {
            id: this.$route.params.id,
            lesson_details: null,
            currentQuestion: 0,
            isCorrectAnswer: false,
            answered: false,
            show_result: false,
            completed: false,
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
            progress: 0,
            userAnswers: []
        };
    },

    created() {
        this.user = this.$store.getters["user/userData"].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "learn/request") {
            } else if (mutation.type === "learn/requestSuccess") {
                if (mutation.payload.length > 0) {
                    this.lesson_details = mutation.payload;
                    console.log(this.lesson_details);
                } else {
                    console.log("No lesson details");
                    this.completed = true; // Set completed flag to true if lesson details are empty
                }
            } else if (mutation.type === "learn/requestFailure") {
                this.completed = true; // Set completed flag to true if there was an error fetching lesson details
            }
        });
        this.$store.dispatch("learn/getLearn", {id: this.id, roleName: this.user.role.name});
    },
    computed: {
        numCorrectAnswers() {
            return this.userAnswers.filter(answer => answer.isCorrect).length;
        }
    },
    methods: {
        ...mapActions({
            getLearn: 'learn/getLearn'
        }),
        checkAnswer(index) {
            if (this.answered) {
                return;
            } else {
                this.answered = true;
                let selectedAnswerIndex = index;
                const selectedAnswer = this.lesson_details[this.currentQuestion].answers[selectedAnswerIndex];
                let element = document.getElementById('answer-' + index);
                if (selectedAnswer === this.lesson_details[this.currentQuestion].correct_answer) {
                    element.classList.add('bg-success');
                    this.isCorrectAnswer = true;
                } else {
                    element.classList.add('bg-danger');
                    this.isCorrectAnswer = false;
                }
                this.userAnswers.push({
                    id: this.lesson_details[this.currentQuestion].id,
                    question: this.lesson_details[this.currentQuestion].question,
                    selectedAnswer: selectedAnswer,
                    isCorrect: this.isCorrectAnswer
                });
                if (this.currentQuestion === this.lesson_details.length - 1) {
                    const userAnswers = this.userAnswers;
                    const correctAnswers = userAnswers.filter(answer => answer.isCorrect).map(answer => answer.id);
                    const incorrectAnswers = userAnswers.filter(answer => !answer.isCorrect).map(answer => answer.id);
                    this.$store.dispatch('learn/updateResult', {
                        lesson_id: this.$route.params.id,
                        learned: correctAnswers,
                        relearn: incorrectAnswers,
                        user_id: this.user.id,
                        roleName: this.user.role.name
                    }).then((res) => {
                        this.show_result = true;
                    });
                } else {
                    document.getElementById('next').classList.remove('d-none');
                }
            }
        },


        nextQuestion() {
            this.answered = false;
            document.getElementById('next').classList.add('d-none');
            let ans_cards = document.getElementsByClassName('ans-card');
            for (let i = 0; i < ans_cards.length; i++) {
                ans_cards[i].classList.remove('bg-success');
                ans_cards[i].classList.remove('bg-danger');
            }

            if (this.currentQuestion === this.lesson_details.length - 1) {
                this.show_result = true;
            } else {
                if (this.lesson_details[this.currentQuestion].count_answered === 1) {
                    this.currentQuestion++;
                    this.progress = Math.round((this.currentQuestion / this.lesson_details.length) * 100);
                    this.$emit('change-progress', this.progress);
                } else {
                    this.lesson_details[this.currentQuestion].count_answered ? this.lesson_details[this.currentQuestion].count_answered++ : this.lesson_details[this.currentQuestion].count_answered = 1;
                    this.lesson_details[this.currentQuestion].isCorrect = this.isCorrectAnswer;
                    if (this.isCorrectAnswer) {
                        this.currentQuestion++;
                        this.progress = Math.round((this.currentQuestion / this.lesson_details.length) * 100);
                        this.$emit('change-progress', this.progress);
                    } else {
                        // move answer to the end of the array
                        this.lesson_details.push(this.lesson_details[this.currentQuestion]);
                        this.lesson_details.splice(this.currentQuestion, 1);
                    }
                }
            }
        },
        reloadPage() {
            location.reload();
        },
        relearn() {
            this.$store.dispatch('learn/relearn', {
                lesson_id: this.$route.params.id,
                user_id: this.user.id
            }).then((res) => {
                this.reloadPage();
            });
        },
        back() {
            this.$router.push({name: 'lesson.index'});
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
