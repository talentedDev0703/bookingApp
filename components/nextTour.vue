<template>
    <div
        class="NextTour"
        v-if="nextTour">
        <div class="NextTour-name">
            <p
                class="NextTour-subtitle"
                v-if="subtitle">{{ $t('home.nextTour.title') }}</p>
            <p class="NextTour-title">{{ nextTour.tour_name }}</p>
        </div>
        <div class="NextTour-info">
            <span>
                <svg-inline
                    src="~/assets/vector/inline/clock.svg" />
                {{ nextTour.date + ' ' + nextTour.time | moment("from") }}</span>
            <span v-if="userGeoLocation">
                <svg-inline
                    src="~/assets/vector/inline/pin.svg" />
                {{ $calculateAirDistance(nextTour.latitude, nextTour.longitude) }} {{ $t('home.nextTour.km') }}
            </span>
        </div>
        <nuxt-link
            :to="{ name: 'lang-booking-name', params: { name: nextTour.tour_name, lang: $i18n.locale }}"
            class="Button Button--primary"
            @click.native="addToCart(nextTour.tour_name)"
            exact>{{ $t('home.bookNow') }}</nuxt-link>
    </div>

</template>
<script>
    import { mapState } from "vuex";
    export default {
        props: {
            subtitle: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            ...mapState({
                nextTour: state => state.nextTours.tour,
                userGeoLocation: state => state.location.userCoords
            })
        },
        methods: {
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
    };
</script>

