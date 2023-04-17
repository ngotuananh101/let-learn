<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Add Test</h5>
                        <p class="mb-0 text-sm">Add a new test</p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Name</label>
                                <input id="name" name="name" class="form-control" type="text"
                                    placeholder="Enter a name. Example:'MAE Chapter 1'" v-model="this.test.name" />
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-3">Description</label>
                                <input id="description" name="description" class="form-control" type="text"
                                    placeholder="Add a description..." v-model="this.test.description" />
                            </div>
                            <div class="row mt-2 col-">
                                <label class="form-label mt-3">Status</label>
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" id="pending" value="pending"
                                        v-model="test.status">
                                    <label for="pending">Pending</label>
                                </div>
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" id="inactice" value="inactice"
                                        v-model="test.status">
                                    <label for="inactice">Inactice</label>
                                </div>
                            </div>
                            <div class="form-check col-12 mx-3 mt-2">
                                <label class="form-check-label">Scoce reporting</label>
                                <input class="form-check-input" type="checkbox" value="" id="score_reporting"
                                    v-model="test.score_reporting">
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Start time</label>
                                <input id="start_time" name="start_time" class="form-control" type="text"
                                    placeholder="yyyy-mm-dd 00:00:00" v-model="this.test.start_time" />
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">End time</label>
                                <input id="end_time" name="end_time" class="form-control" type="text"
                                    placeholder="yyyy-mm-dd 00:00:00" v-model="this.test.end_time" />
                            </div>
                        </div>
                        <div class="col-md-2 col-6">
                            <button class="btn btn-primary w-100 mt-4" @click="import_test_modal.show">
                                Import test
                            </button>
                        </div>
                        <div class="col-12">
                            <div v-for="(child, index) in cards">
                                <component :is="child" :data="questions[index]" @remove="removeCard"></component>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-4 col-6">
                                <button class="btn btn-lg btn-primary mt-3 w-100" @click="addCard">
                                    Add Card
                                </button>
                            </div>
                            <div class="col-md-4 col-6">
                                <button class="btn btn-lg btn-success mt-3 w-100" @click="addTest">
                                    Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">
                        Import test
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-check form-switch ps-0 mb-3">
                        <input id="importFile" class="form-check-input ms-0" type="checkbox" name="importFile"
                            v-model="importFile" />
                        <label class="form-check-label" for="rememberMe">
                            Import from file
                        </label>
                    </div>
                    <div v-if="this.importFile">
                        <p>Import test from Excel (.xlsx) file ( <a
                                href="https://s3-hcm-r1.longvan.net/19403879-letlearn/template/lesson_template.xlsx"
                                target="_blank">Template</a> )</p>
                        <input type="file" placeholder="Browse file..." class="mb-3 form-control" accept=".csv, .xlsx"
                            name="file" @change="changeFile" />
                    </div>
                    <div v-else>
                        <p>Import test from plant text</p>
                        <textarea class="form-control" rows="10" id="raw_data" placeholder="Enter text here..."></textarea>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-2">Between question and correct answer</label>
                                <input type="text" class="mb-3 form-control" id="betweenQuestionCorrectAnswer"
                                    placeholder="Between question and correct answer" value="---">
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-2">Between row</label>
                                <input type="text" class="mb-3 form-control" id="betweenCard" placeholder="Between row"
                                    value="\n\n\n">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm" data-bs-dismiss="modal" id="closeImport">
                        Close
                    </button>
                    <button type="button" class="btn bg-gradient-success btn-sm" @click="this.import">
                        Import
                    </button>
                </div>
                <h1>{{ answer_option }}</h1>
            </div>
        </div>
    </div>
</template>
<script>
import TestDetailCard from "../../../components/cards/test-detail-card.vue";
import { markRaw } from "vue";
import answer_option from "../../../components/cards/test-detail-card.vue";

export default {
    name: "Add",
    components: {
        TestDetailCard
    },
    title() {
        return "Add Test";
    },
    data() {
        return {
            test: {
                name: '',
                description: '',
                status: '',
                score_reporting: false,
                start_time: '',
                end_time: '',
            },
            import_test_modal: null,
            importFile: true,
            file: null,
            type: 'get',
            questions: { answer_option: '' },
            cards: null,
            id: this.$route.params.id,
            is_multiple_choice: '1',
            user: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'learn/request') {
                if (this.type === 'import') {
                    this.$root.showSnackbar('Importing test...', 'info');
                } else
                    if (this.type === 'addTest') {
                        this.$root.showSnackbar('Adding test...', 'info');
                    }
            } else if (mutation.type === 'learn/requestSuccess') {
                if (this.type === 'import') {
                    this.$root.showSnackbar('Import test successfully', 'success');
                    this.questions = mutation.payload;
                    this.cards = markRaw(this.questions.map((detail, index) => {
                        return TestDetailCard;
                    }));
                    this.import_test_modal.hide();
                } else
                    if (this.type === 'addTest') {
                        this.$root.showSnackbar('Add test successfully', 'success');
                    }
            } else if (mutation.type === 'learn/requestFailure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
    },
    mounted() {
        this.init();
    },
    beforeUnmount() {
        this.unsubscribe();
        this.import_test_modal.hide();
        this.import_test_modal.dispose();
    },
    methods: {
        init() {
            const bootstrap = this.$store.state.config.bootstrap;
            this.import_test_modal = new bootstrap.Modal(document.getElementById('importModal'), {
                show: true,
            });
            this.import_test_modal._element.addEventListener('hidden.bs.modal', () => {
                if (this.importFile) {
                    this.file = null;

                } else {
                    document.getElementById('raw_data').value = '';
                }
            });
            // init 3 cards
            this.questions = [
                {
                    index: 0,
                    question: '',
                    correct_answer: '',
                },
                {
                    index: 1,
                    question: '',
                    correct_answer: '',
                },
                {
                    index: 2,
                    question: '',
                    correct_answer: '',
                },
            ];
            this.cards = markRaw(this.questions.map((detail, index) => {
                return TestDetailCard;
            }));
        },
        removeCard(data) {
            // remove selected card
            this.questions = this.questions.filter((detail) => {
                return detail.index !== data.data.index;
            });
            this.cards = markRaw(this.questions.map((detail, index) => {
                return TestDetailCard;
            }));
        },
        addCard() {
            // add new card
            this.questions.push({
                index: this.questions.length,
                question: '',
                correct_answer: '',
            });
            this.cards = markRaw(this.questions.map((detail, index) => {
                return TestDetailCard;
            }));
        },
        changeFile(e) {
            this.file = e.target.files[0];
        },
        import() {
            this.type = 'import';
            if (this.importFile) {
                if (this.file === null) {
                    this.$root.showSnackbar('Please select a file', 'danger');
                    return;
                }
                const formData = new FormData();
                formData.append('file', this.file);
                this.$store.dispatch('test/importFile', formData);
            } else {
                this.$root.showSnackbar('Importing test...', 'info');
                try {
                    let raw_data = document.getElementById('raw_data').value;
                    const question_separator = document.getElementById('betweenQuestionCorrectAnswer').value;
                    let detail_separator = document.getElementById('betweenCard').value;
                    // check detail_separator is regex
                    if (detail_separator.startsWith('/') && detail_separator.endsWith('/')) {
                        detail_separator = detail_separator.substring(1, detail_separator.length - 1);
                    }
                    let test_detail = raw_data.split(new RegExp(detail_separator, 'g'));
                    this.questions = [];
                    test_detail.forEach((detail, index) => {
                        let question_correct_answer = detail.split(question_separator);
                        if (question_correct_answer.length !== 2) {
                            throw new Error('Invalid format');
                        }
                        this.questions.push({
                            index: index,
                            question: question_correct_answer[0],
                            correct_answer: '',
                        });
                    });
                    this.cards = markRaw(this.questions.map((detail, index) => {
                        return TestDetailCard;
                    }));
                    this.import_test_modal.hide();
                    this.$root.showSnackbar('Import test successfully', 'success');
                } catch (e) {
                    this.$root.showSnackbar('Import test failed', 'danger');
                }
            }
        },
        addTest() {
            this.type = 'addTest';
            this.$store.dispatch('learn/addTest', {
                class_id: this.id,
                name: this.test.name,
                description: this.test.description,
                questions: this.questions,
                status: this.test.status,
                score_reporting: this.score_reporting,
                start_time: this.start_time,
                end_time: this.end_time,
                roleName: this.user.roleName,
            });
            // this.$router.push({ name: 'home.class', params: { id: this.id } });
        }
    }
};
</script>
