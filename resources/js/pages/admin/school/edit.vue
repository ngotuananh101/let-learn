<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Update School</h5>
                                <p class="mb-0 text-sm">Update school information</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="updateSchool">
                                        Update
                                    </button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-danger btn-sm"
                                            @click="deleteSchool">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-3">Name</label>
                                        <input
                                            id="name"
                                            name="name"
                                            class="form-control"
                                            type="text"
                                            placeholder="Name"
                                            v-model="this.school.name"
                                        />
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-3">Slug</label>
                                        <input
                                            id="slug"
                                            name="slug"
                                            class="form-control"
                                            type="text"
                                            placeholder="Slug can null"
                                            v-model="this.school.slug"
                                        />
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <label class="form-label mt-3">Website</label>
                                        <input
                                            id="website"
                                            name="website"
                                            class="form-control"
                                            type="text"
                                            placeholder="Website"
                                            v-model="this.school.website"
                                        />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-3">Address</label>
                                        <input
                                            id="address"
                                            name="address"
                                            class="form-control"
                                            type="text"
                                            placeholder="Address"
                                            v-model="this.school.address"
                                        />
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-3">City</label>
                                        <input
                                            id="city"
                                            name="city"
                                            class="form-control"
                                            type="text"
                                            placeholder="City"
                                            v-model="this.school.city"
                                        />
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-3">Region / Country</label>
                                        <input
                                            id="region"
                                            name="region"
                                            class="form-control"
                                            type="text"
                                            placeholder="Region / Country"
                                            v-model="this.school.region"
                                        />
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label mt-3">Status</label>
                                        <select class="form-select" v-model="this.school.status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-3">Logo</label>
                                        <input
                                            id="logo"
                                            name="logo"
                                            class="form-control"
                                            type="text"
                                            placeholder="Logo"
                                            v-model="this.school.logo"
                                        />
                                        <img
                                            :src="this.school.logo ?? 'https://t3.ftcdn.net/jpg/02/68/55/60/360_F_268556012_c1WBaKFN5rjRxR2eyV33znK4qnYeKZjm.jpg'"
                                            class="w-100 img-fluid mt-3 rounded" alt="logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="user-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">School user</h5>
                                <p class="mb-0 text-sm">View school user</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="school_user_datatable" class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="class-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">School class</h5>
                                <p class="mb-0 text-sm">View school class</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="school_class_datatable" class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {DataTable} from "simple-datatables";

export default {
    name: "School",
    title() {
        return "Edit " + this.school.name + " school";
    },
    data() {
        return {
            id: this.$route.params.id,
            school: {},
            users: {},
            classes: {},
            unsubscribe: null,
            type: 'get',
            schoolUserTable: null,
            schoolClassTable: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminSchool/request') {
            } else if (mutation.type === 'adminSchool/success') {
                if(this.type === 'get') {
                    this.school = mutation.payload.school;
                    this.users = mutation.payload.users;
                    this.classes = mutation.payload.classes;
                    this.init();
                } else if(this.type === 'update') {
                    this.$root.showSnackbar('Update school success', 'success');
                } else if(this.type === 'delete') {
                    this.$root.showSnackbar('Delete school success', 'success');
                    this.$router.push({name: 'admin.school'});
                }
            } else if (mutation.type === 'adminSchool/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('adminSchool/getSchoolById', this.id);
    },
    beforeUnmount() {
        this.unsubscribe();
        this.schoolUserTable.destroy();
        this.schoolClassTable.destroy();
    },
    methods: {
        init() {
            this.schoolUserTable = new DataTable("#school_user_datatable", {
                searchable: true,
                fixedHeight: true,
            });
            this.schoolClassTable = new DataTable("#school_class_datatable", {
                searchable: true,
                fixedHeight: true,
            });
            this.users.forEach((user) => {
                this.schoolUserTable.rows.add([
                    user.id,
                    user.role.name,
                    user.name,
                    user.username,
                    user.email,
                    new Date(user.created_at).toLocaleString(),
                    user.status,
                ]);
            });
            this.classes.forEach((classs) => {
                this.schoolClassTable.rows.add([
                    classs.id,
                    classs.name,
                    new Date(classs.start_date).toLocaleString(),
                    new Date(classs.end_date).toLocaleString(),
                    classs.status,
                ]);
            });
        },
        updateSchool() {
            this.type = 'update';
            this.$store.dispatch('adminSchool/updateSchool', this.school);
        },
        deleteSchool() {
            if(confirm('Are you sure to delete this school?')) {
                this.type = 'delete';
                this.$store.dispatch('adminSchool/deleteSchool', this.id);
            }
        },
    }
};
</script>
