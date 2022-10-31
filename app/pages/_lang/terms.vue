<template>
    <div>
        <siteHeader/>
        <div class="Page">
            <div class="Page-inner">
                <h1 class="Page-title">{{ $t('terms.title') }}</h1>
                <div
                    class="Type"
                    v-html="this.$t('terms.content')"/>
            </div>
        </div>
        <siteFooter/>
    </div>
</template>
<script>
    import siteHeader from "~/components/header.vue";
    import siteFooter from "~/components/footer.vue";
    export default {
        name: 'Terms',
        serverCacheKey () {
            return 'terms'
        },
        components: {
            siteHeader,
            siteFooter
        },
    }
</script>
