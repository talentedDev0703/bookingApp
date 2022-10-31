<template>
    <div class="Hero">
        <div class="Hero-content">

            <h1 v-html="$t('home.hero.title')"/>

            <nextTour/>
        </div>

        <!-- <div class="Tag">
            <span>from
                <strong>135kn</strong>
            </span>
        </div> -->

        <no-ssr>
            <mq-layout mq="lg+">
                <div class="Hero-video">
                    <youtube
                        :video-id="'ORgydh4SV9s'"
                        width="480"
                        height="270"
                        :player-vars="playerVars"/>
                </div>
            </mq-layout>
        </no-ssr>
    </div>
</template>
<script>

    import nextTour from "~/components/nextTour.vue";

    export default {
        components: {
            nextTour
        },
        data() {
            return {
                playerVars: {
                    autoplay: 1,
                    controls: 0,
                    loop: 1,
                    rel: 0,
                    showinfo: 0,
                    autohide: 1,
                    hd: 1,
                    mute: 1,
                    enablejsapi: 1,
                    modestbranding: 1,
                    playlist: 'ORgydh4SV9s'
                }
            }
        }
    };
</script>
