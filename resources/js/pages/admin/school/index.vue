<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">School</h5>
                                <p class="mb-0 text-sm">School management</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="showFormAdd">
                                        Add School
                                        <i class="fa-regular fa-school ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="school_datatable" class="table table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Website</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="schoolModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">School</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-12">
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
                        <div class="col-md-4 col-12">
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
                                        class="img-fluid mt-3 rounded" alt="logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="this.addSchool">
                        Add
                    </button>
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
        return "School management";
    },
    data() {
        return {
            data: null,
            table: null,
            school_modal: null,
            unsubscribe: null,
            type: 'get',
            school: {
                name: null,
                slug: null,
                website: null,
                address: null,
                city: null,
                region: null,
                logo: null,
                status: 'active',
            },
            selected_id: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminSchool/request') {

            } else if (mutation.type === 'adminSchool/success') {
                if (this.type === 'get') {
                    this.data = mutation.payload.school;
                    this.init();
                } else if (this.type === 'add') {
                    this.$root.showSnackbar('Add school success', 'success');
                    this.school_modal.hide();
                    this.$store.dispatch('adminSchool/getSchoolList');
                }
            } else if (mutation.type === 'adminSchool/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('adminSchool/getSchoolList');
    },
    beforeUnmount() {
        this.unsubscribe();
        this.school_modal.hide();
        this.type = null;
    },
    methods: {
        init() {
            this.table = new DataTable("#school_datatable", {
                searchable: true,
                fixedHeight: true,
            });
            let bootstrap = this.$store.state.config.bootstrap;
            this.school_modal = new bootstrap.Modal(document.getElementById('schoolModal'), {
                keyboard: false
            });
            this.data.forEach((item) => {
                let editBtn = document.createElement('a');
                editBtn.className = 'btn btn-sm btn-primary';
                editBtn.innerHTML = 'Edit';
                editBtn.href = '/admin/school/' + item.id + '/edit';
                this.table.rows.add([item.id, item.name, item.website, item.address, item.city, item.region, item.status, editBtn.outerHTML]);
            });
        },
        showFormAdd() {
            this.type = 'add';
            this.school = {
                name: null,
                slug: null,
                website: null,
                address: null,
                city: null,
                region: null,
                logo: null,
                status: 'active',
            };
            this.school_modal.show();
        },
        addSchool() {
            this.$store.dispatch('adminSchool/addSchool', this.school);
        },
    }
};
</script>
