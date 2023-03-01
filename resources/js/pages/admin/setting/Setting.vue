<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-2">
                <SettingMenu />
            </div>
            <div class="col-lg-10 mt-lg-0 mt-4">
                <SettingContent />
            </div>
        </div>
    </div>
</template>

<script>
import SettingMenu from "@/pages/admin/setting/SettingMenu.vue";
import SettingContent from "@/pages/admin/setting/SettingContent.vue";

export default {
    name: "Settings",
    components: {
        SettingMenu,
        SettingContent,
    },
    title() {
        return 'System Setting' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
};
</script>
