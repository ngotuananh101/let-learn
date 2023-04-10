<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-12">
                <div id="basic-info" class="card mt-4">
                    <div class="card-header">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Basic settings</h5>
                                <p class="mb-0 text-sm">Basic settings of your site</p>
                            </div>
                            <div class="my-auto mt-4 ms-auto mt-lg-0">
                                <div class="my-auto ms-auto">
                                    <button type="button"
                                            class="mx-1 mb-0 btn btn-outline-success btn-sm" @click="this.update">
                                        Update
                                        <i class="fas fa-save ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-6 form-group">
                                <label class="form-label mt-2">Site name</label>
                                <div>
                                    <input
                                        id="app_name"
                                        name="app_name"
                                        class="form-control"
                                        type="text"
                                        placeholder="Site name"
                                        v-model="setting.app_name"
                                    />
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-2">TimeZone</label>
                                <select
                                    id="app_timezone"
                                    class="form-control"
                                    name="app_timezone"
                                    v-model="setting.app_timezone"
                                >
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Description</label>
                                <textarea
                                    id="app_description"
                                    name="app_description"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Description"
                                    v-model="setting.app_description"
                                ></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Add HTML to header</label>
                                <textarea
                                    id="app_head_code"
                                    name="app_head_code"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Add code to header"
                                    v-model="setting.app_head_code"
                                ></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2">Keywords</label>
                                <input
                                    id="app_keywords"
                                    name="app_keywords"
                                    class="form-control"
                                    type="text"
                                    placeholder="Keywords"
                                />
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-2">Favicon</label>
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img class="rounded img-fluid w-25" :src="this.setting.app_favicon"
                                         alt="favicon" id="favicon-preview">
                                    <input
                                        type="file"
                                        name="favicon"
                                        placeholder="Browse file..."
                                        class="mb-3 mt-3 form-control"
                                        accept="image/*"
                                        @change="onFileChange"
                                    />
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label mt-2">Logo</label>
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img class="rounded img-fluid w-25" :src="this.setting.app_logo"
                                         alt="logo" id="logo-preview">
                                    <input
                                        type="file"
                                        name="logo"
                                        placeholder="Browse file..."
                                        class="mb-3 mt-3 form-control"
                                        accept="image/*"
                                        @change="onFileChange"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Choices from "choices.js";

export default {
    name: "Settings",
    title() {
        return "System Setting";
    },
    data() {
        return {
            setting: {},
            unsubscribe: null,
            choice_timezone: null,
            choice_keywords: null,
            is_update: false,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminSettings/request') {
                this.$root.showSnackbar('Loading...', 'info');
            } else if (mutation.type === 'adminSettings/success') {
                this.$root.showSnackbar('Success', 'success');
                this.setting = mutation.payload;
                if (!this.is_update) {
                    this.init();
                    this.is_update = true;
                } else {

                }
            } else if (mutation.type === 'adminSettings/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('adminSettings/get');
    },
    beforeUnmount() {
        this.unsubscribe();
        this.choice_timezone.destroy();
        this.choice_keywords.destroy();
    },
    methods: {
        init() {
            this.choice_timezone = new Choices('#app_timezone', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
            // set options for timezone
            this.choice_timezone.setChoices(
                this.setting.timezone.map((timezone) => {
                    return {
                        value: timezone,
                        label: timezone,
                        selected: timezone === this.setting.app_timezone,
                    };
                }),
                'value',
                'label',
                true
            );
            document.getElementById('app_keywords').value = this.setting.app_keywords;
            this.choice_keywords = new Choices('#app_keywords', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
                removeItemButton: true,
                duplicateItemsAllowed: false,
                maxItemCount: 10,
                addItems: true,
                addItemFilter: function (value) {
                    return value.length > 2;
                },
                editItems: true,
                delimiter: ',',
                paste: true,
                shouldSort: false,
                placeholder: true,
                placeholderValue: 'Add keyword (max 10) ',
            });
            // set event listener for keywords
            this.choice_keywords.passedElement.element.addEventListener(
                'change',
                (event) => {
                    this.setting.app_keywords = this.choice_keywords.getValue(true).join(',');
                }
            );
        },
        update() {
            this.$store.dispatch('adminSettings/update', this.setting);
        },
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(files[0], e.target.name);
        },
        createImage(file, name) {
            let reader = new FileReader();
            reader.onload = (e) => {
                if (name === 'favicon') {
                    this.setting.app_favicon = e.target.result;
                } else {
                    this.setting.app_logo = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        },
    }
};
</script>
