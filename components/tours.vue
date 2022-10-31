<template>
    <div
        class="Tours container"
        id="tours">
        <div class="Tours-holder">
            <h2 class="Tours-title">{{ $t('home.tours.title') }}</h2>
            <p class="Tours-description">
                {{ $t('home.tours.description') }}
            </p>
        </div>

        <ul class="ToursList">
            <li
                class="ToursList-item"
                v-for="item in $t('home.tours.list')"
                :key="item.title">
                <div class="ToursList-header">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require(`~/assets/raster/${item.images.large}`)"
                        :sources="[
                            {
                                srcset: require(`~/assets/raster/${item.images.small}`),
                                media: '--small'
                            },
                            {
                                srcset: require(`~/assets/raster/${item.images.large}`),
                                media: '--large'
                            }
                        ]"
                    />
                    <div class="ToursList-headerContent">
                        <p class="ToursList-title">{{ item.title }}</p>
                        <p
                            class="ToursList-duration"
                            v-if="item.duration">{{ item.duration }}</p>
                    </div>
                    <p
                        class="ToursList-badge"
                        v-if="item.badge"><span>{{ item.badge }}</span></p>
                </div>

                <div class="ToursList-body">
                    <p class="ToursList-description">
                        {{ item.description }}
                    </p>

                    <div class="ToursList-bottom">
                        <ul class="ToursList-features">
                            <li
                                v-for="feature in item.features"
                                :key="feature.title">
                                <svg-inline :src="svgPath(feature.icon)"/>
                                <span>{{ feature.title }}</span>
                            </li>
                        </ul>

                        <div class="ToursList-info">
                            <div class="ToursList-priceHolder">
                                <p
                                    class="ToursList-price"
                                    v-if="!user.username"
                                    v-html="item.price"/>
                                <p
                                    class="ToursList-price"
                                    v-if="user.username"
                                    v-html="item.priceLoggedIn"/>
                                <p
                                    class="ToursList-priceInfo"
                                    v-html="item.priceInfo"
                                    v-if="item.priceInfo"/>
                            </div>

                            <div class="ToursList-action">
                                <!-- <nuxt-link
                                    :to="item.path"
                                    target="_blank"
                                    @click.native="addToCart(item.title)"
                                    class="Button Button--primary">{{ item.book }}</nuxt-link> -->
                                <a :href="item.path" target="_blank" class="Button Button--primary">{{item.book}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="Tours-audio">
            <p>{{ $t('home.tours.audioTitle') }}</p>
            <v-image
                :src="require('~/assets/raster/placeholder.jpg')"
                :datasrc="require('~/assets/raster/audioguides.png')"
            />
        </div>
    </div>
</template>
<script>
    import { mapState } from "vuex";
    import vImage from '~/components/image.vue'
    export default {
        components: {
            vImage
        },
        computed: {
            ...mapState({
                user: state => state.user
            })
        },
        methods: {
            svgPath(path) {
                return require(`~/assets/vector/inline/${path}`);
            },
            addToCart(name) {
                if (process.browser) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'addToCart',
                        'ecommerce': {
                            'currencyCode': 'HRK',
                            'add': {
                                'products': [{
                                    'name': name,
                                }]
                            }
                        }
                    });
                }
            }
        }
    }
</script>


