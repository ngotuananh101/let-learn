<template>
    <div class="lesson-detail card my-4">
        <div class="card-header bg-secondary">
            <div class="row">
                <div class="col-6"><h5 class="mb-0">{{ data.index + 1 }}</h5></div>
                <div class="col-6 ms-auto text-end">
                    <span class="text-success fs-5" @click="this.switch()" title="Switch term and definition"><i
                        class="fa-solid fa-arrows-retweet me-2"></i></span>
                    <span class="text-danger fs-5" @click="this.remove()" title="Delete this detail"><i
                        class="far fa-trash-alt me-2" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="card-body bg-secondary">
            <div class="row">
                <input type="hidden" class="id" v-model="data.id">
                <div class="col-md-8 col-12">
                    <label>Term</label>
                    <textarea class="form-control term" rows="8" v-model="data.term"></textarea>
                </div>
                <div class="col-md-4 col-12">
                    <label>Definition</label>
                    <textarea class="form-control definition" rows="8" v-model="data.definition"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Lesson Detail Card",
    props: {
        data:{
            type: Object,
            default: null,
        },
    },
    methods: {
        // remove this card
        remove() {
            this.$emit('remove', {
                data: this.data,
            });
        },
        switch() {
            // switch term and definition
            let term = this.data.term;
            this.data.term = this.data.definition;
            this.data.definition = term;
        },
    },
};
</script>

<style>
.lesson-detail .card-header{
    border-radius: 1rem 1rem 0 0!important;
    padding-bottom: 0 !important;
}
.lesson-detail .card-body{
    padding-top: 0 !important;
    border-radius: 0 0 1rem 1rem!important;
}
</style>
