<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2" v-if="this.post">
                    <div class="card-header d-flex">
                        <img :src="post.author.avatar" alt="user avatar"
                             class="img img-fluid rounded-circle avatar me-3">
                        <div>
                            <h6 class="mb-0">{{ post.author.name }}</h6>
                            <p class="mb-0">{{ post.created_at }}</p>
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <h6 class="card-title">{{ post.title }}</h6>
                        <p class="mt-3 content-text">{{ post.content }}</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <div class="d-flex align-items-center">
                            <i :class="'fa-light fa-heart me-2' + (post.liked ? ' text-danger' : '')"
                               :data-id="post.id" @click="likePost()"></i>
                            <p class="mb-0">{{ post.like_count }} likes</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-eye me-2"></i>
                            <p class="mb-0">{{ post.views }} views</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-comment me-2"></i>
                            <p class="mb-0">{{ post.comment_count }} comments</p>
                        </div>
                    </div>
                    <hr>
                    <div v-if="comments" class="px-3">
                        <p v-if="comments.length === 0" class="text-center my-3 fw-bold">This post don't have any comment</p>
                        <div v-for="comment in comments">
                            <div class="d-flex mb-4">
                                <img :src="comment.author.avatar" alt="user avatar"
                                     class="img img-fluid rounded-circle avatar-sm me-3">
                                <div>
                                    <div class="bg-light p-2 mb-1" style="border-radius: 1rem;">
                                        <h6 class="mb-0 text-dark">{{ comment.author.name }}</h6>
                                        <p class="mb-0">{{ comment.comment }}</p>
                                    </div>
                                    <span
                                        v-show="this.user.id === comment.author.id || this.user.role.name === 'admin' || this.user.role.name === 'super'"
                                        class="me-lg-3 text-xs fw-bold" @click="deleteComment(comment.id)">Delete</span>
                                    <span class="text-xs">{{ comment.created_at }}</span>
                                </div>
                            </div>
                        </div>
                        <p v-if="post.next_page_url" class="fw-bold"
                           @click="loadMoreComment">
                            Load more comment
                        </p>
                    </div>
                    <hr>
                    <div class="card-footer">
                        <div class="d-flex w-100">
                            <img :src="getAvatarByEmail(this.user.email)" alt="user avatar"
                                 class="img img-fluid rounded-circle avatar-sm">
                            <textarea class="form-control mx-md-3" rows="1" placeholder="Write a comment..."></textarea>
                            <div class="d-flex justify-content-center align-items-center">
                                <i class="fa-sharp fa-solid fa-paper-plane fs-4" @click="addComment"></i>
                            </div>
                        </div>
                    </div>
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
            post: null,
            comments: [],
            next_page_url: null,
        };
    },

    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "forum/setPost") {
                this.post = mutation.payload.post;
                this.showComment();
            } else if (mutation.type === "forum/setComments") {
                this.comments = mutation.payload;
            } else if (mutation.type === "forum/failure") {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch("forum/getPostById", this.$route.params.id);
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        getAvatarByEmail(email) {
            let hash = MD5.generate(email);
            return `https://www.gravatar.com/avatar/${hash}?d=identicon`;
        },
        likePost() {
            let data = {
                post_id: this.post.id,
                type: this.post.liked ? 'unlike' : 'like'
            };
            this.$store.dispatch("forum/likePost", data).then(
                response => {
                    if (response) {
                        this.post.liked = !this.post.liked;
                        this.post.like_count = this.post.liked ? this.post.like_count + 1 : this.post.like_count - 1;
                    }
                }
            )
        },
        showComment() {
            let data = {
                post_id: this.post.id,
                page: 1,
            };
            this.$store.dispatch("forum/loadComments", data).then(
                response => {
                    if (response) {
                        this.comments = response.comments;
                        this.next_page_url = response.next_page_url;
                    }
                }
            );
        },
        loadMoreComment() {
            let data = {
                post_id: this.post.id,
                page: this.next_page_url.split('page=')[1],
            };
            this.$store.dispatch("forum/loadComments", data).then(
                response => {
                    if (response) {
                        if (response.comments.length === 0) {
                            this.$root.showSnackbar("No more comments", 'danger');
                        } else {
                            this.comments.push(...response.comments);
                            this.next_page_url = response.next_page_url;
                        }
                    }
                }
            );
        },
        addComment(e) {
            let data = {
                post_id: this.post.id,
                comment: e.target.closest('.card-footer').querySelector('textarea').value.trim(),
            };
            this.$store.dispatch("forum/addComment", data).then(
                response => {
                    if (response) {
                        this.comments.unshift(response.comment);
                        e.target.closest('.card-footer').querySelector('textarea').value = '';
                        this.post.comment_count++;
                    }
                }
            );
        },
        deleteComment(id){
            let data = {
                post_id: this.post.id,
                comment_id: id,
            };
            if(confirm('Are you sure?')){
                this.$store.dispatch("forum/deleteComment", data).then(
                    response => {
                        if (response) {
                            this.comments = this.comments.filter(comment => comment.id !== id);
                            this.post.comment_count--;
                        }
                    }
                );
            }
        },
    }
};
</script>
