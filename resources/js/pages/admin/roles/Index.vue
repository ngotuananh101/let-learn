<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-3">
                <div class="card position-sticky top-1 mt-4">
                    <button v-if="checkPermission('admin.roles.create')" type="button"
                            class="mx-3 mb-0 mt-3 btn btn-outline-success btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#userModal">
                        + New Role
                    </button>
                    <hr class="mb-1">
                    <ul class="nav flex-column bg-white border-radius-lg p-2">
                        <li class="nav-item d-flex justify-content-between align-items-center px-3 py-1">
                            <a class="text-body p-0" href="#">
                                <span class="fs-5">Basic Info</span>
                                <p class="fs-6 m-0">jskdgfks</p>
                            </a>
                            <i class="fa-regular fa-gear fs-5"></i>
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
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Dob</th>
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
    methods: {
        getRoute() {
            return this.$route.name.split('#')[1];
        },
        ...mapGetters({
            permissions: 'account/permissions',
        }),
        checkPermission(name) {
            return this.permissions().some(permission => permission.name === name);
        },
    },
};
</script>

<style scoped>
.nav .nav-item:hover {
    background-color: #f3f3f3;
    border-radius: 0.5rem;
}

.nav .nav-item.active {
    background-color: #344767;
    border-radius: 0.5rem;
}
</style>
