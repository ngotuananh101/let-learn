<template>
    <div class="card position-sticky top-1 mt-4">
        <ul class="nav flex-column bg-white border-radius-lg p-3">
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#basic-info">
                    <i class="ni ni-books me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Basic Info</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#notification">
                    <i class="a-sharp fa-solid fa-bell me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Notification</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "SideNav",
};
</script>
