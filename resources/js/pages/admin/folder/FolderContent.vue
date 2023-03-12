<template>
    <div id="info" class="card mt-4">
        <div class="card-header">
            <div class="d-lg-flex">
                <div>
                    <h5 class="mb-0">All Course</h5>
                    <p class="mb-0 text-sm">List of all Course in the system</p>
                </div>
                <div class="my-auto mt-4 ms-auto mt-lg-0">
                    <div class="my-auto ms-auto">
                        <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                @click="updateFolderInfo">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-5">
                    <label class="form-label mt-2">Course name</label>
                    <argon-input id="name" type="text" name="name" placeholder="Folder name" :value="folder.name"
                                 @change="change('name')"/>
                </div>
                <div class="col-7">
                    <label class="form-label mt-2">Course description</label>
                    <argon-input id="description" type="text" name="description" placeholder="Folder description"
                                 :value="folder.description" @change="change('description')"/>
                </div>
                <div class="col-4">
                    <label class="form-label mt-2">Status</label>
                    <argon-switch id="status" name="status" class="mb-3" :checked="folder.status === 'active'"
                                  @change="change('status')">
                        Active
                    </argon-switch>
                </div>
                <div class="col-2">
                    <label class="form-label mt-2">Public</label>
                    <argon-switch id="is_public" name="is_public" class="mb-3" @click="publicChange"
                                  :checked="folder.is_public === true" @change="change('is_public')">
                        Public
                    </argon-switch>
                </div>
                <div v-if="password_option" class="col-6">
                    <label class="form-label mt-2">Password (option)</label>
                    <argon-input id="password" type="password" name="password"
                                 placeholder="Enter password for this folder" :value="folder.password"
                                 @change="change('password')"/>
                </div>
            </div>
        </div>
    </div>
    <div id="set" class="card mt-4">
        <div class="card-header pb-0">
            <div class="d-lg-flex">
                <div>
                    <h5>Lesson in {{ this.folder.name }}</h5>
                    <p class="mb-0 text-sm">List of all lessons in this course</p>
                </div>
                <div class="my-auto mt-4 ms-auto mt-lg-0">
                    <div class="my-auto ms-auto">
                        <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#addSetModal">
                            Add lesson
                        </button>
                        <button type="button" class="mx-1 mb-0 btn btn-outline-danger btn-sm" @click="removeSet">
                            Remove lesson
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 pb-0 pt-0 card-body">
            <div class="table-responsive">
                <DataTable id="setdata" :options="{select: true}" ref="table"
                           class="table table-flush mx-3">
                    <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Username</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </DataTable>
            </div>
        </div>
    </div>
    <div id="addSetModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Find Lesion
                    </h5>
                    <i class="fa-solid fa-magnifying-glass ms-3"></i>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label mt-2">Find Lesion in system</label>
                            <select
                                id="find_set"
                                class="form-control"
                                name="find_set"
                            >
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-success btn-sm"
                            data-bs-dismiss="modal" id="addSet" @click="addSet">
                        Add
                    </button>
                    <button type="button" class="btn bg-gradient-secondary btn-sm"
                            data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'datatables.net-select';
import {mapActions} from "vuex";
import Choices from 'choices.js';
DataTable.use(DataTablesLib);

export default {
    name: "FolderContent",
    components: {
        ArgonInput,
        ArgonSwitch,
        DataTable,
    },
    data() {
        return {
            folder: {},
            sets: [],
            password_option: false,
            table: null,
            find_set: null,
        }
    },
    beforeMount() {

    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.getFolderById(this.$route.params.id).then((response) => {
            this.folder = response.folder;
            if (this.folder.is_public) this.password_option = false;
            this.sets = response.sets;
        }).then(() => {
            this.loadTable();
            this.setSelect();
        });
    },
    methods: {
        ...mapActions({
            getFolderById: "adminFolder/getFolderById",
            updateFolder: "adminFolder/updateFolder",
            findSet: "adminFolder/findSetByName",
            addSetToFolder: "adminFolder/addSetToFolder",
            removeSetFromFolder: "adminFolder/removeSetFromFolder",
        }),
        loadTable() {
            this.table.clear();
            this.table.rows.add(this.sets).draw();
        },
        publicChange() {
            this.password_option = !this.password_option;
        },
        change(name) {
            if (name === 'status') {
                this.folder.status = this.folder.status === 'active' ? 'inactive' : 'active';
            } else if (name === 'is_public') {
                this.folder.is_public = this.folder.is_public !== true;
            } else {
                this.folder[name] = document.getElementById(name).value;
            }
        },
        updateFolderInfo() {
            this.updateFolder(this.folder);
        },
        setSelect() {
            this.find_set = new Choices('#find_set', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                removeItemButton: true,
                allowHTML: true,
            });
            this.find_set.passedElement.element.addEventListener('choice', (event) => {
                this.selected_id = event.detail.choice.value;
            });

            this.find_set.passedElement.element.addEventListener('search', (event) => {
                // wait user stop typing
                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    let value = event.detail.value;
                    let data = {
                        folder_id: this.$route.params.id,
                        search: value,
                    };
                    this.findSet(data).then((response) => {
                        this.find_set.clearStore();
                        // add new options
                        this.find_set.setChoices(response, 'value', 'label', true);
                    });
                }, 500);
            });
        },
        addSet() {
            if (this.selected_id === null) alert('Please select a set');
            else {
                let data = {
                    folder_id: this.$route.params.id,
                    set_id: this.selected_id,
                };
                this.addSetToFolder(data).then((response) => {
                    this.table.rows.add(response).draw();
                });
            }
        },
        removeSet() {
            let selected = this.table.rows({selected: true}).data();
            if (selected.length === 0) alert('Please select a set');
            else {
                // get selected id
                let ids = [];
                for (let i = 0; i < selected.length; i++) {
                    ids.push(selected[i][0]);
                }
                // remove lesson from folder
                let data = {
                    folder_id: this.$route.params.id,
                    set_ids: ids,
                };
                this.removeSetFromFolder(data);
                // remove from table
                this.table.rows({selected: true}).remove().draw();
            }
        },
    },
}
</script>

