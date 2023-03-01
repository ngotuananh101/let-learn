<template>
    <div class="card my-2">
        <div class="card-header pt-3 pb-0 px-3 bg-secondary">
            <div class="row">
                <div class="col-6"><h5 class="mb-0">{{ title }}</h5></div>
                <div class="col-6 ms-auto text-end">
                    <span class="text-danger fs-5" @click="this.remove()"><i class="far fa-trash-alt me-2" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
        <div class="card-body pt-2 p-3 bg-secondary" :id="'card'+title">
            <div class="row">
                <div class="col-4">
                    <label>Term</label>
                    <textarea class="form-control fs-6 term" rows="8">{{ data ? data.term : '' }}</textarea>
                </div>
                <div class="col-8">
                    <label>Definition</label>
                    <textarea class="form-control fs-6 definition" rows="8">{{ data ? data.definition : '' }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer bg-secondary pb-0 pt-3"></div>
    </div>
</template>

<script>
export default {
    name: "SetCard",
    props: {
        title: {
            type: String,
            required: true,
        },
        data: {
            type: Object,
            default: null,
        },
    },
    created() {
    },
    methods: {
        // remove this card
        remove() {
            this.$emit('remove', this.title);
        },
    },
};
</script>
