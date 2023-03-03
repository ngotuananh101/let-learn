<template>
    <div class="container-fluid">
        <div class="mt-4 row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <h5 class="mb-0">Add Set</h5>
                        <p class="mb-0 text-sm">Add new set to server</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <label class="form-label mt-2 fs-6">Name</label>
                                <argon-input id="name" type="text" name="name"
                                             placeholder="Enter a name, like:'MAE Chapter 1' "/>
                            </div>
                            <div class="col-md-8 col-12">
                                <label class="form-label mt-2 fs-6">Description</label>
                                <argon-input id="description" type="text" name="description"
                                             placeholder="Add a description..."/>
                            </div>
                            <div class="col-12">
                                <div v-for="(child, index) in children">
                                    <component :is="child" :title="index+1 + ''" :key="'setDetail'+index" @remove="this.removeCard" :data="{}"></component>
                                </div>
                            </div>
                            <div class="col-12">
                                <argon-button color="secondary" variant="gradient" full-width class="my-2 py-5 btn-lg fs-6" @click="this.addSetCard">Add Card</argon-button>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6" @click="this.createSet">Create</argon-button>
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
import SetCard from "@/pages/admin/set/SetCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "List",
    components: {
        ArgonInput,
        SetCard,
        ArgonButton,
    },
    title(){
        return 'Add Lesson' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    data() {
        return {
            children: [SetCard, SetCard, SetCard],
            count: 3
        }
    },
    methods: {
        addSetCard() {
            console.log('addSetCard');
            this.children.push(SetCard);
            this.count++;
        },
        removeCard(id){
            this.children.splice(id-1, 1);
            this.count--;
        },
        createSet(){
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            let setDetail = [];
            for (let i = 1; i <= this.count; i++) {
                let card = document.getElementById('card'+i);
                let term = card.getElementsByClassName('term')[0].value;
                let definition = card.getElementsByClassName('definition')[0].value;
                if (term !== '' && definition !== '') {
                    setDetail.push({term: term, definition: definition});
                }
            }
            if (name === '' || description === '' || setDetail.length <3) {
                alert('Please fill all fields and add at least 3 cards');
            }else{
                this.$store.dispatch('adminSet/create', {name: name, description: description, detail: setDetail}).then(() => {
                    this.$router.push({name: 'admin.set.list'});
                });
            }
        }
    },
}
</script>
