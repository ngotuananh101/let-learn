<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Class</h5>
                                <p class="mb-0 text-sm">Edit class information</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="updateInfo">
                                        Update
                                    </button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-danger btn-sm"
                                            @click="deleteClass">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">Name</label>
                            <input
                                id="name"
                                name="name"
                                class="form-control"
                                type="text"
                                placeholder="Name"
                                v-model="class_info.name"
                            />
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label mt-3">Start date</label>
                            <input
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                type="date"
                                placeholder="Start date"
                                v-model="class_info.start_date"
                            />
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label mt-3">End date</label>
                            <input
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                type="date"
                                placeholder="=End date"
                                v-model="class_info.end_date"
                            />
                        </div>
                        <div class="col-md-2 col-12">
                            <label class="form-label mt-3">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="form-control"
                                v-model="class_info.status"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Member</h5>
                                <p class="mb-0 text-sm">Member in this class</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="this.add_member_modal.show()">
                                        Add Member
                                        <i class="fa-regular fa-user-plus ms-2"></i>
                                    </button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-warning btn-sm"
                                            @click="this.removeMember">
                                        Remove Member
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="class_member_datatable" class="table table-flush"></table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Quiz</h5>
                                <p class="mb-0 text-sm">Quiz in this class</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="class_quiz_datatable" class="table table-flush"></table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Post</h5>
                                <p class="mb-0 text-sm">Post in this class</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="class_post_datatable" class="table table-flush"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add member</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-3">Email</label>
                            <input
                                id="email"
                                name="email"
                                class="form-control"
                                type="text"
                                placeholder="Email"
                            />
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-3">Role</label>
                            <select
                                id="role"
                                name="role"
                                class="form-control"
                            >
                                <option value="6">Student</option>
                                <option value="5">Teacher</option>
                            </select>
                        </div>
                        <p class="text-warning">* If user is not exits in our system, we will create new account for
                            user with role you choose and default password is "123@123a"</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="this.addMember">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewQuizModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Quiz</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <label class="form-label mt-3">Email</label>
                            <input
                                id="quiz_name"
                                name="quiz_name"
                                class="form-control"
                                type="text"
                                placeholder="quiz_name"
                                v-model="this.quiz.name"
                                disabled
                            />
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-3">Status</label>
                            <select
                                id="quiz_status"
                                name="quiz_status"
                                class="form-control"
                                v-model="this.quiz.status"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">Start time</label>
                            <input
                                id="quiz_start_time"
                                name="quiz_start_time"
                                class="form-control"
                                type="datetime-local"
                                placeholder="quiz_start_time"
                                v-model="this.quiz.start_time"
                                disabled
                            />
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">End time</label>
                            <input
                                id="quiz_end_time"
                                name="quiz_end_time"
                                class="form-control"
                                type="datetime-local"
                                placeholder="quiz_end_time"
                                v-model="this.quiz.end_time"
                                disabled
                            />
                        </div>
                        <div class="col-md-6 col-12 mt-4">
                            <button class="btn btn-sm btn-outline-success w-100" @click="viewQuizPDF">View question
                            </button>
                        </div>
                        <div class="col-md-6 col-12 mt-4">
                            <button class="btn btn-sm btn-outline-warning w-100" @click="exportQuizAnswer">View result
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="this.updateQuiz">
                        Update
                    </button>
                    <button type="button" class="btn btn-danger" @click="this.deleteQuiz">
                        Delete
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewPostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Post of {{ post.post.author }}</h1>
                    <i class="fa-duotone fa-circle-xmark fs-2" data-bs-dismiss="modal"></i>
                </div>
                <div class="modal-body overflow-auto" style="max-height: 75vh;">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <img :src="post.post.avatar" alt="user avatar"
                                 class="img img-fluid rounded-circle avatar me-3">
                            <div>
                                <h6 class="mb-0">{{ post.post.author }}</h6>
                                <p class="mb-0">{{ post.post.created_at }}</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="#btnOption"
                               class="p-0 nav-link bg-light avatar-sm rounded-circle d-flex justify-content-center align-items-center"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul id="btnOption" class="px-2 py-2 dropdown-menu dropdown-menu-end me-sm-n3"
                                aria-labelledby="btnNotification">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#"
                                       @click="this.deletePost">
                                        <i class="fa-light fa-trash fs-5"></i>
                                        <p class="ms-3 mb-0">Delete this post</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <p class="mt-3">{{ post.post.content }}</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="fa-solid fa-up text-success"></i> {{ post.post.upvote }}
                            <i class="fa-solid fa-down text-danger"></i> {{ post.post.downvote }}
                            ( total: {{ post.post.vote }} )
                        </div>
                        <div>
                            {{ post.comments.length }} comment
                        </div>
                    </div>
                    <hr>
                    <div>
                        <p v-if="post.comments.length === 0">This post don't hame any comment</p>
                        <div v-for="comment in post.comments" :key="comment.id">
                            <div class="d-flex mb-4">
                                <img :src="comment.avatar" alt="user avatar"
                                     class="img img-fluid rounded-circle avatar-sm me-3">
                                <div>
                                    <div class="bg-light p-2 mb-1" style="border-radius: 1rem;">
                                        <h6 class="mb-0 text-dark">{{ comment.author }}</h6>
                                        <p class="mb-0">{{ comment.content }}</p>
                                    </div>
                                    <span class="me-lg-3 text-xs fw-bold" :data-id="comment.id" @click="deleteComment">Delete</span>
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
            </div>
        </div>
    </div>
</template>

<script>
import {DataTable, exportCSV} from "simple-datatables";
import Choices from "choices.js";
import jsPDF from "jspdf";
import "../../../helpers/other/Roboto";
import Papa from "papaparse";

export default {
    name: "index",
    title() {
        return "Class | List"
    },
    data() {
        return {
            unsubscribe: null,
            type: 'get',
            class_member_datatable: null,
            class_quiz_datatable: null,
            class_post_datatable: null,
            class_info: {},
            add_member_modal: null,
            vew_quiz_modal: null,
            view_post_modal: null,
            choice_email: null,
            selected_member: [],
            quiz: {},
            post: {
                post: {},
                comments: {},
                total_page: 0,
                current_page: 0,
            },
            selected_post_id: null,
        }
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            switch (mutation.type) {
                case 'schoolClass/request':
                    break;
                case 'schoolClass/success':
                    console.log(this.type);
                    if (this.type === 'get') {
                        this.class_info = mutation.payload.class;
                        this.init();
                    } else if (this.type === 'update') {
                        this.$root.showSnackbar('Class updated successfully', 'success');
                    } else if (this.type === 'add_member') {
                        this.$root.showSnackbar('Member added successfully', 'success');
                        location.reload();
                    } else if (this.type === 'remove_member') {
                        this.$root.showSnackbar('Member removed successfully', 'success');
                        location.reload();
                    } else if (this.type === 'get_quiz') {
                        this.$root.showSnackbar('Quiz get successfully', 'success');
                        this.quiz = mutation.payload.quiz;
                    } else if (this.type === 'update_quiz') {
                        this.$root.showSnackbar('Quiz updated successfully', 'success');
                        location.reload();
                    } else if (this.type === 'get_post') {
                        this.$root.showSnackbar('Post get successfully', 'success');
                        this.post = mutation.payload;
                    } else if (this.type === 'load_more') {
                        this.post.comments = this.post.comments.concat(mutation.payload.comments);
                        this.post.current_page = mutation.payload.current_page;
                    } else if (this.type === 'delete_post') {
                        this.$root.showSnackbar('Post deleted successfully', 'success');
                        location.reload();
                    } else if (this.type === 'delete_comment') {
                        this.$root.showSnackbar('Comment deleted successfully', 'success');
                        location.reload();
                    } else if (this.type === 'delete_quiz') {
                        this.$root.showSnackbar('Quiz deleted successfully', 'success');
                        location.reload();
                    } else if(this.type === 'delete_class'){
                        this.$root.showSnackbar('Class deleted successfully', 'success');
                        this.$router.push({name: 'school.class.index'});
                    }
                    break;
                case 'schoolClass/failure':
                    this.$root.showSnackbar(mutation.payload, 'danger');
                    break;
            }
        });
        this.$store.dispatch('schoolClass/show', this.$route.params.id);
    },
    beforeUnmount() {
        this.unsubscribe();
        this.classes_datatable ? this.classes_datatable.destroy() : null;
    },
    methods: {
        init() {
            this.class_member_datatable = new DataTable('#class_member_datatable', {
                data: {
                    headings: ['ID', 'Name', 'Email', 'Role'],
                    data: this.class_info.member ? this.class_info.member.map((member) => {
                        return [member.id, member.name, member.email, member.role_id === 5 ? 'Teacher' : 'Student'];
                    }) : [],
                },
                rowRender: (_row, tr, index) => {
                    if (!this.selected_member.includes(index)) {
                        return
                    }
                    if (!tr.attributes) {
                        tr.attributes = {}
                    }
                    if (!tr.attributes.style) {
                        tr.attributes.style = "background-color: var(--bs-gray-400);"
                    } else {
                        tr.attributes.style += " background-color: var(--bs-gray-400);"
                    }
                    return tr
                }
            });
            this.class_member_datatable.on("datatable.selectrow", (rowIndex, event) => {
                if (this.selected_member.includes(rowIndex)) {
                    this.selected_member = this.selected_member.filter(row => row !== rowIndex)
                } else {
                    this.selected_member.push(rowIndex)
                }
                this.class_member_datatable.update();
            });
            this.class_quiz_datatable = new DataTable('#class_quiz_datatable', {
                data: {
                    headings: ['ID', 'Name', 'Start time', 'End time', 'Status'],
                    data: this.class_info.quizzes ? this.class_info.quizzes.map((quiz) => {
                        return [quiz.id, quiz.name, quiz.start_time, quiz.end_time, quiz.status];
                    }) : [],
                },
            });
            this.class_quiz_datatable.on("datatable.selectrow", (rowIndex, event) => {
                this.vew_quiz_modal.show();
                this.type = 'get_quiz';
                this.$store.dispatch('schoolClass/getQuizById', {
                    class_id: this.$route.params.id,
                    quiz_id: this.class_info.quizzes[rowIndex].id
                });
            });
            this.class_post_datatable = new DataTable('#class_post_datatable', {
                data: {
                    headings: ['ID', 'Title', 'Content', 'Created at'],
                    data: this.class_info.posts ? this.class_info.posts.map((post) => {
                        return [post.id, post.title.slice(0, 20) + "...", post.content.slice(0, 30) + "...", post.created_at];
                    }) : [],
                },
            });
            this.class_post_datatable.on("datatable.selectrow", (rowIndex, event) => {
                this.view_post_modal.show();
                this.type = 'get_post';
                this.selected_post_id = this.class_info.posts[rowIndex].id;
                this.$store.dispatch('schoolClass/getPostById', {
                    class_id: this.$route.params.id,
                    post_id: this.selected_post_id,
                    page: this.post_comment_page
                });
            });
            const bootstrap = this.$store.state.config.bootstrap;
            this.add_member_modal = new bootstrap.Modal(document.getElementById('userModal'), {
                keyboard: false
            });
            this.vew_quiz_modal = new bootstrap.Modal(document.getElementById('viewQuizModal'), {
                keyboard: false
            });
            this.view_post_modal = new bootstrap.Modal(document.getElementById('viewPostModal'), {
                keyboard: false
            });
            this.view_post_modal._element.addEventListener('hide.bs.modal', () => {
                this.post = {
                    post: {},
                    comments: {},
                    total_page: 0,
                    current_page: 0,
                };
            });
            this.choice_email = new Choices('#email', {
                searchEnabled: true,
                searchChoices: true,
                shouldSort: false,
                itemSelectText: '',
                removeItemButton: true,
                allowHTML: true,
                noResultsText: 'No results found',
                delimiter: ',',
            });
        },
        updateInfo() {
            this.type = 'update';
            let data = {
                type: 'info',
                name: this.class_info.name,
                start_date: this.class_info.start_date,
                end_date: this.class_info.end_date,
                status: this.class_info.status,
            };
            this.$store.dispatch('schoolClass/update', {id: this.$route.params.id, data: data});
        },
        addMember() {
            this.type = 'add_member';
            let email = this.choice_email.getValue(true);
            let role = document.getElementById('role').value;
            let data = {
                type: 'add_member',
                emails: email,
                role: role,
            };
            this.$store.dispatch('schoolClass/update', {id: this.$route.params.id, data: data});
        },
        removeMember() {
            if (confirm('Are you sure you want to remove these members?')) {
                this.type = 'remove_member';
                let data = {
                    type: 'remove_member',
                    ids: this.selected_member.map((index) => {
                        return this.class_info.member[index].id;
                    }),
                };
                this.$store.dispatch('schoolClass/update', {id: this.$route.params.id, data: data});
            }
        },
        viewQuizPDF() {
            const doc = new jsPDF();
            doc.setFont("Roboto-Regular");
            doc.setFontSize(20);
            doc.text(20, 20, this.quiz.name);
            doc.setFontSize(15);
            doc.text(20, 30, `Start time: ${this.quiz.start_time}`);
            doc.text(20, 35, `End time: ${this.quiz.end_time}`);
            doc.text(20, 40, `Status: ${this.quiz.status}`);
            doc.text(20, 45, `Questions: ${this.quiz.questions.length}`);
            doc.setFontSize(12);
            let y = 50;
            for (let i = 0; i < this.quiz.questions.length; i++) {
                const question = this.quiz.questions[i];
                if (y > 250) {
                    doc.addPage();
                    y = 20;
                }
                y += 10;
                const text = `Q${i + 1}. ${question.question} (${question.points} point)`;
                doc.text(20, y, text);
                y += 5;
                if (doc.getTextWidth(question.question) > 180) {
                    y += 5;
                    if (y > 250) {
                        doc.addPage();
                        y = 20;
                    }
                }
                console.log('abc');
                const answer = question.answer_option ? JSON.parse(question.answer_option) : null;
                if (answer) {
                    for (let j = 0; j < answer.length; j++) {
                        const content = answer[j];
                        if (doc.getTextWidth(content) > 180) {
                            y += 5;
                            if (y > 250) {
                                doc.addPage();
                                y = 20;
                            }
                        }
                        doc.text(20, y, content);
                        const lines = content.split(/\r\n|\r|\n/).length;
                        y += 5 * lines;
                        if (y > 250) {
                            doc.addPage();
                            y = 20;
                        }
                    }
                }
                if (question.is_multiple_choice) {
                    doc.text(20, y, `Correct answer: ${question.correct_answer}`);
                    const lines = question.correct_answer.split(/\r\n|\r|\n/).length;
                    y += 5 * lines;
                    if (y > 250) {
                        doc.addPage();
                        y = 20;
                    }
                }
            }
            doc.save(`${this.quiz.name}.pdf`);
        },
        exportQuizAnswer() {
            let answers = this.quiz.answers.map((answer) => [
                answer.id,
                answer.user_id,
                answer.created_at,
                JSON.parse(answer.answer_text),
            ]);
            let student = this.class_info.member.filter((member) => member.role_id == '6');
            let results = [];
            let header = ['Student ID', 'Username', 'Name'];
            this.quiz.questions.map((question, index) => {
                header.push('Question ' + index);
            });
            header.push('Score');
            results.push(header);
            student.map((student) => {
                let answer = answers.filter((answer) => answer[1] == student.id);
                if (answer.length > 0) {
                    answer.map((answer) => {
                        let result = [student.id, student.username, student.name];
                        let answer_text = answer[3];
                        let score = 0;
                        answer_text.map((answer) => {
                            let data = (answer.answer ?? 'No answer') + ' (' + answer.is_correct + ', point ' + answer.points + ')';
                            result.push(data);
                            score += parseInt(answer.points) ?? 0;
                        });
                        result.push(score);
                        results.push(result);
                    });
                } else {
                    let row = [student.id, student.username, student.name];
                    this.quiz.questions.map((question, index) => {
                        row.push('No answer');
                    });
                    row.push(0);
                    results.push(row);
                }
            })
            // convert results to csv
            let csv = Papa.unparse(results);
            // download csv
            let blob = new Blob([csv], {type: "text/csv;charset=utf-8"});
            this.saveAs(blob, this.quiz.name + '.csv');
        },
        saveAs(blob, name) {
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = name;
            link.click();
        },
        updateQuiz() {
            this.type = 'update_quiz';
            let data = {
                type: 'update_quiz',
                quiz_id: this.quiz.id,
                status: this.quiz.status,
            };
            this.$store.dispatch('schoolClass/updateQuiz', {id: this.$route.params.id, data: data});
        },
        deleteQuiz() {
            if (confirm('Are you sure you want to delete this quiz?')) {
                this.type = 'delete_quiz';
                this.$store.dispatch('schoolClass/deleteQuiz', {
                    class_id: this.$route.params.id,
                    quiz_id: this.quiz.id
                });
            }
        },
        loadMoreComment() {
            this.type = 'load_more';
            this.$store.dispatch('schoolClass/getPostById', {
                class_id: this.$route.params.id,
                post_id: this.selected_post_id,
                page: this.post.current_page + 1,
            });
        },
        deletePost() {
            if (confirm('Are you sure you want to delete this post?')) {
                this.type = 'delete_post';
                this.$store.dispatch('schoolClass/deletePost', {
                    class_id: this.$route.params.id,
                    post_id: this.selected_post_id,
                });
            }
        },
        deleteComment(e) {
            let id = e.target.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this comment?')) {
                this.type = 'delete_comment';
                this.$store.dispatch('schoolClass/deleteComment', {
                    class_id: this.$route.params.id,
                    post_id: this.selected_post_id,
                    comment_id: id,
                });
            }
        },
        deleteClass() {
            if (confirm('Are you sure you want to delete this class?')) {
                this.type = 'delete_class';
                this.$store.dispatch('schoolClass/deleteClass', {id: this.$route.params.id});
            }
        },
    }
}
</script>

<style scoped>
.modal {
    z-index: 9999 !important;
}
</style>
