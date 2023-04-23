<template>
    <div class="container mx-lg-5">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Update quiz</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-3">Name</label>
                                <input id="name" name="name" class="form-control" type="text"
                                       placeholder="Enter a name. Example:'MAE Chapter 1'" v-model="this.quiz.name"/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-3">Description</label>
                                <input id="description" name="description" class="form-control" type="text"
                                       placeholder="Add a description..." v-model="this.quiz.description"/>
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Start time</label>
                                <input id="start_time" name="start_time" class="form-control" type="datetime-local"
                                       v-model="this.quiz.start_time"/>
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">End time</label>
                                <input id="end_time" name="end_time" class="form-control" type="datetime-local"
                                       v-model="this.quiz.end_time"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div v-for="(child, index) in cards">
                    <component :is="child" :data="data[index]" :key="index" @remove="removeQuestion"></component>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <button class="btn btn-lg btn-primary mt-3 w-100" @click="addQuestion">
                    Add Question
                </button>
            </div>
            <div class="col-md-4 col-6">
                <button class="btn btn-lg btn-success mt-3 w-100" @click="updateQuiz">
                    Update
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import {markRaw} from "vue";
import test_card from "../../../../components/cards/test-detail-card.vue";

export default {
    name: "Add",
    components: {
        test_card
    },
    data() {
        return {
            quiz: {
                id: 0,
                name: '',
                description: '',
                start_time: '',
                end_time: '',
            },
            data: [],
            cards: [],
            deleted_questions: [],
            unsubscribe: null,
        };
    },
    created() {
        this.$setPageTitle('Update Quiz');
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'classQuiz/setQuiz') {
                this.$setPageTitle('Update Quiz: ' + mutation.payload.quiz.name);
                this.quiz = mutation.payload.quiz;
                this.data = mutation.payload.questions;
                this.cards = this.data.map(() => markRaw(test_card));
            }if(mutation.type === 'classQuiz/failure'){
                this.$root.showSnackbar(mutation.payload, "danger");
            } else if (mutation.type === 'classQuiz/quizUpdated') {
                this.$root.showSnackbar('Quiz updated successfully', "success");
                this.$router.push({name: 'home.class', params: {id: this.$route.params.id}});
            }
        });
        this.$store.dispatch('classQuiz/getQuizById', {id: this.$route.params.id, quiz_id: this.$route.params.quiz_id});
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        addQuestion() {
            this.data.push({
                id: 0,
                question: '',
                score: 1,
                is_multiple_choice: true,
                answers: [
                    {
                        answer: '',
                        correct: true,
                    },
                ]
            });
            this.cards.push(markRaw(test_card));
        },
        removeQuestion(index) {
            if (this.data[index].id !== 0) {
                this.deleted_questions.push(this.data[index].id);
            }
            this.data.splice(index, 1);
            this.cards = this.data.map(() => markRaw(test_card));
        },
        updateQuiz() {
            this.$store.dispatch('classQuiz/updateQuiz', {
                class_id: this.$route.params.id,
                quiz_id: this.$route.params.quiz_id,
                name: this.quiz.name,
                description: this.quiz.description,
                start_time: this.quiz.start_time,
                end_time: this.quiz.end_time,
                questions: this.data,
                deleted_questions: this.deleted_questions,
            });
        }
    }
};
</script>
