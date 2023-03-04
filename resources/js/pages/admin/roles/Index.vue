<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-3">
                <div class="card position-sticky top-1 mt-4">
                    <button v-if="checkPermission('admin.roles.create')" type="button"
                            class="mx-3 mb-0 mt-3 btn btn-outline-success" @click="showFormAddRole">
                        + New Role
                    </button>
                    <hr class="mb-1">
                    <ul class="nav flex-column bg-white border-radius-lg p-2">
                        <li v-for="role in roles"
                            :class="'nav-item d-flex justify-content-between align-items-center px-3 py-1 role' + [ getRoute() === role[1] ? ' active ' : '']"
                            :id="'role-'+role[0]">
                            <a class="text-body p-0" :href="'#' + role[1]" @click="getRoleUser(role[0])">
                                <span class="fs-5">{{ role[1] }}</span>
                                <p class="fs-6 m-0">{{ role[2] }}</p>
                            </a>
                            <i v-if="checkPermission('admin.roles.edit')" class="fa-regular fa-gear fs-5"
                               @click="showFormEditRole(role[0])"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-4">
                <div id="basic-info" class="card mt-4">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Role Users</h5>
                                <p class="mb-0 text-sm">List all user assigned to this role</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button v-if="checkPermission('admin.roles.create')" type="button"
                                            class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userModal">
                                        + Assign Users
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="user_data" :options="{select: 'single'}" ref="table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Updated At</th>
                                </tr>
                                </thead>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="roleModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="role-title" class="modal-title">
                        Option
                    </h5>
                    <i class="fa-solid fa-option ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-2">Name</label>
                            <argon-input id="name" type="text" name="name" placeholder="Role name"/>
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Description</label>
                            <argon-input id="description" type="text" name="description"
                                         placeholder="Role description"/>
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Permission</label>
                            <select
                                class="form-control"
                                data-trigger
                                name="permission"
                                id="permission"
                                multiple
                            >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm" id="role-submit">
                        Submit
                    </button>
                    <button v-if="this.mode === 'edit'" type="button" class="btn bg-gradient-danger btn-sm"
                            id="role-delete" @click="deleteRole">
                        Delete
                    </button>
                    <button type="button" class="btn bg-gradient-primary btn-sm" id="close-option"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapActions, mapGetters} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import {Modal} from "bootstrap";
import Choices from "choices.js";

DataTable.use(DataTablesLib);

export default {
    name: "Roles Index",
    components: {
        DataTable,
        ArgonButton,
        ArgonInput,
    },
    title() {
        return 'Roles Settings' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            roles: null,
            table: null,
            role_modal: null,
            permission_select: null,
            mode: 'create',
            id: null,
        }
    },
    methods: {
        getRoute() {
            return window.location.hash.replace('#', '');
        },
        ...mapActions({
            getRole: 'adminRole/index',
            getRoleUsers: 'adminRole/getRoleUsers',
            getRoleInfo: 'adminRole/getRoleInfo',
            getAllPermissions: 'adminRole/getAllPermission',
        }),
        ...mapGetters({
            permissions: 'account/permissions',
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
        getRoleUser(id) {
            this.getRoleUsers(id).then((response) => {
                this.table.clear();
                this.table.rows.add(response);
                this.table.draw();
            });
        },
        showFormEditRole(id) {
            this.mode = 'edit';
            document.getElementById('role-title').innerHTML = 'Edit Role';
            document.getElementById('role-submit').innerHTML = 'Update';
            // remove all selected option
            this.permission_select.removeActiveItems();
            this.role_modal.show();
            this.getRoleInfo(id).then((response) => {
                document.getElementById('name').value = response.role[1];
                document.getElementById('description').value = response.role[2];
                response.permissions.forEach((permission) => {
                    this.permission_select.setChoiceByValue(permission[0]);
                });
            });
            this.id = id;
            // remove event listener
            document.getElementById('role-submit').removeEventListener('click', null);
            // add event listener
            document.getElementById('role-submit').addEventListener('click', this.updateRole);
        },
        showFormAddRole() {
            this.mode = 'create';
            document.getElementById('role-title').innerHTML = 'Add Role';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('role-submit').innerHTML = 'Submit';
            // remove all selected option
            this.permission_select.removeActiveItems();
            this.role_modal.show();
            // remove event listener
            document.getElementById('role-submit').removeEventListener('click', null);
            // add event listener
            document.getElementById('role-submit').addEventListener('click', this.addRole);
        },
        addRole() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let permissions = this.permission_select.getValue(true);
            let data = {
                name: name,
                description: description,
                permissions: permissions,
            };
            if (name === '' || description === '' || permissions.length === 0) {
                alert('Please fill all fields and select at least one permission');
            } else {
                this.$store.dispatch('adminRole/addRole', data).then((response) => {
                    this.role_modal.hide();
                    this.roles.push(response);
                });
            }
        },
        updateRole() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let permissions = this.permission_select.getValue(true);
            let data = {
                name: name,
                description: description,
                permissions: permissions,
            };
            if (this.id === 1) {
                alert('You can not edit this role');
            } else if (name === '' || description === '' || permissions.length === 0) {
                alert('Please fill all fields and select at least one permission');
            } else {
                this.$store.dispatch('adminRole/updateRole', {id: this.id, data: data}).then((response) => {
                    this.role_modal.hide();
                    this.roles.forEach((role, index) => {
                        if (role[0] === this.id) {
                            this.roles[index] = response;
                        }
                    });
                });
            }
        },
        deleteRole() {
            if (this.id <= 6) {
                alert('You can not delete this role');
            } else {
                this.$swal({
                    icon: "question",
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$store.dispatch('adminRole/deleteRole', this.id).then((response) => {
                            if(response === true){
                                this.roles.forEach((role, index) => {
                                    if (role[0] === this.id) {
                                        this.roles.splice(index, 1);
                                    }
                                });
                                this.$swal({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Role has been deleted.",
                                    timer: 600,
                                });
                            }else{
                                this.$swal({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!",
                                    timer: 600,
                                });
                            }
                        }).then(() => {
                            this.role_modal.hide();
                        });
                    }
                });
            }
        },
    },
    beforeMount() {
        this.getRole().then((response) => {
            this.roles = response;
        });
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.role_modal = new Modal(document.getElementById('roleModal'), {
            keyboard: true,
            backdrop: 'static'
        });
        this.permission_select = new Choices('#permission', {
            removeItemButton: true,
            searchResultLimit: 5,
            searchFields: ['label', 'value'],
            shouldSort: false,
            itemSelectText: '',
            allowHTML: true,
        });
        this.getAllPermissions().then((response) => {
            this.permission_select.setChoices(response, 'value', 'label', true);
        });
    },
};
</script>

<style scoped>
.nav .nav-item:hover {
    background-color: #f3f3f3;
    border-radius: 0.5rem;
}

.nav .nav-item.active {
    background-color: #d2dbe4;
    color: #344767;
    border-radius: 0.5rem;
}

.choices__inner {
    max-height: 20vh;
    overflow-y: auto;
}
</style>
