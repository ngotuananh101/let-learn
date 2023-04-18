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
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">
                                        Add Class
                                        <i class="fa-regular fa-user-plus ms-2"></i>
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
                        <div class="col-md-3 col-12">
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
                        <div class="col-md-3 col-12">
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
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">
                                        Add Class
                                        <i class="fa-regular fa-user-plus ms-2"></i>
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
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">
                                        Add Class
                                        <i class="fa-regular fa-user-plus ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="class_quiz_datatable" class="table table-flush"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {DataTable, exportCSV} from "simple-datatables";

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
            class_info: {}
        }
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            switch (mutation.type) {
                case 'schoolClass/request':
                    break;
                case 'schoolClass/success':
                    if (this.type === 'get') {
                        console.log(mutation.payload);
                        this.class_info = mutation.payload.class;
                        this.init();
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
            });
            this.class_quiz_datatable = new DataTable('#class_quiz_datatable', {
                data: {
                    headings: ['ID', 'Name', 'Start time', 'End time', 'Status'],
                    data: this.class_info.quizzes ? this.class_info.quizzes.map((quiz) => {
                        return [quiz.id, quiz.name, quiz.start_time, quiz.end_time, quiz.status];
                    }) : [],
                },
            });
        }
    }
}
</script>

<style scoped>
.modal {
    z-index: 9999 !important;
}
</style>
