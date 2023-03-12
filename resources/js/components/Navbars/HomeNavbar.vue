<template>
    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg top-0 z-index-3 position-absolute"
        :class="isBlur ? isBlur : 'shadow-none my-2 navbar-transparent'"
    >
        <div class="container-fluid p-0">
            <div style="height: 3rem">
                <router-link class="m-0 navbar-brand" to="/">
                    <img
                        src="/logo.png"
                        class="navbar-brand-img h-100"
                        alt="main_logo"
                    />
                    <span class="ms-2 font-weight-bold">Let Learn</span>
                </router-link>
            </div>
            <div>
                <button
                    class="shadow-none navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navigation"
                    aria-controls="navigation"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                <span class="mt-2 navbar-toggler-icon">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
                </button>
            </div>
            <div
                id="navigation"
                class="pt-3 pb-2 collapse navbar-collapse w-100 py-lg-0">
                <div class="d-flex align-items-center ms-md-auto">
                    <div class="input-group">
                        <span class="input-group-text text-body">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" :placeholder="'Type here to search...'"/>
                    </div>
                </div>
                <ul class="navbar-nav navbar-nav-hover">
                    <li class="mx-2 nav-item dropdown dropdown-hover">
                        <router-link :to="{ name: 'home' }" class="cursor-pointer nav-link fs-6 font-weight-bold">
                            Home
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
</template>

<script>
export default {
    name: "Navbar",
    props: {
        btnBackground: {
            type: String,
            default: "",
        },
        isBlur: {
            type: String,
            default: "",
        },
        darkMode: {
            type: Boolean,
            default: false,
        },
    },
    computed: {},
};
</script>
