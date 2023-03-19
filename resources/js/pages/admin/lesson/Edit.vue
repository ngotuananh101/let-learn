<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Update Set</h5>
                        <p class="mb-0 text-sm">Update set data</p>
                    </div>
                    <div class="card-body" v-if="this.data">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                             placeholder="Enter a name, like:'MAE Chapter 1'"
                                             :value="this.data.set.name"/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                             placeholder="Add a description..." :value="this.data.set.description"/>
                            </div>
                            <div class="col-4">
                                <label class="form-label mt-2 fs-6">Status</label>
                                <argon-switch id="status" name="status" class="mb-3"
                                              :checked="this.data.set.status === 'active'">
                                    Active
                                </argon-switch>
                            </div>
                            <div class="col-2">
                                <label class="form-label mt-2 fs-6">Public</label>
                                <argon-switch id="is_public" name="is_public" class="mb-3"
                                              :checked="this.data.set.is_public" @change="publicChange">
                                    Public
                                </argon-switch>
                            </div>
                            <div v-if="password_option" class="col-6">
                                <label class="form-label mt-2 fs-6">Password (option)</label>
                                <argon-input id="name" type="password" name="name"
                                             placeholder="Enter passord for this set"
                                             :value="this.data.set.password"/>
                            </div>
                            <div class="col-12">
                                <label class="form-label mt-2 fs-6">Detail</label>
                                <div v-for="(child, index) in children">
                                    <component :is="child" :title="index+1 + ''" :key="'setDetail'+index"
                                               @remove="this.removeCard"
                                               :data="{
                                                   term: data.set_data[index]?data.set_data[index].term:'',
                                                   definition: data.set_data[index]?data.set_data[index].definition:'' }"
                                    ></component>
                                </div>
                            </div>
                            <div class="col-12">
                                <argon-button color="secondary" variant="gradient" full-width
                                              class="my-2 py-5 btn-lg fs-6" @click="this.addSetCard">Add Card
                                </argon-button>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                              @click="this.updateSet">Update
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
import {mapActions} from "vuex";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import SetCard from "@/pages/admin/lesson/SetCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonSwitch from "@/components/Argons/ArgonSwitch.vue";

export default {
    name: "List",
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
            data: null,
            password_option: false,
        }
    },
    beforeMount() {
        this.getSet(this.$route.params.id).then((res) => {
            // return res.data;
            this.data = res.data;
            if(!this.data.set.is_public){
                this.password_option = true;
            }
            this.children = [];
            this.count = 0;
            for (let i = 0; i < this.data.set_data.length; i++) {
                // lesson data for each card
                this.children.push(SetCard);
                this.count++;
            }
        }).then(() => {
            window.scrollTo(0, document.body.scrollHeight);
        });
    },
    mounted() {
    },
    methods: {
        ...mapActions({
            getSet: 'adminSet/getSet',
        }),
        addSetCard() {
            this.children.push(SetCard);
            this.count++;
        },
        removeCard(id) {
            this.children.splice(id - 1, 1);
            this.count--;
        },
        publicChange(){
            this.password_option = !this.password_option;
        },
        updateSet() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let status = document.getElementById('status').checked ? 'active' : 'inactive';
            let is_public = document.getElementById('is_public').checked ? 1 : 0;
            let password = document.getElementById('password') ? document.getElementById('password').value:'';
            let setDetail = [];
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card' + i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                if (term !== '' && definition !== '') {
                    setDetail.push({term: term, definition: definition});
                }
            }
            if (name === '' || description === '' || setDetail.length < 3) {
                alert('Please fill all fields and add at least 3 cards');
            } else {
                this.$store.dispatch('adminSet/updateSet', {
                    id: this.$route.params.id,
                    name: name,
                    description: description,
                    status: status,
                    is_public: is_public,
                    password: password,
                    data: setDetail
                });
            }
        }
    },
}
</script>
