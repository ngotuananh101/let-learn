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
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Name</label>
                                <input id="name" name="name" class="form-control" type="text" placeholder="Name"
                                    v-model="course.name" />
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-3">Description</label>
                                <input id="description" name="description" class="form-control" type="text"
                                    placeholder="Description" v-model="course.description" />
                            </div>
                            <div class="col-md-2 col-12">
                                <label class="form-label mt-3">Password</label>
                                <input id="password" name="password" class="form-control" type="text" placeholder="Password"
                                    v-model="course.password" />
                            </div>
                            <div class="col-md-1 col-12">
                                <label class="form-label mt-3">Status</label>
                                <select id="status" name="status" class="form-control" v-model="course.status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-lg-flex">
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                        @click="updateInfo">
                                        Update
                                    </button>
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
import { DataTable } from "simple-datatables";
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
            },
            type: 'get',
            user: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'course/request') {
                if (this.type === 'update_info') {
                    this.$root.showSnackbar('Updating course info...', 'info');
                }
            } else if (mutation.type === 'course/success') {
                if (this.type === 'get') {
                    this.data = mutation.payload;
                    this.course = this.data.course;
                    this.lessons = this.data.course.lessons;
                } else if (this.type === 'update_info') {
                    this.$root.showSnackbar('Update course info successfully', 'success');
                } else if (this.type === 'delete') {
                    this.$root.showSnackbar('Delete course successfully', 'success');
                    this.$router.push({ name: 'home.home' });
                }
            } else if (mutation.type === 'course/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('course/getLessonByCourseId', { id: this.$route.params.id, roleName: this.user.role.name });
    },
    methods: {
        updateInfo() {
            this.type = 'update_info';
            this.$store.dispatch('course/updateCourse', {
                type: 'info',
                id: this.$route.params.id,
                name: this.course.name,
                description: this.course.description,
                password: this.course.password,
                status: this.course.status,
                roleName: this.user.role.name,
            });
        },
    },
}
</script>

<style scoped></style>
