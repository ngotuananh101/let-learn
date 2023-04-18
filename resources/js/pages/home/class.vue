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
            <div class="container pt-3">
                <h3 class="text-center">News</h3>
                <div class="row justify-content-center mt-4 pb-5">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="onSubmit">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="questionContent" rows="5"></textarea>
                                        <label for="floatingTextarea2">Question</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                </form>
                            </div>
                        </div>

                        <div v-for="post in data" :key="post.id">
                            <div class="card mt-4">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="card-header">
                                            <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30"
                                                 height="30" alt="">
                                            {{ post.name }}
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button v-if="user && user.id === post.user_id " class="btn btn-primary mt-2">
                                            Update
                                        </button>
                                    </div>
                                    <div class="col-2">
                                        <button v-if="user && user.id === post.user_id" class="btn btn-warning mt-2">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>{{ post.content }}</p>
                                </div>

                                <div class="card-body">
                                    <form @submit.prevent="submitComment" :data-post_id='post.id'>
                                        <div class="form-group">
                                            <label for="commentInput">Your Comment</label>
                                            <textarea class="form-control" id="commentInput"
                                                      v-model="newComment"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <!-- Show a loading animation while the comment is being submitted -->
                                        <div v-if="loading" class="text-center mt-2">
                                            <i class="fa-solid fa-spinner animate-spin"></i> Loading...
                                        </div>
                                    </form>
                                </div>
                                <hr>
                                <div class="card-footer" v-for="comment in post.comments" :key="comment.id">
                                    <div class="row">
                                        <div class="col-1">
                                            <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30"
                                                 height="30" alt="">
                                        </div>
                                        <div class="col-10">
                                            <div class="card-header">
                                                <p>{{ comment.user_name }}</p>
                                                <p>{{ comment.comment }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" >
        <div class="container pt-3">
            <h3 class="text-center">Exercises</h3>
            <div v-if="quizzes" class="row mt-5">
                <div class="col-md-6 col-6 mt-3" v-for="(quiz, index) in quizzes" :key="index">
                    <div class="card">
                        <div class="card-header" style="color: black; font-weight: bold">
                            {{ quiz.name }}
                        </div>
                        <div class="card-body" style="color: black; font-weight: bold">
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ quiz.description }}</p>
                                    <p>Number of questions: {{ quiz.count_questions }}</p>
                                    <p>Score reporting: {{ quiz.score_reporting }}</p>
                                </div>
                                <div class="col-6">
                                    <p>Start time: {{ quiz.start_time }}</p>
                                    <p>End time: {{ quiz.end_time }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            :data-bs-target="'#exampleModal-' + index">
                                        Result
                                    </button>
                                    <div :id="'exampleModal-' + index" class="modal fade" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-fullscreen">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                        Detailed test results of students
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="pb-0 card-header">
                                                        <div class="d-lg-flex">
                                                            <div>
                                                                <h5 class="mb-0">Java - Lesson 1</h5>
                                                                <p class="mb-0 text-sm">
                                                                    List all result of test
                                                                </p>
                                                            </div>
                                                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                                                <div class="my-auto ms-auto">
                                                                    <button type="button"
                                                                            class="mx-1 mb-0 btn btn-outline-success btn-sm">
                                                                        Export to exel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="px-0 pb-0 pt-0 card-body">
                                                        <div class="table-responsive">
                                                            <DataTable id="setdata" :options="{ select: 'single' }"
                                                                       ref="table" class="table table-flush mx-3">
                                                                <thead class="thead-light">
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Name</th>
                                                                    <th>Score</th>
                                                                    <th>Time to finish</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr v-for="(result, index) in results" :key="index">
                                                                    <td>{{ result.id }}</td>
                                                                    <td>{{ result.name }}</td>
                                                                    <td>{{ result.score }}</td>
                                                                    <td>{{ result.time }}</td>
                                                                    <td>{{ result.status }}</td>
                                                                </tr>
                                                                </tbody>
                                                            </DataTable>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <button v-if="!quiz.submited"
                                            type="button"
                                            class="btn btn-primary">

                                        <router-link :to="'/lesson/test/' + quiz.id">
                                            Start
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
                <div class="col-12">
                    <button class="btn btn-primary position-fixed bottom-0 end-0 mt-3 ms-3" type="button">Add new
                        test
                    </button>
                </div>
            </router-link>
        </div>
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" >
        <div class="container pt-3">
            <h3 class="text-center">Members</h3>
            <div class="row">
                <div class="col-12">
                    <h4 class="pt-3">Teachers</h4>
                    <div class="card my-4" v-for="(teacher, index) in teacherArray" :key="'teacher-' + teacher.id">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30"
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
                                    <img :src="getUserInfo().gravatar" class="me-2 rounded-circle" width="30"
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
        };
    },
    beforeMount() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "learn/request") {
            } else if (mutation.type === "learn/requestSuccess") {
                this.data = mutation.payload;
                console.log(this.data);
            } else if (mutation.type === "learn/requestFailure") {
            }
        });
        this.$store.dispatch("learn/getNews", this.id);
        console.log(this.id);
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        console.log(this.user.email);
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.quizzes = mutation.payload.quizzes;
                this.members = mutation.payload.members;
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getClassDetail", {id: this.id, roleName: this.user.role.name});
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
        submitComment(e) {
            if (!this.newComment) return;
            // get the post id from data attribute
            const postId = e.target.getAttribute('data-post_id');
            const data = {
                post_id: postId,
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
        onSubmit() {
            const class_id = this.id;
            const title = "Class";
            const content = document.getElementById("questionContent").value;
            const tags = "Class";
            const status = "active";

            // Check if required fields are filled in
            if (!content) {
                alert("Please fill in all required fields.");
                return;
            }

            // Dispatch action to add new question
            this.$store.dispatch("home/addQuestionForum", {class_id, title, content, tags, status});

            // Reload page with animation after submitting form
            location.reload(true);
        },

        viewExerciseResult(exerciseId) {
            const url = '/class/detail/' + exerciseId;
            window.location.href = url;
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
