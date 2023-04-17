<template>
    <nav aria-label="breadcrumb">
        <ol
            class="px-0 pt-1 pb-0 mb-0 bg-transparent breadcrumb me-sm-6"
        >
            <li class="text-sm breadcrumb-item">
                <a :class="isNavFixed ? 'text-dark' : 'text-white'">
                    <i class="ni ni-box-2"></i>
                </a>
            </li>
            <li
                :class="`text-sm breadcrumb-item  ${
          isNavFixed ? 'text-dark' : 'text-white'
        }`"
            >
                <a
                    :class="`${isNavFixed ? 'text-dark' : 'text-white'}`"
                    href="#"
                >{{ currentDirectory }}</a
                >
            </li>
            <li
                :class="`text-sm breadcrumb-item active ${
          isNavFixed ? 'text-dark' : 'text-white'
        }`"
                aria-current="page"
            >
                {{ currentPage }}
            </li>
        </ol>
        <h6
            :class="`mb-0 font-weight-bolder ${
        isNavFixed ? 'text-dark' : 'text-white '
      }`"
        >
            {{ currentPage }}
        </h6>
    </nav>
</template>

<script>
import {mapState} from "vuex";

export default {
    name: "Breadcrumbs",
    props: {
        currentPage: {
            type: String,
            default: ""
        },
        currentDirectory: {
            type: String,
            default: ""
        }
    },
    computed: {
        ...mapState({
            isNavFixed: state => state.config.isNavFixed,
        })
    }
};
</script>
