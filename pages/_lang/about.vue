<template>
    <div>
        <siteHeader/>
        <div class="Page">
            <div class="Page-inner">
                <h1 class="Page-title">{{ $t('about.title') }}</h1>
                <div
                    class="Type"
                    v-html="this.$t('about.content')"/>
            </div>
        </div>
        <siteFooter/>
    </div>
</template>
<script>
    import siteHeader from "~/components/header.vue";
    import siteFooter from "~/components/footer.vue";
    export default {
        name: 'About',
        serverCacheKey () {
            return 'about'
        },
        components: {
            siteHeader,
            siteFooter
        },
    }
</script>
