<template>
    <div class="my-4">
        <div class="card">
            <div class="card-body text-success text-sans-serif text-2xl">
                Learn anything, anytime, anywhere
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="row">
            <h3>Lesson</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons" :key="lesson.id">
                <router-link :to="`/lesson/${lesson.id}`">
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="card-text text-primary">
                                Title: {{ lesson.description }}
                            </p>
                            <p class="card-text">Author: {{ lesson.name }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row mt-5">
            <h3>Course</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="course in courses" :key="course.id">
                <router-link :to="`/course/${course.id}`">
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="card-text text-primary">
                                Title: {{ course.description }}
                            </p>
                            <p class="card-text">Author: {{ course.name }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row mt-5">
            <h3>Other Lessons</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="ol in other_lesson" :key="ol.id">
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="card-text text-primary">
                            Title: {{ ol.description }}
                        </p>
                        <p class="card-text">Author: {{ ol.name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 mt-5">
            <h3>Other Courses</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="other_courses in other_course" :key="other_courses.id">
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="card-text text-primary">
                            Title: {{ other_courses.description }}
                        </p>
                        <p class="card-text">
                            Author: {{ other_courses.name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating button -->
    <div class="position-fixed bottom-3 end-2" style="z-index: 11">
        <i class="fa-sharp fa-solid fa-circle-plus" style="font-size: 4rem" @click="this.addModal.show()"></i>
    </div>
    <div class="modal" id="modal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-body row">
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" @click="createLesson">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/5484/5484383.png" class="card-img-top"
                                    alt="..." />
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Create lesson</h5>
                                <p class="card-text">
                                    Create a new lesson from scratch.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" data-bs-toggle="modal" href="#modalCreateCourse">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/7688/7688940.png" class="card-img-top"
                                    alt="..." />
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Create course</h5>
                                <p class="card-text">
                                    Create a new course from scratch.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" data-bs-toggle="modal" href="#importModal">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/10215/10215645.png" class="card-img-top"
                                    alt="..." />
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Import lesson</h5>
                                <p class="card-text">
                                    Import a lesson from other sources.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div class="card">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img src="https://cdn-icons-png.flaticon.com/512/8744/8744051.png" class="card-img-top"
                                    alt="..." />
                            </div>
                            <div class="card-body p-2 text-center text-black py-4">
                                <h5 class="card-title">Faqs</h5>
                                <p class="card-text">
                                    Learn more about Let Learn Team.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCreateCourse" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">Create Course</h5>
                    <i class="fa-sharp fa-solid fa-folder-plus"></i>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="form-label mt-2 fs-6">Name</label>
                        <input class="form-control" id="name" type="text" name="name"
                            placeholder="Enter a name. Example:'MAE Chapter 1' " />
                    </div>
                    <div>
                        <label class="form-label mt-2 fs-6">Description</label>
                        <input class="form-control" id="description" type="text" name="description"
                            placeholder="Add a description..." />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm" data-bs-toggle="modal" href="#modal">
                        Cancel
                    </button>
                    <button type="button" class="btn bg-gradient-success btn-sm" data-bs-toggle="modal"
                        @click="this.createCourse">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="importModal" class="modal fade" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">Import Lesson</h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
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
                    <button type="button" class="btn bg-gradient-secondary btn-sm" data-bs-toggle="modal" href="#modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-success btn-sm">
                        Import
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
    name: "home",
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            unsubscribe: null,
            lessons: [],
            courses: [],
            other_lesson: [],
            other_course: [],
            addModal: null,
            import_lesson_modal: null,
            importFile: true,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.lessons = mutation.payload.lessons;
                this.courses = mutation.payload.courses;
                this.other_lesson = mutation.payload.other_lesson;
                console.log(this.other_lesson);
                this.other_course = mutation.payload.other_course;
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getHome");
    },
    mounted() {
        this.addModal = new Modal(document.getElementById("modal"));
    },
    methods: {
        createLesson() {
            // hide modal
            this.addModal.hide();
            // redirect to add lesson page
            this.$router.push({ name: "lesson.add" });
        },
        createCourse() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            if (name === '' || description === '') {
                alert('Please fill all fields to create new course');
            } else {
                this.$store.dispatch('course/createCourse', { name: name, description: description }).then(() => {
                    this.$router.push({ name: "home.home" });
                });
            }
        },
        init() {
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
            // init 3 cards
            this.details = [
                {
                    index: 0,
                    term: '',
                    definition: '',
                },
                {
                    index: 1,
                    term: '',
                    definition: '',
                },
                {
                    index: 2,
                    term: '',
                    definition: '',
                },
            ];
            this.cards = markRaw(this.details.map((detail, index) => {
                return LessonDetailCard;
            }));
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
                this.$store.dispatch('lessonService/importExcelFile', formData);
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
    }
};


</script>
<style scoped>
.card {
    flex: 1;
}
</style>
