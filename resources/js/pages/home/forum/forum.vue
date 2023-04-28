<template>
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="postTitle" v-model="new_post.title">
                    <label for="postTitle">Title</label>
                </div>
                <div class="form-floating mt-2">
                                    <textarea class="form-control" id="postContent" style="height: 10rem;"
                                              v-model="new_post.content"></textarea>
                    <label for="postContent">Question</label>
                </div>
                <button type="button" class="btn btn-primary m-0 mt-2" @click="addPost">Submit</button>
            </div>
        </div>
    </div>
    <div class="container mt-4 pb-5">
        <h3>Questions</h3>
        <div v-for="(post, index) in posts" :key="post.id" class="mb-3">
            <div class="card mt-3" :id="'post' + post.id">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <img :src="post.author.avatar" alt="user avatar"
                                 class="img img-fluid rounded-circle avatar me-3">
                            <div>
                                <h6 class="mb-0">{{ post.author.name }}</h6>
                                <p class="mb-0">{{ post.created_at }}</p>
                            </div>
                        </div>
                        <div class="dropdown"
                             v-show="this.user.id === post.author.id || this.user.role.name === 'admin' || this.user.role.name === 'super'">
                            <a href="#btnOption"
                               class="p-0 nav-link bg-light avatar-sm rounded-circle d-flex justify-content-center align-items-center"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul id="btnOption"
                                class="px-2 py-2 dropdown-menu dropdown-menu-end me-sm-n3"
                                aria-labelledby="btnNotification">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa-light fa-pen-to-square fs-6 fw-bold"></i>
                                        <p class="ms-1 mb-0"
                                           @click="this.showFormUpdatePost(post.id)">Update</p>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <i class="fa-light fa-trash fs-6 fw-bold"></i>
                                        <p class="ms-1 mb-0"
                                           @click="this.deletePost(post.id)">Delete</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ post.title }}</h6>
                    <p class="card-text content-text">{{ post.content }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-around mb-3">
                    <div class="d-flex align-items-center">
                        <i :class="'fa-light fa-heart me-2' + (post.liked ? ' text-danger' : '')"
                           :data-id="post.id" @click="likePost(index)"></i>
                        <p class="mb-0">{{ post.like_count }} likes</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa-light fa-eye me-2"></i>
                        <p class="mb-0">{{ post.views }} views</p>
                    </div>
                    <div class="d-flex align-items-center" @click="showComment(index)">
                        <i class="fa-light fa-comment me-2"></i>
                        <p class="mb-0">{{ post.comment_count }} comments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewPostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" v-if="post.post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Post of {{ post.post.author.name }}</h1>
                    <i class="fa-duotone fa-circle-xmark fs-2" data-bs-dismiss="modal"></i>
                </div>
                <div class="modal-body overflow-auto" style="max-height: 70vh;">
                    <div>
                        <h6 class="card-title">{{ post.post.title }}</h6>
                        <p class="mt-3 content-text">{{ post.post.content }}</p>
                    </div>
                    <hr>
                    <div v-if="post.comments">
                        <p v-if="post.comments.length === 0">This post don't have any comment</p>
                        <div v-for="comment in post.comments">
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
                </div>
                <div class="modal-footer">
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
    <div class="modal fade" id="updatePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update {{ update_post.title }}</h1>
                    <i class="fa-duotone fa-circle-xmark fs-2" data-bs-dismiss="modal"></i>
                </div>
                <div class="modal-body">
                    <div class="form-floating">
                        <input type="text" class="form-control" v-model="update_post.title">
                        <label for="postTitle">Title</label>
                    </div>
                    <div class="form-floating mt-2">
                                    <textarea class="form-control" style="height: 10rem;"
                                              v-model="update_post.content"></textarea>
                        <label for="postContent">Question</label>
                    </div>
                    <button type="button" class="btn btn-primary m-0 mt-2" @click="updatePost">Update</button>
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
            page: 1,
            last_page: 1,
            user: null,
            new_post: {
                title: '',
                content: '',
            },
            post: {
                post: null,
                comments: [],
                next_page_url: null,
            },
            view_post_modal: null,
            update_post: {
                id: null,
                title: '',
                content: '',
            },
            update_post_modal: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "forum/setPost") {
                if (mutation.payload.length === 0) {
                    this.$root.showSnackbar("No more questions", 'danger');
                } else {
                    this.posts.length === 0 ? this.posts = mutation.payload.posts : this.posts.concat(mutation.payload.posts);
                    this.page++;
                    this.last_page = mutation.payload.last_page;
                }
            } else if (mutation.type === "forum/failure") {
                this.$root.showSnackbar(mutation.payload, 'danger');
            } else if (mutation.type === "forum/postAdded") {
                this.$root.showSnackbar("Post added", 'success');
                this.posts.unshift(mutation.payload.post);
            }
        });
        this.$store.dispatch("forum/getPost", this.page);
    },
    mounted() {
        this.$root.showSnackbar("Welcome to forum", 'success');
        // if user is end of page, load more
        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                if (this.page <= this.last_page) {
                    this.$store.dispatch("forum/getPost", this.page);
                } else {
                    this.$root.showSnackbar("No more questions", 'danger');
                }
            }
        });
        const bootstrap = this.$store.state.config.bootstrap;
        this.view_post_modal = new bootstrap.Modal(document.getElementById('viewPostModal'), {
            keyboard: false
        });
        this.view_post_modal._element.addEventListener('hidden.bs.modal', () => {
            this.post = {
                post: null,
                comments: [],
            };
        });
        this.update_post_modal = new bootstrap.Modal(document.getElementById('updatePostModal'), {
            keyboard: false
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        getAvatarByEmail(email) {
            let hash = MD5.generate(email);
            return `https://www.gravatar.com/avatar/${hash}?d=identicon`;
        },
        addPost() {
            if (this.new_post.title === '' || this.new_post.content === '') {
                this.$root.showSnackbar("Please fill all fields", 'danger');
            } else {
                this.$store.dispatch("forum/addPost", this.new_post);
                this.new_post.title = '';
                this.new_post.content = '';
            }
        },
        likePost(index) {
            let data = {
                post_id: this.posts[index].id,
                type: this.posts[index].liked ? 'unlike' : 'like',
            };
            this.$store.dispatch("forum/likePost", data).then(
                response => {
                    if (response) {
                        this.posts[index].liked = !this.posts[index].liked;
                        this.posts[index].liked ? this.posts[index].like_count++ : this.posts[index].like_count--;
                    }
                }
            )
        },
        showComment(index) {
            this.post.post = this.posts[index];
            let data = {
                post_id: this.posts[index].id,
                page: 1,
            };
            this.$store.dispatch("forum/loadComments", data).then(
                response => {
                    if (response) {
                        this.post.comments = response.comments;
                        this.post.next_page_url = response.next_page_url;
                        this.view_post_modal.show();
                    }
                }
            );
        },
        loadMoreComment() {
            let data = {
                post_id: this.post.post.id,
                page: this.post.next_page_url.split('page=')[1],
            };
            this.$store.dispatch("forum/loadComments", data).then(
                response => {
                    if (response) {
                        if (response.comments.length === 0) {
                            this.$root.showSnackbar("No more comments", 'danger');
                        } else {
                            this.post.comments.push(...response.comments);
                            this.post.next_page_url = response.next_page_url;
                        }
                    }
                }
            );
        },
        addComment(e) {
            let data = {
                post_id: this.post.post.id,
                comment: e.target.closest('.modal-footer').querySelector('textarea').value.trim(),
            };
            this.$store.dispatch("forum/addComment", data).then(
                response => {
                    if (response) {
                        this.post.comments.unshift(response.comment);
                        e.target.closest('.modal-footer').querySelector('textarea').value = '';
                        this.posts = this.posts.map(post => {
                            if(post.id === this.post.post.id){
                                post.comment_count++;
                            }
                            return post;
                        });
                    }
                }
            );
        },
        deleteComment(id){
            let data = {
                post_id: this.post.post.id,
                comment_id: id,
            };
            if(confirm('Are you sure?')){
                this.$store.dispatch("forum/deleteComment", data).then(
                    response => {
                        if (response) {
                            this.post.comments = this.post.comments.filter(comment => comment.id !== id);
                            this.posts = this.posts.map(post => {
                                if(post.id === this.post.post.id){
                                    post.comment_count--;
                                }
                                return post;
                            });
                        }
                    }
                );
            }
        },
        showFormUpdatePost(post_id){
            this.update_post.id = post_id;
            this.update_post.title = this.posts.find(post => post.id === post_id).title;
            this.update_post.content = this.posts.find(post => post.id === post_id).content;
            this.update_post_modal.show();
        },
        updatePost(){
            let data = {
                post_id: this.update_post.id,
                title: this.update_post.title,
                content: this.update_post.content,
            };
            this.$store.dispatch("forum/updatePost", data).then(
                response => {
                    if (response) {
                        this.posts = this.posts.map(post => {
                            if(post.id === this.update_post.id){
                                post = response.post;
                            }
                            return post;
                        });
                        this.update_post_modal.hide();
                    }
                }
            );
        },
        deletePost(post_id){
            let data = {
                post_id: post_id,
            };
            if(confirm('Are you sure?')){
                this.$store.dispatch("forum/deletePost", data).then(
                    response => {
                        if (response) {
                            this.posts = this.posts.filter(post => post.id !== post_id);
                        }
                    }
                );
            }
        },
    }
}
</script>

<style>
.content-text{
    white-space: pre-wrap;
}
</style>
