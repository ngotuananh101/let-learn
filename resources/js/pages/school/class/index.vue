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
                        <table id="class_datatable" class="table table-flush"></table>
                    </div>
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
            class_datatable: null,
            classes: null
        }
    },
    mounted() {
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            switch (mutation.type) {
                case 'schoolClass/request':
                    break;
                case 'schoolClass/success':
                    this.classes = mutation.payload.classes;
                    this.init();
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
        this.class_datatable ? this.class_datatable.destroy() : null;
    },
    methods: {
        init() {
            this.class_datatable = new DataTable('#class_datatable', {
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
        }
    }
}
</script>

<style scoped>

</style>
