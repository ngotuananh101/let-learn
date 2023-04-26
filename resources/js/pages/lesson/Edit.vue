<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Edit Lesson</h5>
                        <p class="mb-0 text-sm">Edit lesson according to the wishes of the user</p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Name</label>
                                <input id="name" name="name" class="form-control" type="text"
                                    placeholder="Enter a name. Example:'MAE Chapter 1'" v-model="this.lesson.name" />
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label mt-3">Description</label>
                                <input id="description" name="description" class="form-control" type="text"
                                    placeholder="Add a description..." v-model="this.lesson.description" />
                            </div>
                            <div class="col-md-3 col-12">
                                <label class="form-label mt-3">Password</label>
                                <input id="password" name="password" class="form-control" type="text" placeholder="Password"
                                    v-model="this.lesson.password" />
                            </div>
                            <div class="col-md-2 col-6">
                                <button class="btn btn-primary w-100 mt-3" @click="import_lesson_modal.show">
                                    Import Lesson
                                </button>
                            </div>
                            <div class="col-12">
                                <div v-for="(child, index) in cards">
                                    <component :is="child" :data="details[index]" @remove="removeCard"></component>
                                </div>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-lg btn-primary mt-3 w-100" @click="addCard">
                                    Add Card
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-lg btn-success mt-3 w-100" @click="updateLesson">
                                    Save
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
                        Import Lesson
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
                        <p>Import lesson from Excel (.xlsx) file ( <a
                                href="https://s3-hcm-r1.longvan.net/19403879-letlearn/template/lesson_template.xlsx"
                                target="_blank">Template</a> )</p>
                        <input type="file" placeholder="Browse file..." class="mb-3 form-control" accept=".csv, .xlsx"
                            name="file" @change="changeFile" />
                    </div>
                    <div v-else>
                        <p>Import lesson from plant text</p>
                        <textarea class="form-control" rows="10" id="raw_data" placeholder="Enter text here..."></textarea>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label mt-2">Between term and definition</label>
                                <input type="text" class="mb-3 form-control" id="betweenTermDefinition"
                                    placeholder="Between term and definition" value="---">
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
            </div>
        </div>
    </div>
</template>
<script>
import LessonDetailCard from "../../components/cards/lesson-detail-card.vue";
import { markRaw } from "vue";

export default {
    name: "EditLesson",
    components: {
        LessonDetailCard
    },
    title() {
        return "Edit Lesson";
    },
    data() {
        return {
            id: this.$route.params.id,
            unsubscribe: null,
            lesson: {
                name: null,
                description: null,
                password: null,
            },
            import_lesson_modal: null,
            importFile: true,
            file: null,
            type: 'get',
            details: [],
            cards: null,
            removed_ids: [],
            user: null,
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'lesson/request') {
                if (this.type === 'get') {
                    this.$root.showSnackbar('Loading lesson info...', 'info');
                } else if (this.type === 'import') {
                    this.$root.showSnackbar('Importing lesson...', 'info');
                } else if (this.type === 'update') {
                    this.$root.showSnackbar('Updating lesson...', 'info');
                }
            } else if (mutation.type === 'lesson/success') {
                if (this.type === 'get' && this.id) {
                    this.$root.showSnackbar('Load lesson info successfully', 'success');
                    let data = mutation.payload.lesson;
                    this.init(data);
                } else if (this.type === 'import') {
                    this.$root.showSnackbar('Import lesson successfully', 'success');
                    this.details = mutation.payload;
                    this.cards = markRaw(this.details.map((detail, index) => {
                        return LessonDetailCard;
                    }));
                    this.import_lesson_modal.hide();
                } else if (this.type === 'update') {
                    this.$root.showSnackbar('Update lesson successfully', 'success');
                    this.$router.push({ name: 'lesson.index' });
                }
            } else if (mutation.type === 'lesson/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('lesson/getLessonById', { id: this.id, roleName: this.user.role.name });
    },
    beforeUnmount() {
        this.unsubscribe();
        this.import_lesson_modal.hide();
        this.import_lesson_modal.dispose();
    },
    methods: {
        init(data) {
            const bootstrap = this.$store.state.config.bootstrap;
            this.import_lesson_modal = new bootstrap.Modal(document.getElementById('importModal'), {
                show: true,
            });
            this.import_lesson_modal._element.addEventListener('hidden.bs.modal', () => {
                if (this.importFile) {
                    this.file = null;
                } else {
                    document.getElementById('raw_data').value = '';
                }
            });
            this.lesson = {
                name: data.name,
                description: data.description,
                password: '',
            };
            this.details = data.details.map((detail, index) => {
                return {
                    index: index,
                    id: detail.id,
                    term: detail.term,
                    definition: detail.definition,
                };
            });
            this.cards = markRaw(this.details.map((detail, index) => {
                return LessonDetailCard;
            }));
        },
        removeCard(data) {
            // remove selected card
            this.details = this.details.filter((detail) => {
                return detail.index !== data.data.index;
            });
            if (data.data.id) {
                this.removed_ids.push(data.data.id);
            }
            this.cards = markRaw(this.details.map((detail, index) => {
                return LessonDetailCard;
            }));
        },
        addCard() {
            // add new card
            this.details.push({
                index: this.details.length,
                id: 0,
                term: '',
                definition: '',
            });
            this.cards = markRaw(this.details.map((detail, index) => {
                return LessonDetailCard;
            }));
        },
        changeFile(e) {
            this.file = e.target.files[0];
        },
        import() {
            this.type = 'import';
            // put all current lesson details id to removed_ids
            this.details.forEach((detail) => {
                if (detail.id !== 0) {
                    this.removed_ids.push(detail.id);
                }
            });
            if (this.importFile) {
                if (this.file === null) {
                    this.$root.showSnackbar('Please select a file', 'danger');
                    return;
                }
                const formData = new FormData();
                formData.append('file', this.file);
                this.$store.dispatch('lesson/importFile', formData);
            } else {
                this.$root.showSnackbar('Importing lesson...', 'info');
                try {
                    let raw_data = document.getElementById('raw_data').value;
                    const term_separator = document.getElementById('betweenTermDefinition').value;
                    let detail_separator = document.getElementById('betweenCard').value;
                    // check detail_separator is regex
                    if (detail_separator.startsWith('/') && detail_separator.endsWith('/')) {
                        detail_separator = detail_separator.substring(1, detail_separator.length - 1);
                    }
                    let lesson_detail = raw_data.split(new RegExp(detail_separator, 'g'));
                    this.details = [];
                    lesson_detail.forEach((detail, index) => {
                        let term_definition = detail.split(term_separator);
                        if (term_definition.length !== 2) {
                            throw new Error('Invalid format');
                        }
                        this.details.push({
                            index: index,
                            id: 0,
                            term: term_definition[0],
                            definition: term_definition[1],
                        });
                    });
                    this.cards = markRaw(this.details.map((detail, index) => {
                        return LessonDetailCard;
                    }));
                    this.import_lesson_modal.hide();
                    this.$root.showSnackbar('Import lesson successfully', 'success');
                } catch (e) {
                    this.$root.showSnackbar('Import lesson failed', 'danger');
                }
            }
        },
        updateLesson() {
            this.user = this.$store.getters['user/userData'].info;
            console.log(this.user);
            this.type = 'update';
            let data = {
                id: this.id,
                name: this.lesson.name,
                description: this.lesson.description,
                password: this.lesson.password,
                details: this.details,
                removed_ids: this.removed_ids,
                roleName: this.user.role.name,
            };
            this.$store.dispatch('lesson/updateLesson', data);
        }
    }
};
</script>
