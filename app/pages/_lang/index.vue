<template>
    <div>

        <siteHeader/>
        <hero/>

        <div
            class="NextTours"
            v-if="nextTour">
            <p class="NextTours-title">Next tour</p>
            <nextTour
                :subtitle="false"/>
        </div>

        <about/>
        <gallery/>
        <roadmap/>
        <tours/>
        <userphotos/>
        <siteFooter/>

    </div>
</template>

<script>
    import siteHeader from "~/components/header.vue";
    import hero from "~/components/hero.vue";
    import nextTour from "~/components/nextTour.vue";
    import about from "~/components/about.vue";
    import gallery from "~/components/gallery.vue";
    import roadmap from "~/components/roadmap.vue";
    import tours from "~/components/tours.vue";
    import userphotos from "~/components/userphotos.vue";
    import siteFooter from "~/components/footer.vue";

    import { mapMutations } from "vuex";
    import { mapState } from "vuex";

    export default {
        components: {
            siteHeader,
            hero,
            nextTour,
            about,
            gallery,
            roadmap,
            tours,
            userphotos,
            siteFooter
        },
        beforeMount(){
            this.resetBooking();
            console.log("mo");
        },
        methods: {
            ...mapMutations({
                resetBooking: "booking/RESET_STATE",
            }),
        },
        computed: {
            ...mapState({
                nextTour: state => state.nextTours.tour
            })
        }
    };
</script>
