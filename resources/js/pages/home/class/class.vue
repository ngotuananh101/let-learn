<template>
    <ul class="nav nav-tabs pt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">News
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">Exercises
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                    role="tab" aria-controls="contact" aria-selected="false">Members
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="container-fluid pt-3">
                <div class="row justify-content-center mt-2 pb-5">
                    <div class="col-12">
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
                        <h6 class="mt-3">News</h6>
                        <p class="text-center mt-3" v-if="posts_data.posts.length === 0">No post</p>
                        <div v-for="(post, index) in posts_data.posts">
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
                                             v-show="this.user.id === post.author.id || this.user.role.name === 'teacher'">
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
                                                        <i class="fa-light fa-pen-to-square fs-6"></i>
                                                        <p class="ms-1 mb-0" :data-id="post.id"
                                                           @click="this.showFormUpdate">Update this post</p>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <i class="fa-light fa-trash fs-6"></i>
                                                        <p class="ms-1 mb-0" :data-id="post.id"
                                                           @click="this.deletePost">Delete this post</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title">{{ post.title }}</h6>
                                    <p class="card-text">{{ post.content }}</p>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-around">
                                    <div class="d-flex align-items-center ms-3">
                                        <i class="fa-light fa-comment me-2"></i>
                                        <p class="mb-0">{{ post.comment_count }}</p>
                                    </div>
                                    <a :href="'#post' + post.id" @click="viewComment(index)">View comment</a>
                                </div>
                                <hr>
                                <div class="card-footer pt-0">
                                    <div class="d-flex">
                                        <img :src="getAvatarByEmail(this.user.email)" alt="user avatar"
                                             class="img img-fluid rounded-circle avatar-sm me-3">
                                        <textarea class="form-control" rows="3" placeholder="Write a comment..."
                                                  :data-id="post.id" @keydown.enter="addComment"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a v-if="posts_data.next_page_url" href="#" class="btn btn-primary mt-3" @click="loadMorePost">Load
                            more</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel">
            <div class="container pt-3 pb-5">
                <h3 class="text-center">Exercises</h3>
                <div v-if="quizzes" class="row mt-2">
                    <div class="col-md-4 col-12 mt-3" v-for="(quiz, index) in quizzes" :key="index">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h5>{{ quiz.name }}</h5>
                                <p class="mb-0">{{ quiz.description }}</p>
                            </div>
                            <hr>
                            <div class="card-body py-0">
                                <p>Start: {{ quiz.start_time }}</p>
                                <p>End: {{ quiz.end_time }}</p>
                                <p class="mb-0">Questions: {{ quiz.count_questions }}</p>
                                <p class="mb-0 mt-3 text-warning" v-if="quiz.status === 'pending'">Waiting manager approve</p>
                            </div>
                            <hr>
                            <div class="card-footer pt-0">
                                <div class="row">
                                    <div class="col-12" v-if="this.user.role.name === 'teacher'">
                                        <button type="button" class="btn btn-primary mb-0">
                                            Result
                                        </button>
                                        <router-link
                                            :to="{name:'home.test.update', params: {id: this.$route.params.id, quiz_id: quiz.id}}"
                                            class="btn btn-primary mb-0 ms-md-3"
                                        >
                                            Update quiz
                                        </router-link>
                                    </div>
                                    <div class="col-12" v-if="this.user.role.name === 'student'">
                                        <button v-if="!quiz.submitted"
                                                type="button"
                                                class="btn btn-primary">
                                            <router-link :to="'/lesson/test/' + quiz.id">
                                                Start quiz
                                            </router-link>
                                        </button>
                                        <span v-else>Quiz already submitted</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <router-link :to="{ name: 'home.test.add', params: {id: this.id} }">
                    <div v-if="this.user.role.name === 'teacher'" class="col-12">
                        <button class="btn btn-primary position-fixed bottom-0 end-0 mt-3 ms-3" type="button">Add new
                            test
                        </button>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel">
            <div class="container pt-3">
                <h3 class="text-center">Members</h3>
                <div class="row">
                    <div class="col-12">
                        <h4 class="pt-3">Teachers</h4>
                        <div class="card my-4" v-for="(teacher, index) in teacherArray" :key="'teacher-' + teacher.id">
                            <div class="card-body d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <img :src="getAvatarByEmail(teacher.email)" class="me-2 rounded-circle" width="30"
                                         height="30" alt="">
                                    <span class="ms-2 me-auto">{{ teacher.name }}</span>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <h4 class="pt-3">Students</h4>
                        <div class="my-4 pb-5">
                            <div class="card mt-3" v-for="(student, index) in studentArray"
                                 :key="'student-' + student.id">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img :src="getAvatarByEmail(student.email)" class="me-2 rounded-circle"
                                             width="30"
                                             height="30" alt="">
                                        <span class="ms-2 me-auto">{{ student.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <p class="mt-3">{{ post.post.content }}</p>
                    </div>
                    <hr>
                    <div v-if="post.comments">
                        <p v-if="post.comments.length === 0">This post don't hame any comment</p>
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
                                        v-show="this.user.id === comment.author.id || this.user.role.name === 'teacher'"
                                        class="me-lg-3 text-xs fw-bold" :data-id="comment.id" @click="deleteComment">Delete</span>
                                    <span class="text-xs">{{ comment.created_at }}</span>
                                </div>
                            </div>
                        </div>
                        <p v-if="post.comments.length > 0 && post.current_page < post.total_page" class="fw-bold"
                           @click="loadMoreComment">
                            Load more comment
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex w-100">
                        <img :src="getAvatarByEmail(this.user.email)" alt="user avatar"
                             class="img img-fluid rounded-circle avatar-sm me-3">
                        <textarea class="form-control" rows="3" placeholder="Write a comment..." :data-id="post.post.id"
                                  @keydown.enter="addComment"></textarea>
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
            id: this.$route.params.id,
            user: null,
            quizzes: null,
            members: null,
            showDetails: false,
            newPostText: '',
            data: null,
            results: null,
            loading: false,
            newComment: '',
            posts_data: {
                posts: [],
                next_page_url: null,
                prev_page_url: null,
            },
            post: {
                post: null,
                comments: null,
                total_page: 0,
                current_page: 1,
            },
            view_post_modal: null,
            update_post_modal: null,
            new_post: {
                title: '',
                content: '',
            },
            update_post: {
                title: '',
                content: '',
            },
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.members = mutation.payload.members;
            } else if (mutation.type === "classPost/setPosts") {
                this.posts_data = mutation.payload;
            } else if (mutation.type === "classPost/commentAdded") {
                location.reload();
            } else if (mutation.type === "classPost/commentLoaded") {
                this.post.comments ? this.post.comments = this.post.comments.concat(mutation.payload.comments) : this.post.comments = mutation.payload.comments;
                this.post.total_page = mutation.payload.total_page;
                this.post.current_page = mutation.payload.current_page;
            } else if (mutation.type === "classPost/commentDeleted") {
                location.reload();
            } else if (mutation.type === "classPost/postAdded") {
                location.reload();
            } else if (mutation.type === "classPost/failure") {
                this.$root.showSnackbar(mutation.payload, "danger");
            } else if (mutation.type === "classPost/commentDeleted") {
                location.reload();
            } else if (mutation.type === "classPost/setMorePost") {
                this.posts_data.posts = this.posts_data.posts.concat(mutation.payload.posts);
                this.posts_data.next_page_url = mutation.payload.next_page_url;
                this.posts_data.prev_page_url = mutation.payload.prev_page_url;
                setTimeout(() => {
                    window.scrollTo(0, document.body.scrollHeight);
                }, 100);
            } else if (mutation.type === "classPost/postDeleted") {
                location.reload();
            } else if (mutation.type === "classPost/postUpdated") {
                location.reload();
            } else if (mutation.type === "classQuiz/setQuiz") {
                this.quizzes = mutation.payload.quizzes;
            }
        });
        this.$store.dispatch("classPost/getPostsByClassId", this.id);
        this.$store.dispatch("classQuiz/getQuizByClassId", this.id);
        this.$store.dispatch("home/getClassDetail", {id: this.id, roleName: this.user.role.name});
    },
    mounted() {
        const bootstrap = this.$store.state.config.bootstrap;
        this.view_post_modal = new bootstrap.Modal(document.getElementById('viewPostModal'), {
            keyboard: false
        });
        this.update_post_modal = new bootstrap.Modal(document.getElementById('updatePostModal'), {
            keyboard: false
        });
        this.view_post_modal._element.addEventListener('hidden.bs.modal', () => {
            this.post = {
                post: null,
                comments: null,
                total_page: 0,
                current_page: 1,
            };
        });
        this.update_post_modal._element.addEventListener('hidden.bs.modal', () => {
            this.update_post = {
                title: '',
                content: '',
            };
        });
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    computed: {
        teacherArray() {
            return this.members ? this.members.filter(member => member.role === "teacher") : [];
        },
        studentArray() {
            return this.members ? this.members.filter(member => member.role === "student") : [];
        }
    },

    methods: {
        getAvatarByEmail(email) {
            let hash = MD5.generate(email);
            return `https://www.gravatar.com/avatar/${hash}?d=identicon`;
        },
        addComment(event) {
            event.preventDefault();
            let post_id = event.target.getAttribute('data-id');
            let comment = event.target.value;
            let data = {
                type: 'add_comment',
                post_id: post_id,
                comment: comment,
                class_id: this.id
            }
            this.$store.dispatch("classPost/addComment", data);
        },
        viewComment(index) {
            this.post.post = this.posts_data.posts[index];
            let data = {
                post_id: this.post.post.id,
                class_id: this.id,
                page: 1
            }
            this.$store.dispatch("classPost/loadComments", data);
            this.view_post_modal.show();
        },
        loadMoreComment() {
            let post_id = this.post.post.id;
            let data = {
                post_id: post_id,
                class_id: this.id,
                page: this.post.current_page + 1
            }
            this.$store.dispatch("classPost/loadComments", data);
        },
        addPost() {
            let data = {
                title: this.new_post.title,
                content: this.new_post.content,
                class_id: this.id
            }
            this.$store.dispatch("classPost/addPost", data);
        },
        deleteComment(event) {
            if (confirm('Are you sure you want to delete this comment?')) {
                let comment_id = event.target.getAttribute('data-id');
                let data = {
                    type: 'delete_comment',
                    comment_id: comment_id,
                    class_id: this.id,
                    post_id: this.post.post.id
                }
                this.$store.dispatch("classPost/deleteComment", data);
            }
        },
        loadMorePost() {
            this.$store.dispatch("classPost/loadMorePost", this.posts_data.next_page_url);
        },
        deletePost(event) {
            if (confirm('Are you sure you want to delete this post?')) {
                let post_id = event.target.getAttribute('data-id');
                let data = {
                    type: 'delete_post',
                    post_id: post_id,
                    class_id: this.id
                }
                this.$store.dispatch("classPost/deletePost", data);
            }
        },
        showFormUpdate(event){
            let post_id = event.target.getAttribute('data-id');
            let post = this.posts_data.posts.find(post => post.id === parseInt(post_id));
            this.update_post.title = post.title;
            this.update_post.content = post.content;
            this.update_post.id = post.id;
            this.update_post_modal.show();
        },
        updatePost() {
            let data = {
                type: 'update_post',
                title: this.update_post.title,
                content: this.update_post.content,
                class_id: this.id,
                post_id: this.update_post.id
            }
            this.$store.dispatch("classPost/updatePost", data);
        },
    }
}
</script>
