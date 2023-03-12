<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">All Sets</h5>
                                <p class="mb-0 text-sm">List of all Sets in the system</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <router-link :to="{ name: 'admin.set.add' }"
                                                 class="mb-0 btn bg-gradient-success btn-sm">+&nbsp;
                                        New Set
                                    </router-link>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#importModal">
                                        Import
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="setdata" :options="{select: 'single'}" ref="table"
                                       class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Username</th>
                                    <th>Is Public</th>
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
                    <p>You can select option for this Set</p>
                    <div class="row">
                        <div class="col-3">
                            <button type="button" class="mx-1 mb-0 btn bg-gradient-primary btn-sm">
                                View
                            </button>
                        </div>
                        <div class="col-3">
                            <button type="button" class="mx-1 mb-0 btn bg-gradient-warning btn-sm" @click="this.edit">
                                Update
                            </button>
                        </div>
                        <div class="col-3">
                            <button type="button" class="mx-1 mb-0 btn bg-gradient-danger btn-sm" @click="this.delete">
                                Delete
                            </button>
                        </div>
                        <div class="col-3">
                            <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm" @click="this.export">
                                Export
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
    <div id="importModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Import Set
                    </h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <form @submit.prevent="handleImport">
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
                        </div>
                        <argon-switch id="importText" name="switchImport" class="mb-3" @change="this.switchImport">
                            Import from text
                        </argon-switch>
                        <div v-if="this.importType === 'file'">
                            <p>Import set from csv, xls, xlsx file</p>
                            <input type="file" placeholder="Browse file..." class="mb-3 form-control"
                                   accept=".csv, .xls .xlsx" name="file"/>
                        </div>
                        <div v-if="this.importType === 'text'">
                            <p>Import set from text</p>
                            <argon-textarea id="raw_data" name="raw_data" class="mb-2" rows="10"
                                            placeholder="Paste text here..."/>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label mt-2">Between Term and Definition</label>
                                    <argon-input id="betweenTermDefinition" name="term_separator" class="mb-2"
                                                 placeholder="Enter text here..." value="-----"/>
                                </div>
                                <div class="col-6">
                                    <label class="form-label mt-2">Between Card</label>
                                    <argon-input id="betweenTermDefinition" name="detail_separator" class="mb-2"
                                                 placeholder="Enter text here..." value="~~~~~"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary btn-sm"
                                data-bs-dismiss="modal" id="closeImport">
                            Close
                        </button>
                        <button type="submit" class="btn bg-gradient-success btn-sm">
                            Import
                        </button>
                    </div>
                </form>
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
    name: "List",
    components: {
        ArgonButton,
        ArgonSwitch,
        ArgonTextarea,
        ArgonInput,
        DataTable
    },
    title() {
        return 'List Lesson' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            table: null,
            sets: [],
            importType: 'file',
            selected_id: null

        };
    },
    beforeMount() {
        this.index();
        this.sets = this.$store.state.adminSet.sets;
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.handleClickRow();
        // load data to table
        this.loadTable();
    },
    methods: {
        ...mapActions({
            index: 'adminSet/index',
        }),
        handleClickRow() {
            document.getElementById('setdata').addEventListener('click', () => {
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
            this.table.rows.add(this.sets).draw();
        },
        switchImport() {
            if (this.importType === 'file') {
                this.importType = 'text';
            } else {
                this.importType = 'file';
            }
        },
        handleImport(e) {
            let name = e.target.name.value;
            let description = e.target.description.value;
            if (this.importType === 'file') {
                let file = e.target.file.files[0];
                let formData = new FormData();
                formData.append('type', this.importType);
                formData.append('file', file);
                formData.append('name', name);
                formData.append('description', description);
                this.$store.dispatch('adminSet/importSet', formData).then(() => {
                    this.$store.dispatch('adminSet/index').then(() => {
                        this.sets = this.$store.state.adminSet.sets;
                        this.setTable();
                    }).then(() => {
                        document.getElementById('closeImport').click();
                    });
                });
            } else if (this.importType === 'text') {
                let raw_data = e.target.raw_data.value;
                let term_separator = e.target.term_separator.value;
                let detail_separator = e.target.detail_separator.value;
                let formData = new FormData();
                formData.append('type', this.importType);
                formData.append('raw_data', raw_data);
                formData.append('term_separator', term_separator);
                formData.append('detail_separator', detail_separator);
                formData.append('name', name);
                formData.append('description', description);
                this.$store.dispatch('adminSet/importSet', formData).then(() => {
                    this.$store.dispatch('adminSet/index').then(() => {
                        this.sets = this.$store.state.adminSet.sets;
                        this.setTable();
                    }).then(() => {
                        document.getElementById('closeImport').click();
                    });
                });
            } else {
                alert('Something went wrong');
            }
        },
        edit() {
            document.getElementById('edit-close').click();
            this.$router.push({name: 'admin.set.edit', params: {id: this.selected_id}});
        },
        delete() {
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
                    this.$store.dispatch('adminSet/deleteSet', id).then(() => {
                        document.getElementById('edit-close').click();
                        this.$store.dispatch('adminSet/index').then(() => {
                            this.sets = this.$store.state.adminSet.sets;
                        });
                    });
                    // remove selected row
                    this.table.rows({selected: true}).remove().draw();
                    // close modal
                    document.getElementById('edit-close').click();
                }
            });
        },
        export(){
            this.$store.dispatch('adminSet/exportSet', this.selected_id);
        },
    },
}
</script>
