<template>
    <div id="basic-info" class="card mt-4">
        <div class="card-header">
            <h5>Basic Info</h5>
        </div>
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-6">
                    <label class="form-label mt-2">Name</label>
                    <argon-input id="name" type="text" name="name"
                                 placeholder="School name, like 'FPT University'" required
                                 @change="changeInput"/>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">Website</label>
                    <argon-input id="website" type="text" name="website"
                                 placeholder="School website, like 'https://daihoc.fpt.edu.vn'"
                                 required @change="changeInput"/>
                </div>
                <div class="col-12">
                    <label class="form-label mt-2">Email domain (split by ',')</label>
                    <argon-input id="email" type="text" name="email"
                                 placeholder="School email domain, like 'fpt.edu.vn'"
                                 required @change="changeInput"/>
                </div>
                <div class="col-md-4 col-6">
                    <label class="form-label mt-2">Phone</label>
                    <argon-input id="phone" type="text" name="phone" placeholder="02473001866"
                                 required @change="changeInput"/>
                </div>
                <div class="col-md-8 col-6">
                    <label class="form-label mt-2">Address</label>
                    <argon-input id="address" type="text" name="address"
                                 placeholder="School address" required @change="changeInput"/>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">Country</label>
                    <select
                        id="select_country"
                        name="country"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">State</label>
                    <select
                        id="select_state"
                        name="state"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">City</label>
                    <select
                        id="select_city"
                        name="city"
                        class="form-control"
                    >
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label mt-2">Zip</label>
                    <argon-input id="zip" type="number" name="zip" placeholder="Zip code" required
                                 @change="changeInput"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Choices from "choices.js";
import ArgonInput from "@/components/Argons/ArgonInput.vue";
import {mapActions} from "vuex";
import {country_data} from '@/helpers/country_data.js';

export default {
    name: "Content",
    components: {
        ArgonInput,
    },
    data() {
        return {
            country: null,
            state: null,
            city: null,
            school: {
                name: '',
                website: '',
                email: '',
                phone: '',
                address: '',
                state: '',
                city: '',
                country: '',
                zip: '',
                logo: '',
            },
            select_country: null,
            select_city: null,
            select_state: null,
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.country = country_data.getCountry();
            this.select_country = new Choices('#select_country', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
            });
            this.select_state = new Choices('#select_state', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
            });
            this.select_city = new Choices('#select_city', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
            });
            this.select_country.setChoices(this.country, 'isoCode', 'name', true);
            let data = this.getSchool(this.$route.params.id);
            console.log(data);
        });
    },
    beforeUnmount() {
        this.select_country.destroy();
        this.select_state.destroy();
        this.select_city.destroy();
    },
    methods: {
        changeInput(e){
            console.log(e.target.value);
        },
        ...mapActions('adminSchool', ['getSchool']),
    }
}
</script>

<style scoped>

</style>
