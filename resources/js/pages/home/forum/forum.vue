<template>
    <div class="container mt-4">
        <div class="card p-4">
            <h3>Ask question</h3>
            <form @submit.prevent="onSubmit">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Title of question: </span>
                    <input type="text" class="form-control" id="questionTitle">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Tag: </span>
                    <input type="text" class="form-control" id="questionTopic">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="questionContent" rows="5"></textarea>
                    <label for="floatingTextarea2">Question</label>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
    <div class="container mt-4 pb-5">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Questions</h1>
            </div>
            <div class="card-body">
                <div v-for="(post, index) in posts" :key="post.id" class="mb-3">
                    <div class="row">
                        <div class="col-2">
                            <div class="vote-count text-2xl">{{ post.score_reporting }}
                                <i id="like-icon" class="fa-regular fa-thumbs-up" @click="upvoteQuestion(index)"></i></div>
                            <div class="answer-count">{{ post.comments.length }} answers</div>
                            <div class="view-count">{{ post.views }} views</div>
                        </div>
                        <div class="col-8">
                            <div>
                                <router-link :to="{ name: 'forum.forum_detail', params: { id: post.id } }">
                                    <div class="question-title font-weight-bold text-2xl">{{ post.title }}</div>
                                </router-link>
                            </div>
                            <div class="topic text-info">{{ post.tags }}</div>
                            <div class="question-content">{{ post.content }}</div>
                        </div>
                        <div class="col-2">
                            <div class="user-info">
                                <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30" height="30" alt="">
                                <span class="user-name">{{ post.name }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {MD5} from "md5-js-tools";

export default {
    data() {
        return {
            posts: [],
        };
    },
    created() {
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
            const title = document.getElementById("questionTitle").value;
            const content = document.getElementById("questionContent").value;
            const tags = document.getElementById("questionTopic").value;
            const status = "active";

            // Check if required fields are filled in
            if (!title || !content || !tags) {
                alert("Please fill in all required fields.");
                return;
            }

            // Dispatch action to add new question
            this.$store.dispatch("home/addQuestionForum", { title, content, tags, status });

            // Reload page with animation after submitting form
            location.reload(true);
        },
        upvoteQuestion(index){
            const id = this.posts[index].id;
            console.log(id);
            this.$store.dispatch("home/voteQuestion", { id: id, like: "like" });
            setTimeout(() => {
                location.reload();
            }, 1000);
        },
        getUserInfo() {
            let user = this.$store.getters['user/userData'].info;
            // get gravatar
            let email = user.email;
            let hash = MD5.generate(email);
            user.gravatar = `https://www.gravatar.com/avatar/${hash}?d=identicon`;
            return user;
        },
    }
}
</script>

<style>
</style>
