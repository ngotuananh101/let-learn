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
                                    <img src="https://i.pravatar.cc/150?img=7" class="rounded-circle d-flex align-items-center" alt="avatar" style="width: 50px;" />
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
                    // reset newComment
                    this.newComment = "";
// Hide the loading animationand show the reloading animation
                    this.reloading = true;
// Hide the loading animation after a short delay
                    setTimeout(() => {
                        this.loading = false;
                    }, 500);
// Reload the page after a delay, with a reloading animation
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
// handle error
                    this.loading = false;
                });
        },

        // ...
    }
};
</script>
