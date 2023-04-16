<template>
    <div class="lesson-detail card my-4">
        <div class="card-header bg-secondary">
            <div class="row">
                <div class="col-6">
                    <h5 class="mb-0 col-4">{{ data.index + 1 }}</h5>
                </div>
                <div class="form-check form-switch ps-0 mb-3 col-6 justify-content-end">
                    <input id="multipleChoice" class="form-check-input ms-0" type="checkbox" name="multipleChoice"
                        v-model="multipleChoice" />
                    <label class="form-check-label" for="rememberMe">
                        Multiple choice
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body bg-secondary">
            <div v-if="this.multipleChoice">
                <div class="row">
                    <input type="hidden" class="id" v-model="data.id">
                    <div class="col-md-7 col-12">
                        <label>Question</label>
                        <textarea class="form-control form-control-lg" rows="8" v-model="data.question"></textarea>
                    </div>
                    <div class="col-md-5 col-12">
                        <label>Correct answer</label>
                        <textarea class="form-control form-control-lg" rows="8" v-model="data.correct_answer"></textarea>
                    </div>
                    <div class="col-md-6 col-12">
                        <label>Answer 1</label>
                        <textarea class="form-control" rows="8" v-model="data.answer_1"></textarea>
                    </div>
                    <div class="col-md-6 col-12">
                        <label>Answer 2</label>
                        <textarea class="form-control" rows="8" v-model="data.answer_2"></textarea>
                    </div>
                    <div class="col-md-6 col-12">
                        <label>Answer 3</label>
                        <textarea class="form-control" rows="8" v-model="data.answer_3"></textarea>
                    </div>
                    <div class="col-md-6 col-12">
                        <label>Answer 4</label>
                        <textarea class="form-control" rows="8" v-model="data.answer_4"></textarea>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row">
                    <input type="hidden" class="id" v-model="data.id">
                    <div class="col-md-7 col-12">
                        <label>Question</label>
                        <textarea class="form-control form-control-lg" rows="8" v-model="data.question"></textarea>
                    </div>
                    <div class="col-md-5 col-12">
                        <label>Correct answer</label>
                        <textarea class="form-control form-control-lg" rows="8" v-model="data.correct_answer"></textarea>
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
        },
    },
    data() {
        return {
            multipleChoice: true,
        };
    },
    methods: {
        // remove this card
        remove() {
            this.$emit('remove', {
                data: this.data,
            });
        },
        switch() {
            // switch question and correct answer
            let question = this.data.question;
            this.data.question = this.data.correct_answer;
            this.data.correct_answer = question;
        },

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

.lesson-detail .card-body .form-control-lg {
    height: 200px;
}
</style>
