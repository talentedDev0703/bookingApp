<template>
    <span>
        <picture :class="pictureClass">
            <source
                v-for="source in sources"
                :key="source.media"
                :data-srcset="source.srcset"
                :media="source.media">
            <img
                :src="src"
                :class="imageClass"
                :data-src="datasrc"
                :alt="alt"
                :data-flickity-lazyload="dataflickitylazyload"
            >
        </picture>
    </span>
</template>
<script>
    export default {
        props: {
            sources: {
                type: Array,
                default: () => {}
            },
            imageClass: {
                type: String,
                default: 'lazyload'
            },
            pictureClass: {
                type: String,
                default: ''
            },
            src: {
                type: String,
                required: true
            },
            datasrc: {
                type: String,
                default: ''
            },
            dataflickitylazyload: {
                type: String,
                default: ''
            },
            alt: {
                type: String,
                default: ''
            }
        }
    }
</script>

