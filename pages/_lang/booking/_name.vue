<template>
    <div class="BoxedLayout">
        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <bookingProgress
            :is-b2b="isB2B"
            :current-step="currentStep"
            :available-steps="availableSteps"
            :handle-step-change="stepChange" />

        <div class="BoxedLayout-main">
            <h1 class="BoxedLayout-mainTitle">{{ $t('booking.mainTitle') }} <span>{{ this.$route.params.name }}</span></h1>

            <p
                class="BoxedLayout-subTitle"
                v-if="currentStep >= 2 && !this.isPrivate">{{ date | moment('D.M.Y') }}@{{ parseInt(time, 10) | moment('H:m') }} {{ $t('booking.from') }} {{ pickup }}</p>

            <p
                class="BoxedLayout-subTitle"
                v-if="currentStep >= 2 && this.isPrivate">{{ date | moment('D.M.Y') }}@{{ time }}</p>

            <form
                :action="this.formAction"
                class="Booking-form"
                method="POST"
                @submit="onSubmit">

                <fieldset :class="{'u-isHidden' : currentStep > 1}">

                    <template v-if="!this.isPrivate">
                        <div
                            class="Field"
                            :class="{'has-error': errors.first('pickup')}">
                            <label for="pickup">{{ $t('booking.pickup') }}</label>

                            <div class="Field-holder">
                                <select
                                    name="pickup"
                                    id="pickup"
                                    v-model="pickup"
                                    @change="getDatesFromApi"
                                    v-validate="'required'"
                                >
                                    <option
                                        value=""
                                    >{{ $t('booking.pickupDefaultOption') }}</option>
                                    <option
                                        v-for="pickup in pickupPoints"
                                        :key="pickup.name"
                                        :value="pickup.name">
                                        {{ pickup.name }}
                                        <template v-if="userGeoLocation">
                                            - {{ $calculateAirDistance(pickup.latitude, pickup.longitude) }} {{ $t('home.nextTour.km') }}
                                        </template>
                                    </option>
                                </select>
                                <svg-inline src="~/assets/vector/inline/bookingPin.svg"/>
                            </div>

                            <span class="Field-error">{{ errors.first('pickup') }}</span>
                        </div>

                        <div
                            class="Field"
                            :class="{'has-error': errors.first('date')}">
                            <label for="date">{{ $t('booking.date') }}</label>
                            <div class="Field-holder">
                                <select
                                    name="date"
                                    id="date"
                                    v-model="date"
                                    :disabled="!dates"
                                    @change="getTimesFromApi"
                                    v-validate="'required'"
                                >
                                    <option
                                        value=""
                                    >{{ $t('booking.dateDefaultOption') }}</option>
                                    <option
                                        v-for="date in dates"
                                        :key="date"
                                        :value="date">{{ date | moment('D.M.Y') }}</option>
                                </select>
                                <svg-inline src="~/assets/vector/inline/bookingDate.svg"/>
                            </div>

                            <span class="Field-error">{{ errors.first('date') }}</span>
                        </div>

                        <div
                            class="Field"
                            :class="{'has-error': errors.first('time')}">
                            <label for="time">{{ $t('booking.time') }}</label>

                            <div class="Field-holder">
                                <select
                                    name="time"
                                    id="time"
                                    v-model="time"
                                    :disabled="!times"
                                    v-validate="'required'"
                                >
                                    <option
                                        value=""
                                    >{{ $t('booking.timeDefaultOption') }}</option>
                                    <option
                                        v-for="time in times"
                                        :key="time.time"
                                        :value="time.timestamp">{{ time.time }}</option>
                                </select>
                                <svg-inline src="~/assets/vector/inline/bookingClock.svg"/>
                            </div>

                            <span class="Field-error">{{ errors.first('time') }}</span>
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="Field"
                            :class="{'has-error': errors.first('date')}">
                            <label for="date">{{ $t('booking.date') }}</label>
                            <div class="Field-holder">
                                <datepicker
                                    v-model="date"
                                    name="date"
                                    id="date"
                                    format="yyyy-MM-dd"
                                    autocomplete="off"
                                    v-validate="'required'"/>
                                <svg-inline src="~/assets/vector/inline/bookingDate.svg"/>
                            </div>

                            <span class="Field-error">{{ errors.first('date') }}</span>
                        </div>

                        <div
                            class="Field"
                            :class="{'has-error': errors.first('time')}">
                            <label for="time">{{ $t('booking.time') }}</label>

                            <div class="Field-holder">
                                <input
                                    name="time"
                                    id="time"
                                    v-model="time"
                                    v-validate="'required'"
                                >
                                <svg-inline src="~/assets/vector/inline/bookingClock.svg"/>
                            </div>

                            <span class="Field-error">{{ errors.first('time') }}</span>
                        </div>
                    </template>

                </fieldset>

                <fieldset v-if="currentStep === 2">

                    <template
                        v-if="isB2B">
                        <div class="Field Booking-capacity Booking-capacity--info">
                            {{ $t('booking.totalCapacity1') }} {{ tourInfo.available_capacity }}.
                        </div>
                    </template>

                    <template v-if="this.$route.params.name !== 'Private rental'">
                        <div class="Field-group">
                            <div
                                class="Field"
                                :class="{'has-error': errors.first('adults')}">
                                <label for="adults">{{ $t('booking.adults') }}</label>

                                <div class="Field-holder">
                                    <input
                                        type="number"
                                        name="adults"
                                        id="adults"
                                        min="1"
                                        v-model="adults"
                                        v-validate="'required|numeric'"
                                    >
                                </div>

                                <span class="Field-error">{{ errors.first('adults') }}</span>
                            </div>
                            <div
                                class="Field"
                                :class="{'has-error': errors.first('kids')}">
                                <label for="kids">{{ $t('booking.kids') }}</label>

                                <div class="Field-holder">
                                    <input
                                        type="number"
                                        name="kids"
                                        id="kids"
                                        min="0"
                                        v-model="kids"
                                        v-validate="'required|numeric'"
                                    >
                                </div>

                                <span class="Field-error">{{ errors.first('kids') }}</span>
                            </div>
                            <div
                                class="Field"
                                :class="{'has-error': errors.first('infants')}">
                                <label for="infants">{{ $t('booking.infants') }}</label>

                                <div class="Field-holder">
                                    <input
                                        type="number"
                                        name="infants"
                                        id="infants"
                                        min="0"
                                        v-model="infants"
                                        v-validate="'required|numeric'"
                                    >
                                </div>

                                <span class="Field-error">{{ errors.first('infants') }}</span>
                            </div>
                        </div>
                    </template>

                    <template
                        v-if="calculateCapacity <= 0">
                        <div class="Field Booking-capacity Booking-capacity--warning">
                            {{ $t('booking.totalCapacity1') }} {{ tourInfo.available_capacity }}. {{ $t('booking.totalCapacity2') }}
                        </div>
                    </template>

                    <div
                        class="Field"
                        :class="{'has-error': errors.first('name')}">
                        <label for="name">{{ $t('booking.name') }}</label>

                        <div class="Field-holder">
                            <input
                                type="text"
                                name="name"
                                id="name"
                                v-model="namesurname"
                                v-validate="'required'"
                                :disabled="calculateCapacity <= 0"
                            >
                        </div>

                        <span class="Field-error">{{ errors.first('name') }}</span>
                    </div>

                    <div
                        class="Field"
                        :class="{'has-error': errors.first('email')}">
                        <label for="email">{{ $t('booking.email') }}</label>

                        <div class="Field-holder">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                v-model="email"
                                v-validate="'required|email'"
                                :disabled="calculateCapacity <= 0"
                            >
                        </div>

                        <span class="Field-error">{{ errors.first('email') }}</span>
                    </div>

                    <div
                        class="Field">
                        <label for="phone">{{ $t('booking.phone') }}</label>

                        <div class="Field-holder">
                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                v-model="phone"
                                :disabled="calculateCapacity <= 0"
                            >
                        </div>
                    </div>

                    <div
                        class="Field is-terms">
                        <label
                            for="terms">

                            <input
                                type="checkbox"
                                name="terms"
                                id="terms"
                                v-model="terms"
                                v-validate="'required'"
                                :disabled="calculateCapacity <= 0"
                            >

                            {{ $t('booking.accept') }}

                            <nuxt-link
                                :to="{ name: 'lang-terms', params: { lang: $i18n.locale }}"
                                target="_blank"
                                rel="noopener">
                                {{ $t('booking.terms') }}
                            </nuxt-link>
                        </label>

                        <span class="Field-error">{{ errors.first('terms') }}</span>
                    </div>

                </fieldset>

                <input
                    type="hidden"
                    name="id"
                    :value="id"
                    v-if="!isPrivate">

                <input
                    type="hidden"
                    name="b2b"
                    value="1"
                    v-if="isB2B">

                <button
                    class="Button Button--booking"
                    :class="{'is-disabled': calculateCapacity <= 0}"
                >
                    <template v-if="currentStep === 1">{{ $t('booking.continue') }}</template>
                    <template v-if="currentStep === 2">
                        <template v-if="!isB2B">{{ $t('booking.payNow') }}</template>
                        <template v-if="isB2B">{{ $t('booking.b2bFinish') }}</template>
                        <template v-if="calculateTotal"> - <strong>{{ calculateTotal }}kn</strong> total</template>
                </template></button>
            </form>
        </div>

        <div class="Booking-footer">
            <nuxt-link
                :to="{ name: 'lang-terms', params: { lang: $i18n.locale }}"
                target="_blank"
                rel="noopener">{{ $t('footer.terms') }}</nuxt-link>
            <div class="Cards">
                <a
                    href="http://www.visa.com.hr"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/visa_fc.png')"
                        alt=""
                    />
                </a>
                <a
                    href="http://www.mastercard.com"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/mc_acc_opt_70_1x.png')"
                        alt=""
                    />
                </a>
                <a
                    href="http://www.maestrocard.com"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/ms_acc_opt_70_1x.png')"
                        alt=""
                    />
                </a>
                <a
                    href="https://www.diners.com.hr/Pogodnosti-i-usluge/MasterCard- SecureCode.html?Ym5cMzQsY2FyZFR5cGVcMSxwXDc3"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/DCI-Logo-horz.jpg')"
                        alt=""
                    />
                </a>
                <a
                    href="http://www.discover.com"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/DISCOVER.jpg')"
                        alt=""
                    />
                </a>
                <a
                    href="http://www.americanexpress.hr"
                    target="_blank"
                    rel="noopener">
                    <v-image
                        :src="require('~/assets/raster/placeholder.jpg')"
                        :datasrc="require('~/assets/raster/AmericanExpress50.jpg')"
                        alt=""
                    />
                </a>
                <v-image
                    :src="require('~/assets/raster/placeholder.jpg')"
                    :datasrc="require('~/assets/raster/verified_by_visa.jpg')"
                    alt=""
                    onclick="window.open('http://www.pbzcard.hr/media/53824/vbv_en.html','prozoren','width=750,height=590')"
                />
                <v-image
                    :src="require('~/assets/raster/placeholder.jpg')"
                    :datasrc="require('~/assets/raster/mastercard_securecode.gif')"
                    alt=""
                    onclick="window.open('http://www.pbzcard.hr/media/53818/mcsc_en.html','prozoren','width=750,height=590')"
                />
                <v-image
                    :src="require('~/assets/raster/placeholder.jpg')"
                    :datasrc="require('~/assets/raster/logo_sigurna_kupnja_2_1.png')"
                    alt=""
                    onclick="window.open('https://www.erstecardclub.hr/hr/pogodnosti/visa-mastercard-pogodnosti/sigurna-i-brza-kupnja-na-internetu-zahvaljujuci-3d-standardu-vbv','prozoren','width=750,height=590')"
                />
                <v-image
                    :src="require('~/assets/raster/placeholder.jpg')"
                    :datasrc="require('~/assets/raster/ax_guarenteed.gif')"
                    alt=""
                    onclick="window.open('http://www.pbzcard.hr/en/online-services/secure-online-purchasing-guarantee','prozoren','width=750,height=590')"
                />
            </div>
        </div>
    </div>
</template>
<script>
    import { mapState } from "vuex";
    import { mapActions } from "vuex";
    import { mapMutations } from "vuex";

    import Datepicker from 'vuejs-datepicker';

    import bookingProgress from "~/components/bookingProgress.vue";
    import vImage from '~/components/image.vue'


    export default {
        data() {
            return {
                isPrivate: this.$route.params.name === 'Private rental',
                id: 0,
                pickup: "",
                date: "",
                time: '',
                adults: 2,
                kids: 0,
                infants: 0,
                email: "",
                phone: "",
                total: '',
                terms: false,
                namesurname: ""
            };
        },
        components: {
            bookingProgress,
            Datepicker,
            vImage
        },
        head: {
            htmlAttrs: {
                class: "is-boxed-layout fontsLoaded"
            }
        },
        watch: {
            currentStep: function (newStep, oldStep) {
                if (process.browser) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        'event': 'checkout',
                        'ecommerce': {
                            'checkout': {
                                'actionField': {'step': newStep},
                                'products': [{
                                    'name': this.name,
                                    'date': this.date,
                                    'time': this.time,
                                }]
                            }
                        }
                    });
                }
            }
        },
        computed: {
            formAction() {
                return this.isPrivate ? `${this.bookUrl}private-rental` : `${this.bookUrl}book-tour`;
            },
            calculateTotal() {
                const total = (parseInt(this.tourInfo.price, 10) * this.adults) + (parseInt(this.tourInfo.price_kids, 10) * this.kids) + (parseInt(this.tourInfo.price_infants, 10) * this.infants);
                this.setTotal(total);
                return total;
            },

            calculateCapacity() {
                return this.tourInfo.available_capacity - this.adults - this.kids
            },

            ...mapState({
                bookUrl: state => state.bookUrl,
                pickupPoints: state => state.booking.pickupPoints,
                dates: state => state.booking.dates,
                times: state => state.booking.times,
                name: state => state.booking.name,
                currentStep: state => state.booking.currentStep,
                availableSteps: state => state.booking.availableSteps,
                tourID: state => state.booking.tourID,
                tourInfo: state => state.booking.tourInfo,
                userGeoLocation: state => state.location.userCoords
            })
        },
        beforeCreate(){
            this.isB2B = !!(this.$cookies.get('b2b')) || !!(this.$cookies.get('auth'));
        },
        created() {
            if (!this.isPrivate){
                this.getInitialPickupPoints(this.$route.params.name);
            }

            if (process.browser) {
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    'event': 'checkout',
                    'ecommerce': {
                        'checkout': {
                            'actionField': {'step': 1},
                            'products': [{
                                'name': this.$route.params.name,
                            }]
                        }
                    }
                });
            }

        },
        methods: {
            setTotal(newValue) {
                this.total = newValue;
            },
            ...mapActions({
                getInitialPickupPoints: "booking/GET_PICKUP_POINTS",
                getDates: "booking/GET_DATES",
                getTimes: "booking/GET_TIMES",
                getTourID: "booking/GET_TOUR_ID",
                getTour: "booking/GET_TOUR"
            }),
            ...mapMutations({
                stepChange: "booking/STEP_CHANGE",
                availableStepChange: "booking/AVAILABLE_STEP_CHANGE"
            }),
            getDatesFromApi() {
                this.getDates({ pickup: this.pickup, tour: this.name });
            },
            getTimesFromApi() {
                this.getTimes({
                    pickup: this.pickup,
                    tour: this.name,
                    date: this.date
                });
            },
            getTourIDFromApi() {
                this.getTourID({
                    pickup: this.pickup,
                    tour: this.name,
                    date: this.date,
                    time: this.time
                }).then(() => {
                    this.id = this.tourID;
                    this.getTour({id: this.tourID});
                }).catch((err) => {
                    // sudbo kleta
                })
            },
            onSubmit(e) {
                this.$validator
                    .validateAll()
                    .then(() => {
                        if (!this.errors.any()) {
                            if (this.currentStep === 1) {
                                e.preventDefault();
                                this.getTourIDFromApi();
                                this.stepChange(this.currentStep + 1);
                                this.availableStepChange(this.availableSteps + 1);
                            }
                        } else {
                            e.preventDefault();
                        }
                    })
                    .catch(() => {
                        return false;
                });
            }
        }
    };
</script>

