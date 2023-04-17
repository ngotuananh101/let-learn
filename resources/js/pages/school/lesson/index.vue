<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Lesson</h5>
                                <p class="mb-0 text-sm">List of lessons</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm"
                                            @click="()=>{
                                            this.$router.push({name: 'school.lesson.add'})
                                        }"
                                    >
                                        Add Lesson
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
                        <table id="lesson_datatable" class="table table-flush">
                        </table>
                    </div>
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
        return "Lesson Management";
    },
    data() {
        return {
            lessons: null,
            table: null,
            unsubscribe: null,
            type: 'get',
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'schoolLesson/request') {
            } else if (mutation.type === 'schoolLesson/success') {
                if (this.type === 'get') {
                    this.lessons = mutation.payload.lessons;
                    console.log(this.lessons);
                    this.init();
                }
            } else if (mutation.type === 'schoolLesson/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('schoolLesson/index', this.$route.params.slug);
    },
    beforeUnmount() {
        this.unsubscribe();
        this.table ? this.table.destroy() : null;
    },
    methods: {
        exportCSV,
        init() {
            this.table = new DataTable("#lesson_datatable", {
                data: {
                    headings: ['ID', 'Name', 'Description', 'Class', 'Updated At', 'Status'],
                    data: this.lessons.map((lesson) => {
                        return [
                            lesson.id,
                            lesson.name,
                            lesson.description,
                            lesson.class_id ? lesson.class.name : 'N/A',
                            new Date(lesson.updated_at).toLocaleString(),
                            lesson.status,
                        ];
                    }),
                },
            });
            this.table.on('datatable.selectrow', (row_index) => {
                this.$router.push({name: 'school.lesson.edit', params: {id: this.lessons[row_index].id}});
            });
        }
    }
};
</script>
