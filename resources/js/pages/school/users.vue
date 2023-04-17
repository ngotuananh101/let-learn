<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="p-3 pb-0 card-header d-flex">
                        <h6 class="my-auto">User management</h6>
                        <div class="nav-wrapper position-relative ms-auto w-25">
                            <ul class="p-1 nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a
                                        class="px-0 py-1 mb-0 nav-link active"
                                        data-bs-toggle="tab"
                                        href="#manager"
                                        role="tab"
                                        aria-controls="manager"
                                        aria-selected="true"
                                    >Manager</a
                                    >
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="px-0 py-1 mb-0 nav-link"
                                        data-bs-toggle="tab"
                                        href="#teacher"
                                        role="tab"
                                        aria-controls="teacher"
                                        aria-selected="false"
                                    >Teacher</a
                                    >
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="px-0 py-1 mb-0 nav-link"
                                        data-bs-toggle="tab"
                                        href="#student"
                                        role="tab"
                                        aria-controls="student"
                                        aria-selected="false"
                                    >Student</a
                                    >
                                </li>
                            </ul>
                        </div>
                        <div class="pt-1 ps-md-2 dropdown">
                            <a
                                id="dropdownOption"
                                href="#"
                                class="text-secondary ps-4 btn btn-sm btn-outline-success"
                                :class="{ show: showMenu }"
                                data-bs-toggle="dropdown"
                                aria-expanded="true"
                                @click="showMenu = !showMenu"
                            >
                                Options
                                <i class="fa-solid fa-option"></i>
                            </a>
                            <ul
                                class="px-2 py-3 dropdown-menu dropdown-menu-end me-sm-n3"
                                :class="{ show: showMenu }"
                                aria-labelledby="dropdownOption"
                            >
                                <li>
                                    <a class="dropdown-item border-radius-md" href="#" @click="this.export('manager')">Export
                                        manager</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="#" @click="this.export('teacher')">Export
                                        teacher</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="#" @click="this.export('student')">Export
                                        student</a
                                    >
                                </li>
                                <li>
                                    <hr class="dropdown-divider"/>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md text-success" href="#"
                                       @click="showFormAddUser">
                                        Add user</a
                                    >
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md text-success" href="#"
                                       @click="showFormImportUser"
                                    >Import user</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="p-3 mt-2 card-body" style="min-height: 80vh; max-height: unset;">
                        <div id="v-pills-tabContent" class="tab-content">
                            <div
                                id="manager"
                                class="tab-pane fade show position-relative active height-400 border-radius-lg"
                                role="tabpanel"
                                aria-labelledby="manager"
                            >
                                <div class="table-responsive">
                                    <table id="manager_datatable" class="table table-flush"></table>
                                </div>
                            </div>
                            <div
                                id="teacher"
                                class="tab-pane fade position-relative height-400 border-radius-lg"
                                role="tabpanel"
                                aria-labelledby="teacher"
                            >
                                <div class="table-responsive">
                                    <table id="teacher_datatable" class="table table-flush"></table>
                                </div>
                            </div>
                            <div
                                id="student"
                                class="tab-pane fade position-relative height-400 border-radius-lg"
                                role="tabpanel"
                                aria-labelledby="student"
                            >
                                <div class="table-responsive">
                                    <table id="student_datatable" class="table table-flush"></table>
                                </div>
                            </div>
                        </div>
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
                                <option value="4">Manager</option>
                                <option value="5">Teacher</option>
                                <option value="6">Student</option>
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
    <div class="modal fade" id="importUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Import User</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-3">Accept only .xlsx file ( <a href="https://s3-hcm-r1.longvan.net/19403879-letlearn/template/user_template.xlsx">template</a> )</label>
                            <input
                                id="user_file"
                                name="user_file"
                                class="form-control"
                                type="file"
                                placeholder="Input file"
                                accept=".xlsx"
                                @change="this.showPreview"
                            />
                        </div>
                    </div>
                    <h6 class="mt-4 mb-0">Preview (read-only)</h6>
                    <div class="table-responsive">
                        <table id="import_preview_datatable" class="table table-flush"></table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" @click="this.importUser">
                        Import
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {DataTable, exportCSV} from "simple-datatables";
import setNavPills from "../../helpers/other/nav-pills";
import readXlsxFile from 'read-excel-file'

export default {
    name: "User",
    title() {
        let school = this.$store.getters['user/userData'].info.school;
        if (school) {
            return "User Management - " + school.name + " | School";
        } else {
            return "User Management | School";
        }
    },
    data() {
        return {
            data: null,
            type: 'get',
            manager_datatable: null,
            teacher_datatable: null,
            student_datatable: null,
            showMenu: false,
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
            user_modal: null,
            importUser_modal: null,
            import_file: null,
            import_preview_datatable: null,
            import_preview_data: null,
            unsubscribe: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'schoolUser/request') {

            } else if (mutation.type === 'schoolUser/success') {
                if (this.type === 'get') {
                    this.data = mutation.payload;
                    this.init();
                }
                else if (this.type === 'add') {
                    this.$root.showSnackbar('Add user successfully', 'success');
                    let user = mutation.payload.user;
                    if (user.role_id === '4') {
                        this.data.manager.push(user);
                        this.manager_datatable.rows.add([
                            user.id,
                            user.name,
                            user.username,
                            user.email,
                            new Date(user.created_at).toLocaleString(),
                            user.status,
                        ]);
                    } else if (user.role_id === '5') {
                        this.data.teacher.push(user);
                        this.teacher_datatable.rows.add([
                            user.id,
                            user.name,
                            user.username,
                            user.email,
                            new Date(user.created_at).toLocaleString(),
                            user.status,
                        ]);
                    } else if (user.role_id === '6') {
                        this.data.student.push(user);
                        this.student_datatable.rows.add([
                            user.id,
                            user.name,
                            user.username,
                            user.email,
                            new Date(user.created_at).toLocaleString(),
                            user.status,
                        ]);
                    }
                    this.user_modal.hide();
                }
                else if (this.type === 'update') {
                    this.$root.showSnackbar('Update user successfully', 'success');
                    location.reload();
                }
                else if (this.type === 'delete') {
                    this.$root.showSnackbar('Delete user successfully', 'success');
                    location.reload();
                }
                else if (this.type === 'add_multiple') {
                    this.$root.showSnackbar('Import user successfully', 'success');
                    location.reload();
                }
            } else if (mutation.type === 'schoolUser/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('schoolUser/show', this.$route.params.slug);
    },
    mounted() {
        setNavPills();
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        init() {
            this.manager_datatable = new DataTable("#manager_datatable", {
                data: {
                    headings: ['ID', 'Name', 'Username', 'Email', 'Created at', 'Status'],
                    data: this.data.manager.map((item) => {
                        return [
                            item.id,
                            item.name,
                            item.username,
                            item.email,
                            new Date(item.created_at).toLocaleString(),
                            item.status,
                        ];
                    }),
                },
            });
            this.manager_datatable.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                if (!isNaN(rowIndex)) {
                    this.selected_row = rowIndex;
                    this.type = 'update';
                    let manager = this.data.manager[rowIndex];
                    this.user = {
                        id: manager.id,
                        role_id: manager.role_id,
                        name: manager.name,
                        username: manager.username,
                        email: manager.email,
                        date_of_birth: manager.date_of_birth,
                        email_verified_at: manager.email_verified_at ? new Date(manager.email_verified_at).toISOString().slice(0, 16) : null,
                        status: manager.status,
                        password: null,
                        password_confirmation: null,
                    };
                    this.user_modal.show();
                }
            });
            this.teacher_datatable = new DataTable("#teacher_datatable", {
                data: {
                    headings: ['ID', 'Name', 'Username', 'Email', 'Created at', 'Status'],
                    data: this.data.teacher.map((item) => {
                        return [
                            item.id,
                            item.name,
                            item.username,
                            item.email,
                            new Date(item.created_at).toLocaleString(),
                            item.status,
                        ];
                    }),
                },
            });
            this.teacher_datatable.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                if (!isNaN(rowIndex)) {
                    this.selected_row = rowIndex;
                    this.type = 'update';
                    let teacher = this.data.teacher[rowIndex];
                    this.user = {
                        id: teacher.id,
                        role_id: teacher.role_id,
                        name: teacher.name,
                        username: teacher.username,
                        email: teacher.email,
                        date_of_birth: teacher.date_of_birth,
                        email_verified_at: teacher.email_verified_at ? new Date(teacher.email_verified_at).toISOString().slice(0, 16) : null,
                        status: teacher.status,
                        password: null,
                        password_confirmation: null,
                    };
                    this.user_modal.show();
                }
            });
            this.student_datatable = new DataTable("#student_datatable", {
                data: {
                    headings: ['ID', 'Name', 'Username', 'Email', 'Created at', 'Status'],
                    data: this.data.student.map((item) => {
                        return [
                            item.id,
                            item.name,
                            item.username,
                            item.email,
                            new Date(item.created_at).toLocaleString(),
                            item.status,
                        ];
                    }),
                },
            });
            this.student_datatable.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                if (!isNaN(rowIndex)) {
                    this.selected_row = rowIndex;
                    this.type = 'update';
                    let student = this.data.student[rowIndex];
                    this.user = {
                        id: student.id,
                        role_id: student.role_id,
                        name: student.name,
                        username: student.username,
                        email: student.email,
                        date_of_birth: student.date_of_birth,
                        email_verified_at: student.email_verified_at ? new Date(student.email_verified_at).toISOString().slice(0, 16) : null,
                        status: student.status,
                        password: null,
                        password_confirmation: null,
                    };
                    this.user_modal.show();
                }
            });
            const bootstrap = this.$store.state.config.bootstrap;
            this.user_modal = new bootstrap.Modal(document.getElementById('userModal'), {
                keyboard: false
            });
            this.user_modal._element.addEventListener('hidden.bs.modal', () => {
                this.selected_row = null;
                this.type = 'get';
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
            this.importUser_modal = new bootstrap.Modal(document.getElementById('importUserModal'), {
                keyboard: false
            });
            this.import_preview_datatable = new DataTable("#import_preview_datatable", {
                data: {
                    headings: ['No.', 'Name', 'Username', 'Email', 'Role', 'Date of birth', 'Password'],
                },
            });
            this.importUser_modal._element.addEventListener('hidden.bs.modal', () => {
                this.import_preview_datatable.data.data = [];
                this.import_preview_datatable.refresh();
                document.getElementById('user_file').value = '';
            });
        },
        export(type) {
            // get current timestamp
            const timestamp = new Date().getTime();
            const data = {
                filename: `${type}-export-${timestamp}`,
            }
            if (type === 'manager') {
                exportCSV(this.manager_datatable, data);
            } else if (type === 'teacher') {
                exportCSV(this.teacher_datatable, data);
            } else if (type === 'student') {
                exportCSV(this.student_datatable, data);
            }
        },
        showFormAddUser() {
            this.type = 'add';
            this.user_modal.show();
        },
        showFormImportUser() {
            this.importUser_modal.show();
        },
        showPreview(event) {
            let file = event.target.files[0];
            // check file is excel or not
            if (file.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' && file.type !== 'application/wps-office.xlsx') {
                this.$root.showSnackbar('Accept only excel file', 'danger');
            } else {
                readXlsxFile(file).then((rows) => {
                    rows.splice(0, 2);
                    this.import_preview_datatable.data.data = [];
                    this.import_preview_data = [];
                    rows.forEach((row, index) => {
                        this.import_preview_data.push(row);
                        this.import_preview_datatable.rows.add(row);
                    });
                });
            }
        },
        importUser() {
            this.type = 'add_multiple';
            let data = {
                school_slug: this.$route.params.slug,
                type: 'multiple',
                users: this.import_preview_data,
            }
            this.$store.dispatch('schoolUser/store', data);
        },
        addUser() {
            this.type = 'add';
            this.user.type = 'one';
            this.user.school_slug = this.$route.params.slug;
            this.$store.dispatch('schoolUser/store', this.user);
        },
        updateUser(){
            this.type = 'update';
            this.user.school_slug = this.$route.params.slug;
            this.$store.dispatch('schoolUser/update', this.user);
        },
        deleteUser() {
            if(confirm('Are you sure you want to delete this user?')){
                this.type = 'delete';
                this.$store.dispatch('schoolUser/destroy', this.user.id);
            }
        },
    }
};
</script>
