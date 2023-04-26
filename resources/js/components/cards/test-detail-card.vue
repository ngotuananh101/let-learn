<template>
    <div class="test-detail card my-4">
        <div class="card-body row">
            <div class="col-md-8">
                <label class="form-label">Question</label>
                <input type="text" class="form-control fs-6 p-3" v-model="data.question">
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select class="form-select w-100 question-type" @change="changeOption">
                    <option value="true" :selected="data.is_multiple_choice">Multiple-choice</option>
                    <option value="false" :selected="!data.is_multiple_choice">Essay</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Score</label>
                <input type="number" class="form-control" v-model="data.score">
            </div>
            <div class="col-12 mt-3" v-if="data.is_multiple_choice">
                <label class="form-label">Answers</label>
                <div class="form-check" v-for="(answer, index) in data.answers">
                    <input
                        class="form-check-input"
                        type="radio"
                        :name="'answer-' + this.$.vnode.key"
                        :checked="answer.correct"
                        @change="changeCorrectAnswer(index)"
                    />
                    <input type="text" class="form-control ans-input" v-model="data.answers[index].answer"
                           placeholder="Answer">
                </div>
                <div class="form-check" @click="addAnswerOption">
                    <input
                        id="btn-add-answer"
                        class="form-check-input"
                        type="radio"
                        disabled
                    />
                    <label class="custom-control-label" for="btn-add-answer">
                        Add more answer
                    </label>
                </div>
            </div>
            <a href="#" class="mt-3 text-danger" @click="removeQuestion"><i class="fa-solid fa-trash me-2"></i>Delete
                this question</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "Test Detail Card",
    props: {
        data: {
            type: Object,
            required: true,
            id: {
                type: Number,
                required: false,
                default: 0,
            },
            question: {
                type: String,
                required: false,
                default: 'Question',
            },
            is_multiple_choice: {
                type: Boolean,
                required: true,
            },
            score: {
                type: Number,
                required: true,
            },
            answers: {
                type: Array,
                required: true,
                id: {
                    type: Number,
                    required: true,
                },
                answer: {
                    type: String,
                    required: true,
                },
                correct: {
                    type: Boolean,
                    required: true,
                }
            },
        },
    },
    methods: {
        remove() {
            this.$emit('remove', {
                data: this.data,
            });
        },
        addAnswerOption() {
            this.data.answers.push({
                answer: '',
                correct: false,
            });
        },
        changeCorrectAnswer(index) {
            this.data.answers.forEach((answer) => {
                answer.correct = false;
            });
            this.data.answers[index].correct = true;
        },
        changeOption() {
            this.data.is_multiple_choice = !this.data.is_multiple_choice;
        },
        removeQuestion() {
            this.$emit('remove', this.$.vnode.key);
        },
    },
};
</script>

<style>
.test-detail {
    border-left: 5px solid #165ec4;
    border-radius: 0.5rem;
}

.ans-input {
    border: none;
    border-radius: 0;
    outline: none;
}

.ans-input:focus {
    border-bottom: 1px solid #5016c4;
}
</style>
