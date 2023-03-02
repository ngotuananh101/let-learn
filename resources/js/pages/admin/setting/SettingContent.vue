<template>
    <div id="basic-info" class="card mt-4">
        <div class="card-header">
            <h5>Basic Info</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-2">Site name</label>
                    <argon-input id="name" type="text" name="name" placeholder="Site name" :value="this.setting.name"
                                 @keyup.enter="this.update"/>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">TimeZone</label>
                    <select
                        id="timezone"
                        class="form-control"
                        name="timezone"
                        @change="this.update"
                    >
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Description</label>
                    <argon-textarea
                        id="description"
                        name="description"
                        class="multisteps-form__textarea mt-0"
                        placeholder="Description"
                        @keyup.enter="this.update"
                    />
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Add HTML to header</label>
                    <argon-textarea
                        id="header"
                        name="header"
                        class="multisteps-form__textarea mt-0"
                        placeholder="Description"
                    />
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Keywords</label>
                    <input
                        id="keywords"
                        name="keywords"
                        class="form-control"
                        type="text"
                        :value="this.setting.keywords"
                        placeholder="Keywords separated by comma"
                        onfocus="focused(this)"
                        onfocusout="defocused(this)"
                        @keyup.enter="this.update"
                    />
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label mt-2">Favicon</label>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img class="rounded img-fluid w-25" :src="this.setting.favicon"
                                     alt="favicon" id="favicon-preview">
                                <input
                                    type="file"
                                    name="favicon"
                                    placeholder="Browse file..."
                                    class="mb-3 mt-3 form-control"
                                    accept="image/x-icon"
                                    @change="this.update"
                                />
                            </div>
                        </div>
                        <div class="col-9">
                            <label class="form-label mt-2">Logo</label>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img class="rounded img-fluid w-25" :src="this.setting.logo"
                                     alt="logo" id="logo-preview">
                                <input
                                    type="file"
                                    name="logo"
                                    placeholder="Browse file..."
                                    class="mb-3 mt-3 form-control"
                                    accept="image/png"
                                    @change="this.update"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="notification" class="card mt-4">
        <div class="card-header">
            <h5>Notification</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-12">
                    <label class="form-label mt-2">Onesignal appId</label>
                    <argon-input id="onesignal_app_id" type="text" name="onesignal_app_id" placeholder="Onesignal App Id" :value="this.setting.onesignal_app_id"
                                 @keyup.enter="this.update"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import * as Choices from "choices.js";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import ArgonTextarea from "@/components/Argons/ArgonTextarea.vue";
import {mapActions} from "vuex";

export default {
    name: "SettingContent",
    components: {
        ArgonInput,
        ArgonTextarea,
    },
    data() {
        return {
            setting: {
                name: '',
                timezone: '',
                description: '',
                keywords: '',
                favicon: '',
                logo: '',
            },
        };
    },
    beforeMount() {
        this.index();
        this.setting = this.$store.state.adminSetting.settings;
    },
    mounted() {
        // set timezone
        this.setTimeZone();
        // set description
        document.getElementById('description').value = this.setting.description;
        // set header
        document.getElementById('header').value = this.setting.header;
        // set keywords
        this.setKeywords();
    },
    methods: {
        ...mapActions({
            index: 'adminSetting/index',
            updateSetting: 'adminSetting/update',
        }),
        update(e) {
            let key = e.target.getAttribute('name');
            let valuee = e.target.value;
            this.$root.$data.snackbar = {
                color: 'warning',
                message: 'Updating ' + key + '...',
            };
            if (key === 'favicon' || key === 'logo') {
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById(key + '-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
                valuee = file;
            }
            this.updateSetting({key: key, value: valuee}).then(() => {
                this.$root.$data.snackbar = {
                    color: 'success',
                    message: 'Updated ' + key + ' successfully!',
                };
            }).catch(() => {
                this.$root.$data.snackbar = {
                    color: 'danger',
                    message: 'Updated ' + key + ' failed!',
                };
            }).then(() => {
                setTimeout(() => {
                    this.$root.$data.snackbar = '';
                }, 500);
            });
        },
        setTimeZone() {
            let listTimeZone = Intl.supportedValuesOf('timeZone');
            let select = document.getElementById('timezone');
            listTimeZone.forEach(function (item) {
                let option = document.createElement('option');
                option.value = item;
                option.text = item;
                select.appendChild(option);
            });
            document.getElementById('timezone').value = this.setting.timezone;
            new Choices('#timezone', {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true,
            });
        },
        setKeywords() {
            new Choices('#keywords', {
                delimiter: ",",
                editItems: true,
                maxItemCount: 10,
                removeItemButton: true,
                addItems: true,
                allowHTML: true,
            });
        }
    },
};
</script>
