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
                            <div class="col-md-9 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-3">Name</label>
                                        <input
                                            id="name"
                                            name="name"
                                            class="form-control"
                                            type="text"
                                            placeholder="Name"
                                            v-model="this.school.name"
                                        />
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label mt-3">Slug</label>
                                        <input
                                            id="slug"
                                            name="slug"
                                            class="form-control"
                                            type="text"
                                            placeholder="Slug can null"
                                            v-model="this.school.slug"
                                            disabled
                                        />
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label mt-3">Website</label>
                                        <input
                                            id="website"
                                            name="website"
                                            class="form-control"
                                            type="text"
                                            placeholder="Website"
                                            v-model="this.school.website"
                                        />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-3">Address</label>
                                        <input
                                            id="address"
                                            name="address"
                                            class="form-control"
                                            type="text"
                                            placeholder="Address"
                                            v-model="this.school.address"
                                        />
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label mt-3">City</label>
                                        <input
                                            id="city"
                                            name="city"
                                            class="form-control"
                                            type="text"
                                            placeholder="City"
                                            v-model="this.school.city"
                                        />
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label mt-3">Region / Country</label>
                                        <input
                                            id="region"
                                            name="region"
                                            class="form-control"
                                            type="text"
                                            placeholder="Region / Country"
                                            v-model="this.school.region"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label mt-3">Logo</label>
                                        <input
                                            type="file"
                                            name="logo"
                                            placeholder="Browse file..."
                                            class="mb-3 mt-3 form-control"
                                            accept="image/*"
                                            @change="onFileChange"
                                        />
                                        <img
                                            :src="this.school.logo ?? 'https://t3.ftcdn.net/jpg/02/68/55/60/360_F_268556012_c1WBaKFN5rjRxR2eyV33znK4qnYeKZjm.jpg'"
                                            class="img-fluid mt-3 rounded" alt="logo">
                                    </div>
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
            school: {},
            unsubscribe: null,
            type: 'get',
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'school/request') {
            } else if (mutation.type === 'school/success') {
                if (this.type === 'get') {
                    this.school = mutation.payload.school;
                } else {
                    this.$root.showSnackbar('Update success', 'success');
                    // update school in local storage
                }
            } else if (mutation.type === 'school/failure') {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch('school/get', this.$route.params.slug);
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length) return;
            this.createImage(files[0], e.target.name);
        },
        createImage(file, name) {
            let reader = new FileReader();
            reader.onload = (e) => {
                this.school[name] = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        update() {
            this.type = 'update';
            let school = {
                name: this.school.name,
                slug: this.school.slug,
                website: this.school.website,
                address: this.school.address,
                city: this.school.city,
                region: this.school.region,
                logo: this.school.logo,
            };
            this.$store.dispatch('school/update', {slug: this.$route.params.slug, school: school});
        }
    }
};
</script>
