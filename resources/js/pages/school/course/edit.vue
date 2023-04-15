<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Update Course</h5>
                                <p class="mb-0 text-sm">Update course details</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="updateInfo">
                                        Update
                                    </button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-danger btn-sm"
                                            @click="deleteCourse">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-3 col-12">
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
                            <div class="col-md-6 col-12">
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
                            <div class="col-md-2 col-12">
                                <label class="form-label mt-3">Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    type="text"
                                    placeholder="Password"
                                    v-model="course.password"
                                />
                            </div>
                            <div class="col-md-1 col-12">
                                <label class="form-label mt-3">Status</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="form-control"
                                    v-model="course.status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 fw-bold">Lesson in this course</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        @click="lesson_modal.show()">
                                    Add Lesson
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" @click="removeLesson">
                                    Remove Lesson
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="lesson_datatable" class="table table-flush"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addLessonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Course</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-3">Search lesson by name</label>
                            <select
                                id="lesson"
                                name="lesson"
                                class="form-control"
                                multiple
                            >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="addLesson">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {DataTable} from "simple-datatables";
import Choices from "choices.js";

export default {
    name: "edit",
    title() {
        return "Edit " + this.course.name;
    },
    data() {
        return {
            course: {
                name: "",
                description: "",
                password: "",
                status: "",
                lessons: [],
            },
            unsubscribe: null,
            type: 'get',
            lesson_datatable: null,
            lesson_modal: null,
            lesson_choice: null,
            timer: null,
            selectedRows: [],
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'schoolCourse/success') {
                if (this.type === 'get') {
                    this.course = mutation.payload;
                    this.init();
                } else if (this.type === 'update_info') {
                    this.$root.showSnackbar('Update course info successfully', 'success');
                } else if (this.type === 'search_lesson') {
                    this.$root.showSnackbar('Search lesson successfully', 'success');
                    // remove unselected lesson from lesson_choice
                    this.lesson_choice.removeActiveItemsByValue(this.course.lessons.map((lesson) => lesson.id));
                    // add new lesson to lesson_choice
                    this.lesson_choice.setChoices(mutation.payload, 'id', 'name', true);
                } else if (this.type === 'add_lesson') {
                    this.$root.showSnackbar('Add lesson successfully', 'success');
                    location.reload();
                } else if (this.type === 'delete') {
                    this.$root.showSnackbar('Delete course successfully', 'success');
                    this.$router.push({name: 'school.course'});
                } else if (this.type === 'remove_lesson') {
                    this.$root.showSnackbar('Remove lesson successfully', 'success');
                    this.lesson_datatable.rows.remove(this.selectedRows);
                    this.selectedRows = [];
                    this.lesson_datatable.update();
                }
            } else if (mutation.type === 'schoolCourse/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            } else if (mutation.type === 'schoolLesson/success') {
                let lessons = mutation.payload.lessons;
                this.lesson_choice.setChoices(lessons, 'id', 'name', true);
            }
        });
        this.$store.dispatch('schoolCourse/edit', this.$route.params.id);
    },
    beforeUnmount() {
        this.unsubscribe();
        this.lesson_datatable ? this.lesson_datatable.destroy() : null;
        this.lesson_modal.dispose();
        this.lesson_choice.destroy();
    },
    methods: {
        init() {
            this.lesson_datatable = new DataTable("#lesson_datatable", {
                data: {
                    headings: ["ID", "Name", "Description", "Class", "Update At", "Status", "Action"],
                    data: this.course.lessons.map((lesson) => {
                        return [
                            lesson.id,
                            lesson.name,
                            lesson.description,
                            lesson.class ? lesson.class.name : 'N/A',
                            new Date(lesson.updated_at).toLocaleString(),
                            lesson.status,
                            `<a href="/school/${this.$route.params.slug}/lesson/${lesson.id}/edit" class="btn btn-sm btn-outline-primary">Edit</a>`
                        ];
                    }),
                },
                rowRender: (_row, tr, index) => {
                    if (!this.selectedRows.includes(index)) {
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
            const bootstrap = this.$store.state.config.bootstrap;
            this.lesson_modal = new bootstrap.Modal(document.getElementById('addLessonModal'));
            this.lesson_choice = new Choices('#lesson', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
                addItems: true,
                searchResultLimit: 10,
                duplicateItemsAllowed: false,
                placeholder: true,
                placeholderValue: 'Enter lesson name ',
            });
            this.lesson_datatable.on("datatable.selectrow", (rowIndex, event) => {
                if (this.selectedRows.includes(rowIndex)) {
                    this.selectedRows = this.selectedRows.filter(row => row !== rowIndex)
                } else {
                    this.selectedRows.push(rowIndex)
                }
                this.lesson_datatable.update();
            });
            this.$store.dispatch('schoolLesson/index', this.$route.params.slug);
        },
        updateInfo() {
            this.type = 'update_info';
            this.$store.dispatch('schoolCourse/updateCourse', {
                type: 'update',
                id: this.$route.params.id,
                name: this.course.name,
                description: this.course.description,
                password: this.course.password,
                status: this.course.status,
            });
        },
        addLesson() {
            this.type = 'add_lesson';
            this.$store.dispatch('schoolCourse/addLessonToCourse', {
                id: this.$route.params.id,
                lesson_ids: this.lesson_choice.getValue(true),
            });
        },
        deleteCourse() {
            if (confirm('Are you sure?')) {
                this.type = 'delete';
                this.$store.dispatch('schoolCourse/deleteCourse', this.$route.params.id);
            }
        },
        removeLesson() {
            if (confirm('Are you sure?')) {
                this.type = 'remove_lesson';
                this.$store.dispatch('schoolCourse/removeLessonFromCourse', {
                    id: this.$route.params.id,
                    lesson_ids: this.selectedRows.map((row) => this.course.lessons[row].id),
                });
            }
        },
    },
}
</script>

<style scoped>
.selected {
    background-color: var(--bs-gray-400) !important;
}
</style>
