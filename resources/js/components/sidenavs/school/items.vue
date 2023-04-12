<template>
    <div
        id="sidenav-collapse-main"
        class="collapse navbar-collapse w-auto h-auto h-100 overflow-hidden"
    >
        <ul class="navbar-nav">
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="dashboard"
                    nav-text="Dashboard"
                    :class="getRoute() === 'school.dashboard' ? 'active' : ''"
                    @click="go('school.dashboard')"
                >
                    <template #icon>
                        <i class="fa-regular fa-chart-line text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="setting"
                    nav-text="Setting"
                    :class="getRoute() === 'school.setting' ? 'active' : ''"
                    @click="go('school.setting')"
                >
                    <template #icon>
                        <i class="fa-regular fa-gear text-info text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="user"
                    nav-text="User"
                    :class="getRoute() === 'school.users' ? 'active' : ''"
                    @click="go('school.users')"
                >
                    <template #icon>
                        <i class="fa-regular fa-user text-success text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
        </ul>
    </div>
</template>

<script>
import SidenavCollapse from "../../../components/sidenavs/sidenav-collapse.vue";
export default {
    name: "items",
    components: {
        SidenavCollapse,
    },
    methods: {
        getRoute() {
            return this.$route.name;
        },
        go(name) {
            this.$router.push({name: name});
        }
    }
}
</script>

<style scoped>

</style>
