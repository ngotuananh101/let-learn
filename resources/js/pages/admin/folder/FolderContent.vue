<template>
    <div id="info" class="card mt-4">
        <div class="card-header">
            <h5>Basic Info</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-5">
                    <label class="form-label mt-2">Folder name</label>
                    <argon-input id="name" type="text" name="name" placeholder="Folder name"/>
                </div>
                <div class="col-7">
                    <label class="form-label mt-2">Folder description</label>
                    <argon-input id="description" type="text" name="description" placeholder="Folder description"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ArgonInput from "@/components/Argons/ArgonInput.vue";
export default {
    name: "FolderContent",
    components: {
        ArgonInput,
    },
    data() {
        return {
            folder: {},
        }
    },
}
</script>

<style scoped>

</style>
