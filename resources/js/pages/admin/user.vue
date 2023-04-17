<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">User</h5>
                                <p class="mb-0 text-sm">View, add, update, delete user</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="user_modal.show()">
                                        Add User
                                        <i class="fa-regular fa-user-plus ms-2"></i>
                                    </button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-warning btn-sm"
                                            @click="()=>{
                                                exportCSV(table, {
                    download: true,
                    lineDelimiter: '\n',
                                    columnDelimiter: ';'
                                    })
                                            }">
                                        Export List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="user_datatable" class="table table-flush">
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
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">User</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">Name</label>
                            <input
                                id="name"
                                name="name"
                                class="form-control"
                                type="text"
                                placeholder="Name"
                                v-model="user.name"
                            />
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">Username</label>
                            <input
                                id="username"
                                name="username"
                                class="form-control"
                                type="text"
                                placeholder="Username"
                                v-model="user.username"
                            />
                        </div>
                        <div class="col-md-8 col-12">
                            <label class="form-label mt-3">Email</label>
                            <input
                                id="email"
                                name="email"
                                class="form-control"
                                type="email"
                                placeholder="Email"
                                v-model="user.email"
                            />
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-3">Role</label>
                            <select
                                id="role"
                                name="role"
                                class="form-control"
                                v-model="user.role_id">
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-3">Date of birth</label>
                            <input
                                id="date_of_birth"
                                name="date_of_birth"
                                class="form-control"
                                type="date"
                                placeholder="Date of birth"
                                v-model="user.date_of_birth"
                            />
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-3">Email verified at</label>
                            <input
                                id="email_verified_at"
                                name="email_verified_at"
                                class="form-control"
                                type="datetime-local"
                                placeholder="Email verified at"
                                v-model="user.email_verified_at"
                            />
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label mt-3">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="form-control"
                                v-model="user.status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <p class="m-0 mt-5 font-italic">* Required only when adding a new user</p>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-1">Password</label>
                            <input
                                id="password"
                                name="password"
                                class="form-control"
                                type="password"
                                placeholder="Password"
                                v-model="user.password"
                            />
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-1">Password confirmation</label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                type="password"
                                placeholder="Password confirmation"
                                v-model="user.password_confirmation"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button v-if="this.type === 'add'" type="button" class="btn btn-success" @click="this.addUser">
                        Add
                    </button>
                    <button v-if="this.type === 'update'" type="button" class="btn btn-warning"
                            @click="this.updateUser">
                        Update
                    </button>
                    <button v-if="this.type === 'update'" type="button" class="btn btn-danger" @click="this.deleteUser">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {DataTable, exportCSV} from "simple-datatables";

export default {
    name: "User",
    title() {
        return "User Management";
    },
    data() {
        return {
            data: null,
            table: null,
            user_modal: null,
            unsubscribe: null,
            type: 'list',
            user: {
                id: null,
                role_id: null,
                name: null,
                username: null,
                email: null,
                date_of_birth: null,
                email_verified_at: null,
                status: null,
                password: null,
                password_confirmation: null,
            },
            selected_row: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminUser/request') {
                if (this.type === 'list') {
                }
            } else if (mutation.type === 'adminUser/success') {
                if (this.type === 'list') {
                    this.data = mutation.payload;
                    this.init()
                } else if (this.type === 'add') {
                    this.$root.showSnackbar('User added successfully', 'success');
                    this.user_modal.hide();
                } else if (this.type === 'update') {
                    this.$root.showSnackbar('User updated successfully', 'success');
                    this.user_modal.hide();
                    location.reload();
                } else if (this.type === 'delete') {
                    this.$root.showSnackbar('User deleted successfully', 'success');
                    this.table.rows.remove(this.selected_row);
                    this.user_modal.hide();
                }
            } else if (mutation.type === 'adminUser/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            } else if (mutation.type === 'adminRole/success') {
                mutation.payload.roles.forEach((role) => {
                    let option = document.createElement("option");
                    option.value = role.id;
                    option.text = role.name;
                    document.getElementById("role").appendChild(option);
                });
            }
        });
        this.$store.dispatch('adminUser/getUserList');
        this.$store.dispatch('adminRole/getRoleList');
    },
    beforeUnmount() {
        this.unsubscribe();
        this.table.destroy();
        this.user_modal.hide();
        this.user_modal.dispose();
    },
    methods: {
        exportCSV,
        init() {
            this.table = new DataTable("#user_datatable", {
                searchable: true,
                fixedHeight: true,
            });
            this.table.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                this.selected_row = rowIndex;
                this.type = 'update';
                this.user = {
                    id: this.data[rowIndex].id,
                    role_id: this.data[rowIndex].role_id,
                    name: this.data[rowIndex].name,
                    username: this.data[rowIndex].username,
                    email: this.data[rowIndex].email,
                    date_of_birth: this.data[rowIndex].date_of_birth,
                    email_verified_at: this.data[rowIndex].email_verified_at ? new Date(this.data[rowIndex].email_verified_at).toISOString().slice(0, 16) : null,
                    status: this.data[rowIndex].status,
                    password: null,
                    password_confirmation: null,
                };
                this.user_modal.show();
            });
            this.data.map((item) => {
                let data = [item.id, item.role.name, item.name, item.username, item.email, new Date(item.created_at).toLocaleString(), item.status];
                this.table.rows.add(data);
            });
            const bootstrap = this.$store.state.config.bootstrap;
            this.user_modal = new bootstrap.Modal(document.getElementById('userModal'), {
                keyboard: false
            });
            this.user_modal._element.addEventListener('hidden.bs.modal', () => {
                this.selected_row = null;
                this.type = 'add';
                this.user = {
                    id: null,
                    role_id: null,
                    name: null,
                    username: null,
                    email: null,
                    date_of_birth: null,
                    email_verified_at: null,
                    status: null,
                    password: null,
                    password_confirmation: null,
                }
            });
            this.type = 'add';
        },
        addUser() {
            console.log(this.user);
            this.$store.dispatch('adminUser/addUser', this.user);
        },
        updateUser() {
            this.$store.dispatch('adminUser/updateUser', this.user);
        },
        deleteUser() {
            if (confirm('Are you sure you want to delete this user?')) {
                this.type = 'delete';
                this.$store.dispatch('adminUser/deleteUser', this.user.id);
            }
        },
    }
};
</script>
