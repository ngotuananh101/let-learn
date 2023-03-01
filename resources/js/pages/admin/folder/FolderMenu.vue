<template>
    <div class="card position-sticky top-1 mt-4">
        <ul class="nav flex-column bg-white border-radius-lg p-3">
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#info">
                    <i class="fa-regular fa-folder me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Folder Information</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#set">
                    <i class="fa-regular fa-book me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Folder Set</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "FolderMenu"
}
</script>

<style scoped>

</style>
