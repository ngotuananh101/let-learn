<template>
    <div class="container pt-5">
        <div class="card p-3" v-if="lesson_details">
            <div class="card-body">
                <p>Question</p>
                <h5 class="card-title text-sans-serif pb-5">{{ lesson_details[currentQuestion].question }}</h5>
                <button class="btn btn-primary mb-3" @click="finishTest()">Finish test</button>
                <div class="row">
                    <div
                        class="col-md-6 pb-3"
                        v-for="(answer, index) in lesson_details[currentQuestion].answers"
                        :key="index"
                    >
                        <div
                            class="card ans-card"
                            :id="'answer-' + index"
                            :class="{ 'bg-secondary': lesson_details[currentQuestion].isSelected === index }"
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

        <div class="card mt-4" v-if="lesson_details">
            <div class="card-body d-flex justify-content-center">
                <div class="d-flex justify-content-start flex-wrap w-100 ml-auto">
                    <button v-for="(question, index) in lesson_details"
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
    </div>
</template>
<script>
import {mapActions} from "vuex";

export default {
    name: "Test",
    data() {
        return {
            id: this.$route.params.id,
            lesson_details: null,
            currentQuestion: 0,
            bg: ['rgb(219, 238, 255)', 'rgb(253, 240, 227)', 'rgb(230, 223, 242)', 'rgb(235, 242, 223)'],
            ans_icon: ['A', 'B', 'C', 'D'],
            userAnswers: [],
            show_result: false,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "learn/request") {
            } else if (mutation.type === "learn/requestSuccess") {
                this.lesson_details = mutation.payload;
                this.lesson_details.forEach(question => {
                    question.isSelected = null;
                    question.isCorrect = false;
                });
                console.log(this.lesson_details);
            } else if (mutation.type === "learn/requestFailure") {
            }
        });
        this.$store.dispatch("learn/getTest", this.id);
    },
    methods: {
        ...mapActions({
            getQ: 'learn/getTest'
        }),
        checkAnswer(index) {
// Set the selected answer for the current question
            this.lesson_details[this.currentQuestion].isSelected = index;
            // Check if the selected answer is correct and set the isCorrect property accordingly
            const question = this.lesson_details[this.currentQuestion];
            question.isCorrect = question.answers[index] === question.answers;

            // Remove the "bg-secondary" class from all answers
            const answers = document.getElementsByClassName("ans-card");
            for (let i = 0; i < answers.length; i++) {
                answers[i].classList.remove("bg-secondary");
            }

            // Add the "bg-secondary" class to the selected answer
            document.getElementById("answer-" + index).classList.add("bg-secondary");

        },
        finishTest() {
            // this.show_result = true;
            if (confirm('Are you sure you want to finish the test?')) {
                let answers = [];
                this.lesson_details.forEach(question => {
                    answers.push({
                        question_id: question.id,
                        answer: question.answers[question.isSelected]
                    });
                });
                // this.$store.dispatch('home/finished', {
                //     quiz_id: this.id,
                //     answers: answers
                // }).then((res) => {
                //     this.show_result = true;
                // });
            }
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
.card-title{
    white-space: pre-line;
}
</style>
