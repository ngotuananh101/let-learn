<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Course</h5>
                                <p class="mb-0 text-sm">List of all courses</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="courseModal.show()">
                                        Add Course
                                        <i class="fa-regular fa-user-plus ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="course_datatable" class="table table-flush"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="courseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Course</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-12">
                            <label class="form-label mt-3">Name</label>
                            <input
                                id="name"
                                name="name"
                                class="form-control"
                                type="text"
                                placeholder="Name"
                                v-model="course.name"
                            />
                        </div>
                        <div class="col-md-7 col-12">
                            <label class="form-label mt-3">Description</label>
                            <input
                                id="description"
                                name="description"
                                class="form-control"
                                type="text"
                                placeholder="Description"
                                v-model="course.description"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="this.add">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {DataTable, exportCSV} from "simple-datatables";

export default {
    name: "CourseIndex",
    title() {
        return "Course Management";
    },
    data() {
        return {
            courses: null,
            table: null,
            unsubscribe: null,
            type: 'get',
            courseModal: null,
            course: {
                name: null,
                description: null,
            },
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminCourse/request') {
            } else if (mutation.type === 'adminCourse/success') {
                if(this.type === 'get') {
                    this.courses = mutation.payload.courses;
                    this.init();
                } else if(this.type === 'add') {
                    this.$root.showSnackbar('User added successfully', 'success');
                    this.courseModal.hide();
                    this.courses.push(mutation.payload);
                    this.table.rows.add([
                        mutation.payload.id,
                        mutation.payload.name,
                        mutation.payload.description,
                        mutation.payload.user.name,
                        mutation.payload.school ? mutation.payload.school.name : 'N/A',
                        mutation.payload.class ? mutation.payload.class.name : 'N/A',
                        'active',
                    ]);
                }
            } else if (mutation.type === 'adminCourse/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('adminCourse/getAll');
    },
    beforeUnmount() {
        this.unsubscribe();
        this.table.destroy();
    },
    methods: {
        exportCSV,
        init() {
            this.table = new DataTable("#course_datatable", {
                data: {
                    headings: [
                        "ID",
                        "Name",
                        "Description",
                        "Creator",
                        "School",
                        "Class",
                        "Status",
                    ],
                    data: this.courses.map((course) => {
                        return [
                            course.id,
                            course.name,
                            course.description,
                            course.user.name,
                            course.school ? course.school.name : 'N/A',
                            course.class ? course.class.name : 'N/A',
                            course.status,
                        ];
                    }),
                },
            });
            this.table.on('datatable.selectrow', (row_index) => {
                this.$router.push({name: 'admin.course.edit', params: {id: this.courses[row_index].id}});
            });
            const bootstrap = this.$store.state.config.bootstrap;
            this.courseModal = new bootstrap.Modal(document.getElementById('courseModal'), {
                keyboard: false,
            });
        },
        add(){
            this.type = 'add';
            this.$store.dispatch('adminCourse/add', this.course);
        }
    }
};
</script>
