<template>
    <div class="form-group">
    <textarea
        :id="id"
        class="form-control"
        :rows="rows"
        :placeholder="placeholder"
    ></textarea>
    </div>
</template>

<script>
export default {
    name: "ArgonTextarea",
    props: {
        id: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: "",
        },
        rows: {
            type: Number,
            default: 5,
        },
        value: {
            type: String,
            default: "",
        },
    },
};
</script>
