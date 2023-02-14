<template>
    <div id="sidenav-collapse-main" class="collapse navbar-collapse w-auto h-auto h-100">
        <ul class="navbar-nav">
            <li class="nav-item">
                <sidenav-collapse collapse-ref="dashboardsExamples" nav-text="Dashboards"
                    :class="getRoute() === 'dashboard' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-solid fa-chart-line text-primary text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <sidenav-item :to="{ name: 'auth.login' }" mini-icon="L" text="Landing" />
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    PAGES
                </h6>
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
