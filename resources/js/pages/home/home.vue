<template lang="">
    <!-- Floating button -->
    <div class="position-fixed bottom-3 end-2" style="z-index: 11">
        <i
            class="fa-sharp fa-solid fa-circle-plus"
            style="font-size: 4rem"
            @click="this.addModal.show()"
        ></i>
    </div>
    <div class="modal" id="modal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-body row">
                    <div class="col-md-3 col-6 p-3">
                        <div class="card" @click="createLesson">
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/5484/5484383.png"
                                    class="card-img-top"
                                    alt="..."
                                />
                            </div>
                            <div
                                class="card-body p-2 text-center text-black py-4"
                            >
                                <h5 class="card-title">Create lesson</h5>
                                <p class="card-text">
                                    Create a new lesson from scratch.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div
                            class="card"
                            data-bs-toggle="modal"
                            href="#modalCreateCourse"
                        >
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/7688/7688940.png"
                                    class="card-img-top"
                                    alt="..."
                                />
                            </div>
                            <div
                                class="card-body p-2 text-center text-black py-4"
                            >
                                <h5 class="card-title">Create course</h5>
                                <p class="card-text">
                                    Create a new course from scratch.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 p-3">
                        <div
                            class="card"
                            data-bs-toggle="modal"
                            href="#importModal"
                        >
                            <div class="p-5 pb-4 m-4 mb-0">
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/10215/10215645.png"
                                    class="card-img-top"
                                    alt="..."
                                />
                            </div>
                            <div
                                class="card-body p-2 text-center text-black py-4"
                            >
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
                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/8744/8744051.png"
                                    class="card-img-top"
                                    alt="..."
                                />
                            </div>
                            <div
                                class="card-body p-2 text-center text-black py-4"
                            >
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
    <div
        class="modal fade"
        id="modalCreateCourse"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content p-md-5">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">Create Course</h5>
                    <i class="fa-sharp fa-solid fa-folder-plus"></i>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="mx-2" id="name">Name:</span>
                        <input
                            type="name"
                            class="form-control"
                            id="name"
                            placeholder="Enter a name. Example:'MAE Chapter 1' "
                            aria-describedby="basic-addon1"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <span class="mx-2" id="description"
                            >Description:</span
                        >
                        <input
                            type="description"
                            class="form-control"
                            id="description"
                            placeholder="Add a description..."
                            aria-describedby="basic-addon1"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn bg-gradient-secondary btn-sm"
                        data-bs-toggle="modal"
                        href="#modal"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        class="btn bg-gradient-success btn-sm"
                        data-bs-toggle="modal"
                        @click="this.createCourse"
                    >
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div
        id="importModal"
        class="modal fade"
        tabindex="-1"
        aria-hidden="true"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog modal-lg mt-lg-10">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="ModalLabel" class="modal-title">Import Lesson</h5>
                    <i class="fa-regular fa-cloud-arrow-up ms-3"></i>
                </div>
                <form @submit.prevent="handleImport">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                <label class="mx-2"
                                    >Name</label
                                >
                                <input
                                    id="name"
                                    name="name"
                                    class="form-control mb-2"
                                    placeholder="Enter name..."
                                    required
                                />
                            </div>
                            <div class="col-7">
                                <label class="mx-2"
                                    >Description</label
                                >
                                <input
                                    id="description"
                                    name="description"
                                    class="form-control mb-2"
                                    placeholder="Enter description..."
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input mb-3"
                                type="checkbox"
                                role="switch"
                                id="importText"
                                checked
                                @change="this.switchImport"
                            />
                            <label class="form-check-label" for="importText"
                                >Import from text</label
                            >
                        </div>
                        <div v-if="this.importType === 'file'">
                            <p>
                                Import set from csv, xls, xlsx file (
                                <a
                                    href="https://s3-hcm-r1.longvan.net/19403879-letlearn/template/lesson_template.xlsx"
                                    target="_blank"
                                    >Template</a
                                >
                                )
                            </p>
                            <input
                                type="file"
                                placeholder="Browse file..."
                                class="mb-3 form-control"
                                accept=".csv, .xls, .xlsx"
                                name="file"
                            />
                        </div>
                        <div v-if="this.importType === 'text'">
                            <p>Import set from text</p>
                            <textarea
                                id="raw_data"
                                name="raw_data"
                                class="form-control mb-2"
                                rows="10"
                                placeholder="Paste text here..."
                            ></textarea>
                            <div class="row">
                                <div class="col-7">
                                    <label class="mt-2"
                                        >Between Term and Definition</label
                                    >
                                    <input
                                        id="betweenTermDefinition"
                                        name="term_separator"
                                        class="form-control mb-2"
                                        placeholder="Enter text here..."
                                        value="-----"
                                    />
                                </div>
                                <div class="col-5">
                                    <label class="mt-2"
                                        >Between Card</label
                                    >
                                    <input
                                        id="betweenTermDefinition"
                                        name="detail_separator"
                                        class="form-control mb-2"
                                        placeholder="Enter text here..."
                                        value="~~~~~"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn bg-gradient-secondary btn-sm"
                            data-bs-toggle="modal"
                            href="#modal"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn bg-gradient-success btn-sm"
                        >
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
    data() {
        return {
            addModal: null,
            importType: "file",
        };
    },
    mounted() {
        this.addModal = new Modal(document.getElementById("modal"));
    },
    methods: {
        switchImport() {
            if (this.importType === "file") {
                this.importType = "text";
            } else {
                this.importType = "file";
            }
        },
    },
};
</script>
<style lang=""></style>
