<template>
    <div class="container mt-4">
        <div class="card p-4">
            <h3>Ask question</h3>
            <form @submit.prevent="onSubmit">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Title of question: </span>
                    <input type="text" class="form-control" id="questionTitle" v-model="newQuestion.title">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Tag: </span>
                    <input type="text" class="form-control" id="questionTopic" v-model="newQuestion.topic">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="questionContent" rows="5"
                              v-model="newQuestion.content"></textarea>
                    <label for="floatingTextarea2">Question</label>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Questions</h1>
            </div>
            <div class="card-body">
                <div v-for="question in posts" :key="question.id" class="mb-3">
                    <div class="row">
                        <div class="col-1">
                            <div class="vote-count text-2xl">{{ question.votes }} <i class="fa-regular fa-thumbs-up"></i></div>
                            <div class="answer-count">{{ question.comment }} answers</div>
                            <div class="view-count">{{ question.views }} views</div>
                        </div>
                        <div class="col-9">
                            <div>
<!--                                <router-link :to="{ name: 'home.forum_detail' }">-->
                                    <div class="question-title font-weight-bold text-2xl">{{ question.title }}</div>
<!--                                </router-link>-->
                            </div>
                            <div class="topic text-info">{{ question.tags }}</div>
                            <div class="question-content">{{ question.content }}</div>
                        </div>
                        <div class="col-2">
<!--                            <div class="user-info">-->
<!--                                <img :src="question.avatar" class="me-2 rounded-circle" width="30" height="30" alt="">-->
<!--                                <span class="user-name">{{ question.user }}</span>-->
<!--                            </div>-->
                        </div>
                        <hr class="mt-2">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            newQuestion: {
                title: "",
                topic: "",
                content: "",
                user: "Anonymous",
                avatar: "https://randomuser.me/api/portraits/men/5.jpg",
                votes: 0,
                answers: 0,
                views: 0
            }
        };
    },created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.posts = mutation.payload;
                console.log(this.posts);
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getForum");
    },

    methods: {
        onSubmit() {
            //validate the form
            if (!this.newQuestion.title || !this.newQuestion.topic || !this.newQuestion.content) {
                alert("Please fill in all fields.");
                return;
            }
            // handle form submission
            // add the new question to the questions array
            this.questions.push(Object.assign({}, this.newQuestion, {id: this.questions.length + 1}));
            // reset the form
            this.newQuestion.title = "";
            this.newQuestion.topic = "";
            this.newQuestion.content = "";
            // close the modal
            $("#askQuestionModal").modal("hide");
        },
    }
}
</script>

<style>
</style>
