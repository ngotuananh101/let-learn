<template>
    <div id="sidenav-collapse-main" class="collapse navbar-collapse w-auto h-auto h-100 overflow-hidden">
        <ul class="navbar-nav">
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.dashboard' })" collapse-ref="" nav-text="Dashboards"
                    :class="getRoute() === 'dashboard' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-solid fa-chart-line text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.setting' })" collapse-ref="" nav-text="Settings"
                                  :class="getRoute() === 'setting' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-gear text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    Content
                </h6>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.set.list' })" collapse-ref="" nav-text="Set"
                                  :class="getRoute() === 'set' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-book text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.folder.list' })" collapse-ref="" nav-text="Folder"
                                  :class="getRoute() === 'folder' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-folder text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse @click="$router.push({ name: 'admin.setting' })" collapse-ref="" nav-text="Class"
                                  :class="getRoute() === 'class' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-screen-users text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
        </ul>
    </div>
</template>
<script>
import SidenavCollapse from "@/components/Sidenav/SidenavCollapse.vue";
import SidenavItem from "@/components/Sidenav/SidenavItem.vue";
import SidenavCard from "@/components/Sidenav/SidenavCard.vue";

export default {
    name: "SidenavList",
    components: {
        SidenavCollapse,
        SidenavItem,
        SidenavCard,
    },
    methods: {
        getRoute() {
            const routeArr = this.$route.path.split("/");
            return routeArr[2];
        }
    }
};
</script>
