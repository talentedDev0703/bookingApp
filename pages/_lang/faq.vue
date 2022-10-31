<template>
    <div>
        <siteHeader/>
        <div class="Page">
            <div class="Page-inner">
                <h1 class="Page-title">{{ $t('faq.title') }}</h1>
                <div
                    class="Type"
                    v-html="this.$t('faq.content')"/>
            </div>
        </div>
        <siteFooter/>
    </div>
</template>
<script>
    import siteHeader from "~/components/header.vue";
    import siteFooter from "~/components/footer.vue";
    export default {
        name: 'Faq',
        serverCacheKey () {
            return 'faq'
        },
        components: {
            siteHeader,
            siteFooter
        },
    }
</script>
