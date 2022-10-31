<template>
    <div class="BoxedLayout">
        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <bookingProgress
            :current-step="4"
            :available-steps="4" />

        <div class="BoxedLayout-main">
            <h1 class="BoxedLayout-mainTitle">{{ $t('booking.success.cancel') }}</h1>

            <p class="BoxedLayout-back">{{ $t('booking.continueTo') }} <nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">{{ $t('booking.homepage') }}</nuxt-link></p>
        </div>
    </div>
</template>
<script>
    import bookingProgress from "~/components/bookingProgress.vue";
    export default {
        components: {
            bookingProgress
        },
        head: {
            htmlAttrs: {
                class: "is-boxed-layout fontsLoaded"
            }
        }
    }
</script>

