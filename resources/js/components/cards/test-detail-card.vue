<template>
    <div class="lesson-detail card my-4">
        <div class="card-header bg-secondary">
            <div class="row">
                <div class="col-6">
                    <h5 class="mb-0 col-6">{{ data.index + 1 }}</h5>
                </div>
                <div class="col-6 ms-auto text-end">
                    <span class="text-danger fs-5" @click="this.remove()" title="Delete this detail"><i
                            class="far fa-trash-alt me-2" aria-hidden="true"></i></span>
                </div>
                <div class="form-check form-switch ps-0 mb-3 col-12 justify-content-end">
                    <input id="multipleChoice" class="form-check-input ms-0" type="checkbox" name="multipleChoice"
                        v-model="multipleChoice" />
                    <label class="form-check-label" for="rememberMe">
                        Multiple choice
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body bg-secondary">
            <div v-if="multipleChoice">
                <div class="row">
                    <input type="hidden" class="id" v-model="data.id">
                    <div class="col-md- col-12">
                        <label>Question</label>
                        <textarea class="form-control form-control-lg" rows="8" v-model="data.question"></textarea>
                    </div>
                    <div class="col-md-4 col-3">
                        <button class="btn btn-lg btn-primary mt-3 w-100" @click="addAnswer">
                            Add Answer
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-12 mt-2" v-for="(answer, index) in answerOptions">
                        <!-- <div class="form-check col-1">
                            <input class="form-check-input" type="radio" id="correcr_answer" value="correcr_answer" v-model="correcr_answer">
                        </div> -->
                        <div class="form-check">
                            <input class="form-check-input" type="radio" :id="'answer' + index" :name="'answer' + data.id"
                                :value="answer.answer" v-model="data.correct_answer">
                        </div>
                        <div class="justify-content-end">
                            <button type="button" class="btn-close" @click="this.removeAnswer()"> </button>
                        </div>
                        <input class="form-control" type="text" for="rememberMe" v-model="answer.answer" />
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row">
                    <input type="hidden" class="id" v-model="data.id">
                    <div class="col-12">
                        <label>Question</label>
                        <textarea class="form-control" rows="8" v-model="data.question"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Test Detail Card",
    props: {
        data: {
            type: Object,
            default: null,
            answers: null,
        },
    },
    data() {
        return {
            multipleChoice: true,
            answer_option: [],
        };
    },
    mounted() {
        this.init();
    },
    methods: {
        // remove this card
        remove() {
            this.$emit('remove', {
                data: this.data,
            });
        },
        init() {
            this.answer_option = [];
            if (this.data.answers && this.data.answers.length) {
                this.data.answers.forEach(answer => {
                    this.answer_option.push({
                        answer: answer.answer || '',
                        answer_option: this.answer_option = {
                            answer(index) {
                                return this.answer_option[index].answer;
                            }
                        }
                    });
                });
            } else {
                // init 1 answer when start
                this.answer_option.push({
                    index: 0,
                    answer: '',
                    is_correct: false,
                });
            }
            this.data.correct_answer = '';
            console.log(this.answer_option);
        },
        addAnswer() {
            // add new answer
            this.answer_option.push({
                index: this.answer_option.length,
                answer: '',
                is_correct: false,
            });
        },
        removeAnswer(index) {
            // remove selected answer
            this.answer_option.splice(index, 1);

        },
    },
    computed: {
        answerOptions() {
            return this.answer_option.map((option, index) => ({
                index,
                answer: option.answer
            }));
        },
        answerOptionsWithIndex() {
            return this.answer_option.map((option, index) => ({
                index,
                answer: option.answer,
                is_correct: this.data.correct_answer === index
            }));
        }
    },

};
</script>

<style>
.lesson-detail .card-header {
    border-radius: 1rem 1rem 0 0 !important;
    padding-bottom: 0 !important;
}

.lesson-detail .card-body {
    padding-top: 0 !important;
    border-radius: 0 0 1rem 1rem !important;
}

.lesson-detail .card-body textarea {
    height: 100px;
}

.lesson-detail .card-body .form-control-sm {
    height: 100px;
    width: 200px;
}
</style>
