<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-md-2 col-12">
                <div class="card mt-4">
                    <div class="card-body pb-0">
                        <ul class="nav flex-column bg-white border-radius-lg">
                            <li class="nav-item d-flex justify-content-between align-items-center mb-2 border-radius-lg"
                                :class="index === this.selected_role ? 'active' : ''" v-for="(item, index) in data">
                                <a class="nav-link text-body align-items-center py-1 w-100"
                                   href="#" @click="selectRole(index)">
                                    <p class="mb-0 font-weight-bold">{{ item.name }}</p>
                                    <span class="text-xs">{{ item.description }}</span>
                                </a>
                                <div @click="editRole(item.id)"><i class="fa-duotone fa-gear p-3"></i></div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-outline-success btn-sm mb-0 w-100"
                                @click="formAddRole">
                            Add Role
                            <i class="fa-solid fa-users-medical ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-12">
                <div id="info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Role</h5>
                                <p class="mb-0 text-sm">View, add, update, delete user</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="assigned_modal.show()">
                                        Assigned User
                                        <i class="fa-regular fa-user-plus ms-2"></i>
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
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="assignedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h1 class="modal-title fs-5">Assigned User</h1>
                        <span> Enter the user email to assign to this role</span>
                    </div>
                </div>
                <div class="modal-body">
                    <label class="form-label mt-2">User email</label>
                    <input
                        id="user_email"
                        name="user_email"
                        class="form-control"
                        type="text"
                        placeholder="User email"
                        v-model="user_email"
                    />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="assignedUser">
                        Assigned
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="roleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Role</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-2">Name</label>
                            <input
                                id="name"
                                name="name"
                                class="form-control"
                                type="text"
                                placeholder="User email"
                                v-model="role.name"
                            />
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-2">Description</label>
                            <input
                                id="description"
                                name="description"
                                class="form-control"
                                type="text"
                                placeholder="Description"
                                v-model="role.description"
                            />
                        </div>
                        <div class="col-12">
                            <label class="form-label mt-2">Permission</label>
                            <select
                                id="permission"
                                name="permission"
                                class="form-control"
                                multiple>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button v-if="this.type === 'update_role'" type="button" class="btn btn-primary"
                            @click="updateRole">
                        Update
                    </button>
                    <button v-if="role.id > 6" type="button" class="btn btn-danger" @click="deleteRole">
                        Delete
                    </button>
                    <button v-if="this.type === 'add_role'" type="button" class="btn btn-primary" @click="addRole">
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
    name: "Role",
    title() {
        return "Role Management";
    },
    data() {
        return {
            data: null,
            unsubscribe: null,
            type: 'list',
            table: null,
            selected_role: 0,
            assigned_modal: null,
            bootstrap: null,
            user_email: null,
            role: {},
            role_modal: null,
            permission: null,
            select_permission: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminRole/request') {
            } else if (mutation.type === 'adminRole/success') {
                if (this.type === 'list') {
                    this.data = mutation.payload.roles;
                    this.permission = mutation.payload.permissions;
                    this.init();
                } else if (this.type === 'get_user') {
                    this.resetTable(mutation.payload.role.users);
                    this.clickEvent();
                } else if (this.type === 'unassigned') {
                    this.$root.showSnackbar('Unassigned user from role successfully new role of user is \'user\'', 'success');
                } else if (this.type === 'assigned') {
                    this.$root.showSnackbar('Assigned user to role successfully', 'success');
                } else if (this.type === 'get_role') {
                    this.role = mutation.payload.role;
                    // clear select value
                    this.select_permission.removeActiveItems();
                    // set multiple select value
                    let permission = [];
                    this.role.permissions.forEach((item) => {
                        permission.push(item.id);
                    });
                    this.select_permission.setChoiceByValue(permission);
                    this.type = 'update_role';
                } else if (this.type === 'update_role') {
                    this.$root.showSnackbar('Update role successfully', 'success');
                } else if (this.type === 'delete_role') {
                    this.data = this.data.filter((item) => {
                        return item.id !== this.role.id;
                    });
                    this.role_modal.hide();
                    this.$root.showSnackbar('Delete role successfully', 'success');
                } else if (this.type === 'add_role') {
                    this.data.push(mutation.payload.role);
                    this.role_modal.hide();
                    this.$root.showSnackbar('Add role successfully', 'success');
                }
            } else if (mutation.type === 'adminRole/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('adminRole/getRoleList');
    },
    mounted() {
    },
    beforeUnmount() {
        this.unsubscribe();
        this.table.destroy();
        this.assigned_modal.dispose();
        this.role_modal.dispose();
    },
    methods: {
        init() {
            this.table = new DataTable("#user_datatable", {
                searchable: true,
                fixedHeight: true,
            });
            this.type = 'get_user';
            this.$store.dispatch('adminRole/getUserByRole', this.data[0].id);
            let bootstrap = this.$store.state.config.bootstrap;
            this.assigned_modal = new bootstrap.Modal(document.getElementById('assignedModal'));
            this.role_modal = new bootstrap.Modal(document.getElementById('roleModal'));
            this.select_permission = new Choices('#permission', {
                removeItemButton: true,
                searchFields: ['label', 'value'],
                itemSelectText: '',
                allowHTML: true,
                placeholder: true,
                placeholderValue: 'Select permission ',
            });
            this.select_permission.setChoices(this.permission, 'id', 'name', true);
        },
        resetTable(data) {
            this.table.data.data = [];
            this.table.update();
            if (data.length > 0) {
                data.map((item) => {
                    this.table.rows.add([
                        item.id,
                        item.name,
                        item.username,
                        item.email,
                        new Date(item.created_at).toLocaleString(),
                        item.status
                            ? '<span class="badge bg-gradient-success">Active</span>'
                            : '<span class="badge bg-gradient-danger">Inactive</span>',
                        '<a class="unassigned" title="Unassigned user from role"><i class="fa-regular fa-trash text-danger"></i></a>'
                    ]);
                });
            }
        },
        selectRole(index) {
            if (this.selected_role === index) return;
            this.selected_role = index;
            this.type = 'get_user';
            this.$store.dispatch('adminRole/getUserByRole', this.data[index].id);
        },
        editRole(id) {
            this.type = 'get_role';
            this.$store.dispatch('adminRole/getRole', id);
            this.role_modal.show();
        },
        clickEvent() {
            document.querySelectorAll('.unassigned').forEach((item) => {
                item.addEventListener('click', (e) => {
                    let user_id = e.target.closest('tr').children[0].innerText;
                    let role_id = this.data[this.selected_role].id;
                    if (confirm('Are you sure to unassigned this user?')) {
                        this.type = 'unassigned';
                        this.$store.dispatch('adminRole/unassignedRole', {user_id, role_id});
                    }
                });
            });
        },
        assignedUser() {
            this.type = 'assigned';
            this.$store.dispatch('adminRole/assignedRole',
                {
                    email: this.user_email,
                    role_id: this.data[this.selected_role].id
                });
        },
        updateRole() {
            this.$store.dispatch('adminRole/updateRole', {
                id: this.role.id,
                name: this.role.name,
                description: this.role.description,
                permission: this.select_permission.getValue(true)
            });
        },
        formAddRole() {
            this.type = 'add_role';
            this.role = {};
            this.role_modal.show();
        },
        addRole() {
            this.$store.dispatch('adminRole/addRole', {
                name: this.role.name,
                description: this.role.description,
                permission: this.select_permission.getValue(true)
            });
        },
        deleteRole() {
            if (confirm('Are you sure to delete this role?')) {
                this.type = 'delete_role';
                this.$store.dispatch('adminRole/deleteRole', this.role.id);
            }
        }
    }
};
</script>

<style scoped>
.nav li.active {
    background-color: var(--bs-gray-300);
}
</style>
