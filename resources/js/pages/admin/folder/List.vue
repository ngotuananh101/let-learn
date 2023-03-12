<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Folders</h5>
                                <p class="mb-0 text-sm">List of all Folders in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mt-1 mb-0 btn bg-gradient-success mt-sm-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addModal">
                                        +&nbsp;Add Folder
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="folder_data" :options="{select: 'single'}" ref="folder_table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Username</th>
                                    <th>Public</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="addModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle" class="modal-title">
                        Add new Folder
                    </h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                            <label class="form-label mt-2">Name</label>
                            <argon-input id="name" name="name" class="mb-2"
                                         placeholder="Enter name..." required/>
                        </div>
                        <div class="col-7">
                            <label class="form-label mt-2">Description</label>
                            <argon-input id="description" name="description" class="mb-2"
                                         placeholder="Enter description..." required/>
                        </div>
                        <div class="col-md-3 col-5">
                            <label class="form-label mt-2">Status</label>
                            <argon-switch id="status" name="status" class="mb-3" checked>
                                Active
                            </argon-switch>
                        </div>
                        <div class="col-md-3 col-5">
                            <label class="form-label mt-2">Public ?</label>
                            <argon-switch id="public" name="public" class="mb-3" @change="publicChange">
                                Public
                            </argon-switch>
                        </div>
                        <div v-if="password_option" class="col-md-6 col-12">
                            <label class="form-label mt-2">Password (option)</label>
                            <argon-input id="password" type="password" name="password"
                                         placeholder="Enter password for this set"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm"
                            data-bs-dismiss="modal" id="close_model">
                        Close
                    </button>
                    <button type="button" class="btn bg-gradient-success btn-sm" id="btn-submit" @click="submit">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="optionModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-title" class="modal-title">
                        Option
                    </h5>
                    <i class="fa-solid fa-option ms-3"></i>
                </div>
                <div class="modal-body">
                    <p>You can select option for this Folder</p>
                    <div class="row">
                        <div class="col-4">
                            <button type="button" class="mx-1 mb-0 btn btn-outline-primary btn-sm w-100">
                                View
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="mx-1 mb-0 btn btn-outline-warning btn-sm w-100" @click="this.edit">
                                Edit
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="mx-1 mb-0 btn btn-outline-danger btn-sm w-100" @click="this.delete">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-primary btn-sm" id="edit-close"
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
import {mapActions} from "vuex";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import ArgonTextarea from "@/components/Argons/ArgonTextarea.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import {Modal} from "bootstrap";

DataTable.use(DataTablesLib);

export default {
    name: "Folder List",
    components: {
        ArgonButton,
        ArgonSwitch,
        ArgonTextarea,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List Course' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            table: null,
            folder: [],
            password_option: false,
            type: 'add',
            selected_id: null
        };
    },
    beforeMount() {
        this.index().then((response) => {
            this.folders = response;
            this.loadTable();
        });
    },
    mounted() {
        this.table = this.$refs.folder_table.dt();
        this.handleClickRow();
    },
    methods: {
        ...mapActions({
            index: 'adminFolder/index',
        }),
        handleClickRow() {
            document.getElementById('folder_data').addEventListener('click', () => {
                // get id
                this.selected_id = this.table.rows({selected: true}).data().toArray()[0][0];
                // show modal
                let myModal = new Modal(document.getElementById('optionModal'));
                myModal.show();
                document.getElementById('optionModal').addEventListener('hidden.bs.modal', () => {
                    this.table.rows().deselect();
                });
            });
        },
        loadTable() {
            this.table.clear();
            this.table.rows.add(this.folders).draw();
        },
        publicChange() {
            this.password_option = !this.password_option;
        },
        submit() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let status = document.getElementById('status').checked ? 'active' : 'inactive';
            let public_folder = document.getElementById('public').checked ? 1 : 0;
            let password = public_folder ? document.getElementById('password').value : null;
            if (name === '' || description === '' || status === '' || public_folder === '') {
                alert('Please fill all fields');
            } else {
                this.$store.dispatch('adminFolder/add', {
                    name: name,
                    description: description,
                    status: status,
                    is_public: public_folder,
                    password: password
                }).then((response) => {
                    // add new row
                    this.table.row.add(response).draw();
                    // close modal
                    document.getElementById('close_model').click();
                });
            }
        },
        edit(){
            document.getElementById('edit-close').click();
            this.$router.push({name: 'admin.folder.edit', params: {id: this.selected_id}});
        },
        delete(){
            this.$swal({
                icon: "question",
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = this.selected_id;
                    this.$store.dispatch('adminFolder/delete', id);
                    // remove selected row
                    this.table.rows({selected: true}).remove().draw();
                    // close modal
                    document.getElementById('edit-close').click();
                }
            });
        }
    },
}
</script>
