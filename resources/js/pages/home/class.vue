<template>
    <div class="justify-content-center" id="navbarNav">
        <ul class="nav nav-pills bg-transparent border-0" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-news" type="button" role="tab" aria-controls="pills-news"
                        aria-selected="true" style="color: black; font-weight: bold">News
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-excercite-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-excercite" type="button" role="tab" aria-controls="pills-excercite"
                        aria-selected="false" style="color: black; font-weight: bold">Excercite
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-member-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-member" type="button" role="tab" aria-controls="pills-member"
                        aria-selected="false" style="color: black; font-weight: bold">Members
                </button>
            </li>

        </ul>
    </div>
    <div class="tab-pane fade" id="pills-news">nothing</div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-excercite" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="container pt-3">
                <h3 class="text-center">Excercite</h3>
                <div v-if="quizs.quizzes" class="row mt-5">
                    <div class="col-md-6 col-6 mt-3" v-for="(quiz, index) in quizs.quizzes" :key="index">
                        <div class="card">
                            <div class="card-header" style="color: black; font-weight: bold">
                                {{ quiz.name }}
                            </div>
                            <div class="card-body" style="color: black; font-weight: bold">
                                <div class="row">
                                    <div class="col-6">
                                        <p>{{ quiz.description }}</p>
                                        <p>Number of questions: {{ quiz.questions.length }}</p>
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
                                        <button
                                            type="button"
                                            class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            :data-bs-target="'#exampleModal-' + index"
                                        >
                                            Result
                                        </button>
                                        <div
                                            :id="'exampleModal-' + index"
                                            class="modal fade"
                                            tabindex="-1"
                                            aria-labelledby="exampleModalLabel"
                                            aria-hidden="true"
                                        >
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            Detailed test results of students
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"
                                                        ></button>
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
                                                                <div
                                                                    class="my-auto mt-4 ms-auto mt-lg-0"
                                                                >
                                                                    <div class="my-auto ms-auto">
                                                                        <button
                                                                            type="button"
                                                                            class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                                                        >
                                                                            Export to exel
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="px-0 pb-0 pt-0 card-body">
                                                            <div class="table-responsive">
                                                                <DataTable
                                                                    id="setdata"
                                                                    :options="{ select: 'single' }"
                                                                    ref="table"
                                                                    class="table table-flush mx-3"
                                                                >
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
                                                                    <tr
                                                                        v-for="(result, index) in results"
                                                                        :key="index"
                                                                    >
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
                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="button"
                                                class="btn btn-primary">
                                            <router-link
                                                :to="'/quiz/' + quiz.id"
                                            >
                                                Start
                                            </router-link>
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button type="button"
                                                class="btn btn-primary">
                                            <router-link
                                                :to="'/essay/' + quiz.id"
                                            >
                                                Essay
                                            </router-link>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-member">
        <div class="container">
            <h3 class="text-center">Member</h3>
            <div class="fixed-plugin">
                <a class="fixed-plugin-button  position-fixed p-3">Add</a>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="pt-3">Teacher</h4>
                    <div class="card my-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                <span class="ms-2 me-auto">Long Cute pho mai que</span>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <h4 class="pt-3">Students</h4>
                    <div class="card my-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                <span class="ms-2 me-auto">Long Cute</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-1" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                <ul class="dropdown-menu" aria-labelledby="student-options-1">
                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                    <li><a class="dropdown-item" href="#">Hide</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card my-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                <span class="ms-2 me-auto">Long Cute</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-2" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                <ul class="dropdown-menu" aria-labelledby="student-options-2">
                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                    <li><a class="dropdown-item" href="#">Hide</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card my-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                <span class="ms-2 me-auto">Long Cute</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-3" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                <ul class="dropdown-menu" aria-labelledby="student-options-3">
                                    <li><a class="dropdown-item" href="#">Delete</a></li>
                                    <li><a class="dropdown-item" href="#">Hide</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapActions} from "vuex";

DataTable.use(DataTablesLib);
export default {
    components: {
        DataTable,
    },
    data() {
        return {
            id: this.$route.params.id,
            quizs: [],
            showDetails: false,
            table: null,
            results: [
                {id: 1, name: "Vu Hai Long", score: "80", time: "15:34", status: "submitted"},
                {id: 2, name: "Ngo Tuan Anh", score: "89", time: "15:38", status: "unsubmitted"},
                {id: 3, name: "Dao Xuan Dat", score: "87", time: "15:34", status: "submitted"},
                {id: 4, name: "Nguyen Quang Tuan", score: "45", time: "35:34", status: "unsubmitted"},
                {id: 5, name: "Tran Manh Dat", score: "25", time: "15:34", status: "submitted"},
                {id: 6, name: "Pham Thi An", score: "95", time: "16:22", status: "unsubmitted"},
                {id: 7, name: "Nguyen Van A", score: "70", time: "14:56", status: "submitted"},
                {id: 8, name: "Le Thi B", score: "82", time: "16:08", status: "unsubmitted"},
                {id: 9, name: "Truong Duc C", score: "68", time: "15:12", status: "submitted"},
                {id: 10, name: "Doan Minh D", score: "93", time: "17:04", status: "submitted"},
                {id: 11, name: "Le Tran Gia Huy", score: "75", time: "15:50", status: "unsubmitted"},
                {id: 12, name: "Phan Thi Kim Ngan", score: "78", time: "16:34", status: "submitted"}

            ],
        };
    },
    created() {
        this.$store.dispatch("home/getQuiz", this.id).then(
            quiz => {
                this.quizs = quiz;
                console.log(this.quizs);
            });
    },
    mounted() {

    },

    methods: {
        ...mapActions({
            getQuiz: "home/getQuiz",
        }),
        onSubmit() {
            // Handle form submission here
        },

        viewExerciseResult(exerciseId) {
            const url = '/class/detail/' + exerciseId;
            window.location.href = url;
        },
    }
}
</script>
