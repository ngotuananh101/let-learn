<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="pb-0 card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Java - Lesson 1</h5>
                                <p class="mb-0 text-sm">List all result of test</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button class="mb-0 btn bg-gradient-success btn-sm">&nbsp; Public for student</button>
                                    <button type="button" class="mx-1 mb-0 btn btn-outline-success btn-sm">Export to exel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-0 pb-0 pt-0 card-body">
                        <div class="table-responsive">
                            <DataTable id="setdata" :options="{select: 'single'}" ref="table" class="table table-flush mx-3">
                                <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Score</th>
                                    <th>Time to finish</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(lesson, index) in lessons" :key="index">
                                    <td>{{ lesson.id }}</td>
                                    <td>{{ lesson.name }}</td>
                                    <td>{{ lesson.score }}</td>
                                    <td>{{ lesson.time }}</td>
                                    <td>{{ lesson.status }}</td>
                                </tr>
                                </tbody>
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

DataTable.use(DataTablesLib);

export default {
    name: "List",
    components: {
        DataTable,
    },
    data() {
        return {
            table: null,
            lessons: [
                { id: 1, name: "Vu Hai Long", score: "80", time: "15:34", status: "submitted" },
                { id: 2, name: "Ngo Tuan Anh", score: "89", time: "15:38", status: "unsubmitted" },
                { id: 3, name: "Dao Xuan Dat", score: "87", time: "15:34", status: "submitted" },
                { id: 4, name: "Nguyen Quang Tuan", score: "45", time: "35:34", status: "unsubmitted" },
                { id: 5, name: "Tran Manh Dat", score: "25", time: "15:34", status: "submitted" },
                { id: 6, name: "Pham Thi An", score: "95", time: "16:22", status: "unsubmitted" },
                { id: 7, name: "Nguyen Van A", score: "70", time: "14:56", status: "submitted" },
                { id: 8, name: "Le Thi B", score: "82", time: "16:08", status: "unsubmitted" },
                { id: 9, name: "Truong Duc C", score: "68", time: "15:12", status: "submitted" },
                { id: 10, name: "Doan Minh D", score: "93", time: "17:04", status: "submitted" },
                { id: 11, name: "Le Tran Gia Huy", score: "75", time: "15:50", status: "unsubmitted" },
                { id: 12, name: "Phan Thi Kim Ngan", score: "78", time: "16:34", status: "submitted" }
            ],
        };
    },
    mounted() {
        this.table = this.$refs.table.dt();
        this.modal_option = new Modal(document.getElementById('optionModal'));
        this.modal_import = new Modal(document.getElementById('importModal'));

        // load data to table
        let delay = setInterval(() => {
            if (this.table) {
                this.table.clear();
                this.table.rows.add(this.lessons);
                this.table.draw();
                clearInterval(delay);
            }
        }, 100);
    },
};
</script>
