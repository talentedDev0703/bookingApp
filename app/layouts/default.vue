<template>
    <div>
        <nuxt/>
        <user-nav/>
    </div>
</template>
<script>
    if (process.browser) {
        require("lazysizes");
        require("picturefill");
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.customMedia = {
            "--small": "(max-width: 700px)",
            "--medium": "(max-width: 900px)",
            "--large": "(max-width: 1200px)"
        };
    }


    import { mapMutations } from "vuex";
    import userNav from "~/components/userNav.vue";

    export default {
        components: {
            userNav
        },
        head: {
            htmlAttrs: {
                class: "fontsLoaded"
            }
        },
        mounted() {
            this.getUserLocation();
            if (this.$route.query.referrer) {
                this.setReferrer(this.$route.query.referrer);
            }
        },
        methods: {
            ...mapMutations({
                setCoords: "location/SET_USER_COORDS",
                setReferrer: "referrer/SET_REFERRER",
            }),
            getUserLocation() {
                this.$getLocation().then(coordinates => {
                    this.setCoords(coordinates);
                });
            }
        }
    };
</script>
<style lang="scss">
@import "~/assets/scss/main.scss";
</style>

