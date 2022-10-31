<template>
    <div
        class="Gallery"
        id="gallery">
        <div class="container">

            <div class="Gallery-holder">
                <h2 class="Gallery-title">{{ $t('home.gallery.title') }}</h2>
                <p class="Gallery-description">
                    {{ $t('home.gallery.description') }}
                </p>
            </div>

            <no-ssr ref="main">
                <flickity
                    class="Gallery-main"
                    ref="flickity"
                    :options="flickity.main.options">
                    <div
                        class="Gallery-cell"
                        v-for="n in 14"
                        :key="n">
                        <v-image
                            :src="require('~/assets/raster/gallery-placeholder.jpg')"
                            :datasrc="require(`~/assets/raster/gallery-${n}-main.jpg`)"
                            :dataflickitylazyload="require(`~/assets/raster/gallery-${n}-main.jpg`)"
                            :sources="[
                                {
                                    srcset: require(`~/assets/raster/gallery-${n}-small.jpg`),
                                    media: '--small'
                                },
                                {
                                    srcset: require(`~/assets/raster/gallery-${n}-main.jpg`),
                                    media: '--large'
                                }
                        ]"/>
                    </div>
                    <span class="Gallery-counter">{{ flickity.main.currentIndex }} / {{ flickity.main.cellLength }}</span>
                </flickity>
            </no-ssr>
            <no-ssr>
                <mq-layout mq="md+">
                    <flickity
                        :options="flickity.nav.options">
                        <div
                            class="Gallery-nav"
                            v-for="n in 14"
                            :key="n"
                        >
                            <img
                                :src="require(`~/assets/raster/gallery-${n}-thumb.jpg`)"
                                alt=""
                                aria-hidden="true">
                        </div>
                    </flickity>
                </mq-layout>
            </no-ssr>
        </div>
    </div>
</template>
<script>
    import vImage from '~/components/image.vue'
    export default {
        data() {
            return {
                flickity: {
                    main: {
                        currentIndex: 1,
                        cellLength: 0,
                        options: {
                            pageDots: false,
                            wrapAround: true,
                            arrowShape: 'M32 14 69 51.2491298 33.488239 87',
                            imagesLoaded: true,
                            cellSelector: '.Gallery-cell',
                            lazyLoad: true
                        }
                    },
                    nav: {
                        options: {
                            asNavFor: '.Gallery-main',
                            pageDots: false,
                            prevNextButtons: false,
                            imagesLoaded: true,
                            cellAlign: 'left',
                            contain: true
                        }
                    }
                }
            };
        },
        mounted(){
            this.$nextTick(() => {
                this.$refs.flickity.on('select', () => {
                    this.flickity.main.currentIndex = this.$refs.flickity.selectedIndex() + 1;
                });

                this.flickity.main.cellLength = this.$refs.flickity.cells().length;
            })
        },
        components: {
            vImage
        }
    };
</script>

