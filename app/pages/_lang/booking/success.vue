<template>
    <div class="BoxedLayout Booking">
        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <bookingProgress
            v-if="data.tour_name"
            :current-step="4"
            :available-steps="4" />

        <div
            class="BoxedLayout-main">

            <template
                v-if="data.tour_name">
                <h1 class="BoxedLayout-mainTitle">{{ $t('booking.success.mainTitle') }} <span>{{ data.tour_name }}</span></h1>

                <p class="BoxedLayout-subTitle">{{ data.date }} @ {{ data.time }} {{ $t('booking.from') }} {{ data.pickup_point }}</p>

                <p
                    class="Booking-voucher"
                    v-if="!data.b2b">{{ $t('booking.success.voucher') }}</p>

                <template v-if="data.b2b">
                    <a
                        :href="data.voucher_url"
                        class="Booking-voucher">{{ $t('booking.success.voucherB2b') }}</a>
                </template>
            </template>

            <template
                v-else-if="isPrivate">
                <h1 class="BoxedLayout-mainTitle">{{ $t('booking.success.mainTitle') }} <span>{{ $t('booking.success.privateRental') }}</span></h1>

                <p class="Booking-voucher">{{ $t('booking.success.privateInfo') }}</p>
            </template>

            <template
                v-else-if="data">
                <h1 class="BoxedLayout-mainTitle">{{ data }}</h1>
            </template>

            <p class="BoxedLayout-back">{{ $t('booking.continueTo') }} <nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">{{ $t('booking.homepage') }}</nuxt-link></p>

        </div>
    </div>
</template>
<script>
    import bookingProgress from "~/components/bookingProgress.vue";
    export default {
        data() {
            return {
                data: '',
                isPrivate: this.$route.query.order_number === 'private',
                revenue: 10
            };
        },
        components: {
            bookingProgress
        },
        head: {
            htmlAttrs: {
                class: "is-boxed-layout fontsLoaded"
            }
        },
        async mounted() {
            if (!this.isPrivate){

                this.$nextTick(() => {
                    this.$nuxt.$loading.start();
                });

                this.data = await this.$axios.$get(`success?order_number=${this.$route.query.order_number}`);

                if (process.browser && this.data.revenue) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'purchase',
                        'ecommerce': {
                            'purchase': {
                                'actionField': {
                                    'id': this.$route.query.order_number,
                                    'revenue': this.data.revenue,
                                },
                                'products': [{
                                    'name': this.data.tour_name,
                                }]
                            }
                        }
                    });
                }

                this.$nuxt.$loading.finish();
            }


        }
    }
</script>

