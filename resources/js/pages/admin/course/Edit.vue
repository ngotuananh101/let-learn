<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-3">
                <FolderMenu />
            </div>
            <div class="col-lg-9 mt-lg-0 mt-4">
                <FolderContent />
            </div>
        </div>
    </div>
</template>

<script>
import FolderMenu from "@/pages/admin/folder/FolderMenu.vue";
import FolderContent from "@/pages/admin/folder/FolderContent.vue";
export default {
    name: "edit",
    components: {
        FolderMenu,
        FolderContent,
    },
}
</script>

<style scoped>

</style>
