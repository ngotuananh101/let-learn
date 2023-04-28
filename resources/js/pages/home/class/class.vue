<template>
    <h2 class="mt-3 text-center">Class Information</h2>
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
                                <p class="mb-0 mt-3">
                                    Status:
                                    <span v-if="quiz.status === 'pending'" class="text-warning">Pending</span>
                                    <span v-else-if="quiz.status === 'active'" class="text-success">Active</span>
                                    <span v-else class="text-danger">Expired</span>
                                </p>
                            </div>
                            <hr>
                            <div class="card-footer py-0">
                                <div class="row">
                                    <div class="col-12" v-if="this.user.role.name === 'teacher'">
                                        <div class="row">
                                            <div class="col-md-6 col-12" v-if="quiz.status !== 'pending'">
                                                <button type="button" class="btn btn-primary w-100"
                                                        @click="exportResultToExcel(quiz.id)"
                                                >
                                                    View Report
                                                </button>
                                            </div>
                                            <div class="col-md-6 col-12" v-if="quiz.status !== 'pending'">
                                                <button type="button" class="btn btn-primary w-100"
                                                        @click="showFormViewScore(quiz.id)"
                                                >
                                                    View Score
                                                </button>
                                            </div>
                                            <div class="col-md-6 col-12" v-if="quiz.status === 'pending'">
                                                <router-link
                                                    :to="{name:'home.test.update', params: {id: this.$route.params.id, quiz_id: quiz.id}}"
                                                    class="btn btn-primary w-100"
                                                >
                                                    Update quiz
                                                </router-link>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <button type="button"
                                                        class="btn btn-danger w-100"
                                                        :data-id="quiz.id"
                                                        @click="deleteQuiz"
                                                >
                                                    Delete quiz
                                                </button>
                                            </div>
                                        </div>
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
                <h3 class="text-center">Class Members</h3>
                <div class="row">
                    <h4 class="pt-3">Teachers ( {{ teacherArray.length }} )</h4>
                    <div class="col-md-4 col-12" v-for="(teacher, index) in teacherArray"
                         :key="'teacher-' + teacher.id">
                        <div class="card my-4">
                            <div class="card-body d-flex align-items-center overflow-hidden">
                                <img :src="teacher.avatar" class="me-2 rounded-circle" width="30"
                                     height="30" alt="">
                                <div>
                                    <p class="ms-2 mb-0 fw-bold">{{ teacher.name }}</p>
                                    <span class="ms-2 text-muted">{{ teacher.email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <h4 class="pt-3">Students ( {{ studentArray.length }} )</h4>
                    <div class="col-md-4 col-12" v-for="(student, index) in studentArray"
                         :key="'student-' + student.id">
                        <div class="card mt-3">
                            <div class="card-body d-flex align-items-center overflow-hidden">
                                <img :src="student.avatar" class="me-2 rounded-circle" width="30"
                                     height="30" alt="">
                                <div>
                                    <p class="ms-2 mb-0 fw-bold">{{ student.name }}</p>
                                    <span class="ms-2 text-muted">{{ student.email }}</span>
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
    <div class="modal fade" id="viewScoreModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Score report for {{ quiz_info ? quiz_info.quiz.name : "" }}</h1>
                    <i class="fa-duotone fa-circle-xmark fs-2" data-bs-dismiss="modal"></i>
                </div>
                <table class="table table-responsive" id="viewScoreTable"></table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateScoreModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" style="background: rgba(0,0,0,0.6)">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateScoreTitle"></h1>
                    <i class="fa-duotone fa-circle-xmark fs-2" data-bs-dismiss="modal"></i>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">Question</th>
                            <th scope="col">Type</th>
                            <th scope="col">Answer</th>
                            <th scope="col">Is Correct</th>
                            <th scope="col">Score</th>
                        </tr>
                        </thead>
                        <tbody id="updateScoreTable_body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="updateScore">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {MD5} from "md5-js-tools";
import writeXlsxFile from 'write-excel-file';
import {DataTable, makeEditable} from 'simple-datatables';

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
            view_score_modal: null,
            view_score_table: null,
            update_score_modal: null,
            new_post: {
                title: '',
                content: '',
            },
            update_post: {
                title: '',
                content: '',
            },
            quiz_info: null,
            selected_student_id: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            switch (mutation.type) {
                case "home/requestSuccess":
                    this.members = mutation.payload.data;
                    this.$setPageTitle(mutation.payload.class_name + " Classroom");
                    break;
                case "classPost/setPosts":
                    this.posts_data = mutation.payload;
                    break;
                case "classPost/commentAdded":
                case "classPost/commentDeleted":
                case "classPost/postAdded":
                case "classPost/postDeleted":
                case "classPost/postUpdated":
                    location.reload();
                    break;
                case "classPost/commentLoaded":
                    this.post.comments ? this.post.comments = this.post.comments.concat(mutation.payload.comments) : this.post.comments = mutation.payload.comments;
                    this.post.total_page = mutation.payload.total_page;
                    this.post.current_page = mutation.payload.current_page;
                    break;
                case "classPost/setMorePost":
                    this.posts_data.posts = this.posts_data.posts.concat(mutation.payload.posts);
                    this.posts_data.next_page_url = mutation.payload.next_page_url;
                    this.posts_data.prev_page_url = mutation.payload.prev_page_url;
                    setTimeout(() => {
                        window.scrollTo(0, document.body.scrollHeight);
                    }, 100);
                    break;
                case "classPost/failure":
                case "classQuiz/failure":
                    this.$root.showSnackbar(mutation.payload, "danger");
                    break;
                case "classQuiz/setQuiz":
                    this.quizzes = mutation.payload.quizzes;
                    break;
                case "classQuiz/quizDeleted":
                case "classQuiz/quizUpdated":
                    location.reload();
                    break;
                default:
                    break;
            }
        });
        this.$store.dispatch("classPost/getPostsByClassId", this.id);
        this.$store.dispatch("classQuiz/getQuizByClassId", this.id);
        this.$store.dispatch("home/getClassDetail", {id: this.id});
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
        this.view_score_modal = new bootstrap.Modal(document.getElementById('viewScoreModal'), {
            keyboard: false
        });
        this.view_score_modal._element.addEventListener('hidden.bs.modal', () => {
            this.view_score_table.destroy();
        });
        this.view_score_table = new DataTable('#viewScoreTable');
        this.view_score_table.on("datatable.selectrow", (rowIndex, event) => {
            event.preventDefault();
            if(isNaN(rowIndex)) {}
            else{
                // check if user click to button update
                let target = event.target;
                let check = target.classList.contains('update-score-btn') && !target.classList.contains('disabled');
                if(check){
                    let student_id = this.quiz_info.students[rowIndex].id;
                    this.showFormUpdateScore(student_id);
                }
            }
        });
        this.update_score_modal = new bootstrap.Modal(document.getElementById('updateScoreModal'), {
            keyboard: false
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
        showFormUpdate(event) {
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
        deleteQuiz(event) {
            if (confirm('Are you sure you want to delete this quiz?')) {
                let quiz_id = event.target.getAttribute('data-id');
                let data = {
                    type: 'delete_quiz',
                    quiz_id: quiz_id,
                    class_id: this.id
                }
                this.$store.dispatch("classQuiz/deleteQuiz", data);
            }
        },
        async exportResultToExcel(quiz_id) {
            const quizInfoSchema = [
                {
                    column: 'Type',
                    type: String,
                    value: (quiz) => quiz.type
                },
                {
                    column: 'Value',
                    type: String,
                    value: (quiz) => quiz.value
                },
            ];
            const questionSchema = [
                {
                    column: "ID",
                    type: Number,
                    value: (question) => question.id
                },
                {
                    column: "Question",
                    type: String,
                    value: (question) => question.question,
                    wrap: true
                },
                {
                    column: "Type",
                    type: String,
                    value: (question) => question.is_multiple_choice ? 'Multiple Choice' : 'Easy'
                },
                {
                    column: "Correct Answer",
                    type: String,
                    value: (question) => question.correct_answer
                },
                {
                    column: "Score",
                    type: Number,
                    value: (question) => question.score
                }
            ];
            const answerSchema = [
                {
                    column: "Question",
                    type: String,
                    value: (answer) => answer.question
                },
                {
                    column: "Answer",
                    type: String,
                    value: (answer) => answer.answer
                },
                {
                    column: "Correct",
                    type: String,
                    value: (answer) => answer.is_correct ? 'Yes' : 'No'
                },
                {
                    column: "Score",
                    type: Number,
                    value: (answer) => answer.score
                },
                {
                    column: "Max Score",
                    type: Number,
                    value: (answer) => answer.max_score
                }
            ];
            this.$store.dispatch("classQuiz/viewReport", {
                type: 'report',
                quiz_id: quiz_id,
                class_id: this.id
            }).then(
                response => {
                    let quiz = response.quiz;
                    // map key and value to object
                    quiz = Object.keys(quiz).map(key => {
                        return {
                            type: key,
                            value: quiz[key] + ''
                        }
                    });
                    const questions = response.questions;
                    const students = response.students;
                    const studentAnswers = response.studentAnswers;
                    let data = [quiz, questions];
                    let schema = [quizInfoSchema, questionSchema];
                    let sheets = ['Quiz info', 'Questions'];
                    // create one workbook per student
                    students.forEach(student => {
                        sheets.push(student.email);
                        schema.push(answerSchema);
                        let answer = studentAnswers.find(answer => answer.user_id === student.id);
                        if (answer) {
                            let ans_data = [];
                            let answer_text = JSON.parse(answer.answer_text);
                            questions.forEach(question => {
                                let ans = answer_text.find(ans => ans.question_id === question.id);
                                if (ans) {
                                    ans_data.push({
                                        question: question.question,
                                        answer: ans.answer,
                                        is_correct: ans.is_correct,
                                        score: ans.points,
                                        max_score: question.score
                                    });
                                } else {
                                    ans_data.push({
                                        question: question.question,
                                        answer: '',
                                        is_correct: false,
                                        score: 0,
                                        max_score: question.score
                                    });
                                }
                            });
                            data.push(ans_data);
                        } else {
                            let ans_data = [];
                            questions.forEach(question => {
                                ans_data.push({
                                    question: question.question,
                                    answer: 'Not answer',
                                    is_correct: false,
                                    score: 0,
                                    max_score: question.score
                                });
                            });
                            data.push(ans_data);
                        }
                    });
                    writeXlsxFile(data, {
                        schema: schema,
                        sheets: sheets,
                        fileName: response.quiz.name + ' report.xlsx',
                    });
                });
        },
        showFormViewScore(quiz_id) {
            this.$store.dispatch("classQuiz/viewReport", {
                type: 'report',
                quiz_id: quiz_id,
                class_id: this.id
            }).then(
                response => {
                    // check view_score_table is destroyed
                    if (!this.view_score_table.initialized) {
                        this.view_score_table.init();
                    }
                    this.quiz_info = response;
                    const questions = response.questions;
                    const students = response.students;
                    const studentAnswers = response.studentAnswers;
                    let table_data = {};
                    table_data.headings = ['ID', 'Name', 'Email'].concat(questions.map((question, index) => 'Question ' + (index + 1)));
                    table_data.headings.push('Total');
                    table_data.headings.push('Action');
                    table_data.data = [];
                    students.forEach(student => {
                        let answer = studentAnswers.find(answer => answer.user_id === student.id);
                        let row = [student.id, student.name, student.email];
                        if (answer) {
                            let ans_data = [];
                            let answer_text = JSON.parse(answer.answer_text);
                            let total = 0;
                            let max_score = 0;
                            questions.forEach(question => {
                                let ans = answer_text.find(ans => ans.question_id === question.id);
                                if (ans) {
                                    total += ans.points;
                                    max_score += question.score;
                                    ans_data.push({
                                        question: question.question,
                                        answer: ans.answer,
                                        is_correct: ans.is_correct,
                                        score: ans.points,
                                        max_score: question.score
                                    });
                                } else {
                                    ans_data.push({
                                        question: question.question,
                                        answer: '',
                                        is_correct: false,
                                        score: 0,
                                        max_score: question.score
                                    });
                                }
                            });
                            row = row.concat(ans_data.map(ans => ans.score));
                            row.push(total + '/' + max_score + ' (' + (total / max_score * 100).toFixed(2) + '%)');
                            row.push('<button class="btn btn-primary btn-sm update-score-btn" data-id=' + student.id +'>Update</button>');
                        } else {
                            let ans_data = [];
                            let total = 0;
                            let max_score = 0;
                            questions.forEach(question => {
                                ans_data.push({
                                    question: question.question,
                                    answer: 'Not answer',
                                    is_correct: false,
                                    score: 0,
                                    max_score: question.score
                                });
                                max_score += question.score;
                            });
                            row = row.concat(ans_data.map(ans => ans.score));
                            row.push(total + '/' + max_score + ' (' + (total / max_score * 100).toFixed(2) + '%)');
                            row.push('<button class="btn btn-primary btn-sm disabled" data-id=' + student.id +'>Update</button>');
                        }
                        table_data.data.push(row);
                    });
                    this.view_score_table.insert(table_data);
                    this.view_score_modal.show();
                }
            );
        },
        showFormUpdateScore(student_id){
            let student = this.quiz_info.students.find(student => student.id === student_id);
            let answer = this.quiz_info.studentAnswers.find(answer => answer.user_id === student_id);
            let answer_text = JSON.parse(answer.answer_text);
            let questions = this.quiz_info.questions;
            let table_body = document.getElementById('updateScoreTable_body');
            table_body.innerHTML = '';
            questions.forEach(question => {
                let ans = answer_text.find(ans => ans.question_id === question.id);
                let row = document.createElement('tr');
                let question_td = document.createElement('td');
                question_td.innerHTML = question.question.split('[')[0].trim();
                let type_td = document.createElement('td');
                type_td.innerHTML = question.is_multiple_choice ? 'Multiple choice' : 'Short answer';
                let answer_td = document.createElement('td');
                answer_td.innerHTML = ans.answer ?? 'Not answer';
                let is_correct_td = document.createElement('td');
                let score_td = document.createElement('td');
                if(question.is_multiple_choice){
                    is_correct_td.innerHTML = ans.is_correct ? 'Correct' : 'Incorrect';
                    score_td.innerHTML = ans.points;
                } else {
                    is_correct_td.innerHTML = 'N/A';
                    let score_input = document.createElement('input');
                    score_input.type = 'number';
                    score_input.className = 'form-control';
                    score_input.value = ans.points;
                    score_input.min = 0;
                    score_input.max = question.score;
                    score_input.setAttribute('data-id', question.id);
                    score_td.appendChild(score_input);
                }
                row.appendChild(question_td);
                row.appendChild(type_td);
                row.appendChild(answer_td);
                row.appendChild(is_correct_td);
                row.appendChild(score_td);
                table_body.appendChild(row);
            });
            document.getElementById('updateScoreTitle').innerHTML = "Score of " + student.name;
            this.selected_student_id = student_id;
            this.update_score_modal.show();
        },
        updateScore(){
            let score = 0;
            let max_score = 0;
            let questions = this.quiz_info.questions;
            let answer_text = JSON.parse(this.quiz_info.studentAnswers.find(answer => answer.user_id === this.selected_student_id).answer_text);
            let table_body = document.getElementById('updateScoreTable_body');
            let inputs = table_body.querySelectorAll('input');
            inputs.forEach(input => {
                let question = questions.find(question => question.id === parseInt(input.getAttribute('data-id')));
                let ans = answer_text.find(ans => ans.question_id === question.id);
                ans.points = parseInt(input.value);
                score += ans.points;
                max_score += question.score;
            });
            let data = {
                user_id: this.selected_student_id,
                quiz_id: this.quiz_info.quiz.id,
                answer_text: JSON.stringify(answer_text),
                type: 'update_score',
                class_id: this.id,
            };
            this.$store.dispatch('classQuiz/updateScore', data);
        }
    }
}
</script>
<style>
.datatable-wrapper {
    width: 100%;
    overflow-x: auto;
}
</style>
