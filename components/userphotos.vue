<template>
    <div
        style="display:none"
        class="Userphotos"
        id="social">
        <div class="container">
            <div class="Userphotos-holder">
                <p class="Userphotos-description">
                    {{ $t('home.userphotos.title') }}
                </p>
                <h2 class="Userphotos-title">{{ $t('home.userphotos.hashtag') }}</h2>
            </div>

            <div class="Userphotos-container">
                <template v-if="grams">
                    <div class="Userphotos-photos">
                        <div
                            v-for="gram in grams"
                            :key="gram.id">
                            <a :href="gram.link">
                                <v-image
                                    :src="require('~/assets/raster/placeholder.jpg')"
                                    :datasrc="gram.images.standard_resolution.url"
                                    :sources="[
                                        {
                                            srcset: gram.images.low_resolution.url,
                                            media: '--small'
                                        },
                                        {
                                            srcset: gram.images.standard_resolution.url,
                                            media: '--large'
                                        }
                                    ]"
                                    :alt="gram.caption.text"
                                />
                            </a>
                        </div>
                    </div>
                </template>

                <!-- <div
                    v-if="error"
                    class="error">{{ $t('home.userphotos.error') }}</div> -->

                <button
                    v-if="next_url"
                    type="button"
                    class="Button Button--primary"
                    @click="getMoreGrams">{{ $t('home.loadMore') }}</button>
            </div>
        </div>
    </div>
</template>
<script>
    import vImage from '~/components/image.vue'
    export default {
        components: {
            vImage
        },
        data(){
            return {
                grams: [],
                next_url: "",
                error: false
            }
        },
        async beforeMount() {
            try {
                const data = await this.$axios.$get('https://api.instagram.com/v1/users/self/media/recent/?access_token=7744599223.1677ed0.96d42d650504442ea694665a22840c63&count=9');
                this.grams = data.data;
                this.next_url = data.pagination.next_url || false;
            } catch (error) {
                this.error = true
            }
        },
        methods: {
            async getMoreGrams() {
                try {
                    const data = await this.$axios.$get(this.next_url);
                    this.grams = this.grams.concat(data.data);
                    this.next_url = data.pagination.next_url || false;
                } catch (error) {
                    this.error = true
                }
            }
        }
    }
</script>
