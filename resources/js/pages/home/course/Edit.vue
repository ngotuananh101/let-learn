<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Update Course</h5>
                        <p class="mb-0 text-sm">Update course data</p>
                    </div>
                    <div class="card-body">
                        <div class="row" v-if="this.data">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                    placeholder="Enter a name, like:'MAE Chapter 1'" :value="this.data.lesson.name" />
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                    placeholder="Add a description..." :value="this.data.lesson.description" />
                            </div>
                            <div class="col-4">
                                <label class="form-label mt-2 fs-6">Status</label>
                                <argon-switch id="status" name="status" class="mb-3"
                                    :checked="this.data.lesson.status === 'active'">
                                    Active
                                </argon-switch>
                            </div>
                            <div class="col-2">
                                <label class="form-label mt-2 fs-6">Public</label>
                                <argon-switch id="is_public" name="is_public" class="mb-3"
                                    :checked="this.data.lesson.is_public" @change="publicChange">
                                    Public
                                </argon-switch>
                            </div>
                            <div v-if="password_option" class="col-6">
                                <label class="form-label mt-2 fs-6">Password (option)</label>
                                <argon-input id="name" type="password" name="name" placeholder="Enter passord for this set"
                                    :value="this.data.lesson.password" />
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-6 col-12 mt-4" v-for="lesson in lessons">
                                    <router-link :to="`/l/${lesson.id}`">
                                        <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'"
                                            :description="lesson.username" />
                                    </router-link>
                                </div>
                            </div> -->
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                    @click="this.updateCourse">Update
                                </argon-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import SetCard from "@/pages/admin/lesson/SetCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";

export default {
    name: "Edit",
    components: {
        ArgonInput,
        SetCard,
        ArgonButton,
        ArgonSwitch
    },
    data() {
        return {
            children: [],
            count: 0,
            password_option: false,
        }
    },
    beforeMount() {
        this.$store.dispatch('home/getCourseInfo', this.$route.params.id).then((res) => {
            this.data = res;
            this.password_option = !this.data.lesson.is_public;
            for (let i = 0; i < this.data.detail.length; i++) {
                this.children.push(SetCard);
                this.count++;
            }
        });
    },
    methods: {
        publicChange() {
            this.password_option = !this.password_option;
        },
        updateCourse() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let status = document.getElementById('status').checked ? 'active' : 'inactive';
            let is_public = document.getElementById('is_public').checked ? 1 : 0;
            let password = document.getElementById('password') ? document.getElementById('password').value : '';
            if (name === '' || description === '') {
                alert('Please fill all fields and add at least 3 cards');
            } else {
                this.$store.dispatch('home/updateCourse', {
                    id: this.$route.params.id,
                    name: name,
                    description: description,
                    status: status,
                    is_public: is_public,
                    password: password,
                });
            }
        }
    },
}
</script>
