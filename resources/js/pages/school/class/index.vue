<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Class</h5>
                                <p class="mb-0 text-sm">List of all classes</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">
                                        Add Class
                                        <i class="fa-regular fa-user-plus ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="classes_datatable" class="table table-flush"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="classModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Class management</h1>
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
                                v-model="class_info.name"
                            />
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label mt-3">Start date</label>
                            <input
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                type="date"
                                placeholder="Start date"
                                v-model="class_info.start_date"
                            />
                        </div>
                        <div class="col-md-3 col-12">
                            <label class="form-label mt-3">End date</label>
                            <input
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                type="date"
                                placeholder="=End date"
                                v-model="class_info.end_date"
                            />
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label mt-3">Member</label>
                            <div class="table-responsive">
                                <table id="class_member_datatable" class="table table-flush"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning"
                            @click="this.updateUser">
                        Update
                    </button>
                    <button type="button" class="btn btn-danger" @click="this.deleteUser">
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
    name: "index",
    title() {
        return "Class | List"
    },
    data() {
        return {
            unsubscribe: null,
            type: 'get',
            classes_datatable: null,
            class_member_datatable: null,
            classes: null,
            class_modal: null,
            selected_class_id: null,
            class_info: {}
        }
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            switch (mutation.type) {
                case 'schoolClass/request':
                    break;
                case 'schoolClass/success':
                    if (this.type === 'get'){
                        this.classes = mutation.payload.classes;
                        this.init();
                    }
                    if (this.type === 'show'){
                        this.class_member_datatable.data.data = [];
                        this.class_info = mutation.payload.class;
                        this.class_info.member ? this.class_info.member.map((member) => {
                            this.class_member_datatable.rows.add([member.id, member.name, member.email, member.role_id === 5 ? 'Teacher' : 'Student']);
                        }) : null;
                    }
                    break;
                case 'schoolClass/failure':
                    this.$root.showSnackbar(mutation.payload, 'danger');
                    break;
            }
        });
        this.$store.dispatch('schoolClass/index', this.$route.params.slug);
    },
    beforeUnmount() {
        this.unsubscribe();
        this.classes_datatable ? this.classes_datatable.destroy() : null;
    },
    methods: {
        init() {
            this.classes_datatable = new DataTable('#classes_datatable', {
                data: {
                    headings: ['ID', 'Name', 'Status', 'Start Date', 'End Date'],
                    data: this.classes.map((cl) => {
                        return [
                            cl.id,
                            cl.name,
                            cl.status,
                            cl.start_date,
                            cl.end_date,
                        ]
                    }),
                },
            });
            this.classes_datatable.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                if (isNaN(rowIndex)) return;
                this.selected_class_id = this.classes[rowIndex].id;
                this.$router.push({name: 'school.class.edit', params: {id: this.selected_class_id}});
            });
            const bootstrap = this.$store.state.config.bootstrap;
            this.class_modal = new bootstrap.Modal(document.getElementById('classModal'), {
                keyboard: false
            });
            this.class_modal._element.addEventListener('hidden.bs.modal', () => {

            });
        }
    }
}
</script>

<style scoped>
.modal{
    z-index: 9999 !important;
}
</style>
