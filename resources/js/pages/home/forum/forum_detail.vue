<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div v-if="post" class="col-md-12">
                    <div class="card mb-2">
                        <div class="card-header">
                            <h2>{{ post.title }}</h2>
                        </div>
                        <div class="card-body">
                            <p>{{ post.content }}</p>
                        </div>
                        <p class="text-xl-end p-lg-2">
                            <span class="bold">
                                Created on: {{ post.created_at }} | Tags:
                                <span v-for="(tag, index) in post.tags.split(',')" :key="index">{{ tag }}</span>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="card mb-3" v-for="(comment, index) in comments" :key="index">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <i class="fa-solid fa-caret-up text-success" @click="upvoteComment(index)"></i>
                                            <div class="col-md- justify-content-end align-items-center">{{ comment.votes }}</div>
                                            <i class="fa-solid fa-caret-down text-danger mt-2" @click="downvoteComment(index)"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 m-lg-2 d-flex align-items-center">
                                <div>{{ comment.comment }}</div>
                            </div>
                            <div class="col-md-1">
                                <div class="text-center">
                                    <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30" height="30" alt="">
                                    <div>{{ comment.user_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form @submit.prevent="submitComment">
                            <div class="form-group">
                                <label for="commentInput">Your Comment</label>
                                <textarea class="form-control" id="commentInput" v-model="newComment"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <!-- Show a loading animation while the comment is being submitted -->
                            <div v-if="loading" class="text-center mt-2">
                                <i class="fa-solid fa-spinner animate-spin"></i> Loading...
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Show a reloading animation after the comment is submitted and the page is being reloaded -->
                <div v-if="reloading" class="text-center mt-2">
                    <i class="fa-solid fa-spinner animate-spin"></i> Reloading...
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
            post: {
                title: "",
                content: "",
                created_at: "",
                tags: ""
            },
            comments: null,
            data: null,
            newComment: "",
            loading: false,
            reloading: false
        };
    },

    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.data = mutation.payload;
                this.post = this.data.post;
                this.comments = this.data.comments;
                console.log(this.post);
                console.log(this.comments);
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getForumDetail", this.$route.params.id);
    },

    methods: {
        submitComment() {
            if (!this.newComment) return;
            const data = {
                post_id: this.$route.params.id,
                comment: this.newComment,
                status: "active"
            };

            // Show the loading animation
            this.loading = true;

            this.$store.dispatch("home/commentForum", data)
                .then(response => {
                    console.log('Comment added successfully:', response);
                    this.newComment = "";
                    this.reloading = true;
                    setTimeout(() => {
                        this.loading = false;
                    }, 500);
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                    this.loading = false;
                });
        },
        upvoteComment(index) {
            const comment_id = this.comments[index].id;
            this.$store.dispatch("home/voteComment", { id: this.$route.params.id, comment_id, votetype: "upvote" });
            setTimeout(() => {
                location.reload();
            }, 500);
        },
        downvoteComment(index) {
            const comment_id = this.comments[index].id;
            this.$store.dispatch("home/voteComment", { id: this.$route.params.id, comment_id, votetype: "downvote" });
            setTimeout(() => {
                location.reload();
            }, 500);
        },
        getUserInfo() {
            let user = this.$store.getters['user/userData'].info;
            // get gravatar
            let email = user.email;
            let hash = MD5.generate(email);
            user.gravatar = `https://www.gravatar.com/avatar/${hash}?d=identicon`;
            return user;
        },
    },
    mounted() {
    }
};
</script>
