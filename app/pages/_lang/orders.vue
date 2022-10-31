<template>
    <div class="BoxedLayout Orders">

        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <div class="BoxedLayout-main">

            <template v-if="orders">
                <h1 class="BoxedLayout-mainTitle">{{ $t('admin.orders.title') }}</h1>

                <table class="Table">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.orders.buyer') }}</th>
                            <th>{{ $t('admin.orders.tour') }}</th>
                            <th>{{ $t('admin.orders.pickupPoint') }}</th>
                            <th>{{ $t('admin.orders.cancel') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="order in orders"
                            :key="order.id"
                            :class="{'is-cancelled':
                            order.cancelled}">
                            <td>
                                {{ order.buyer_name }}
                            </td>
                            <td>
                                {{ order.tour_name }}
                            </td>
                            <td>
                                {{ order.pickup_point }} - {{ order.pickup_date }}@{{ order.pickup_time }}
                            </td>
                            <td>
                                <button
                                    class="Button Button--link is-negative"
                                    type="button"
                                    @click="removeOrder({orderID: order.id, companyID: user.company})"
                                    v-if="order.cancel_available">{{ $t('admin.orders.cancel') }}</button>
                                &nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>

            </template>

            <p class="BoxedLayout-back">{{ $t('booking.continueTo') }} <nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">{{ $t('booking.homepage') }}</nuxt-link></p>
        </div>
        <user-nav/>
    </div>
</template>

<script>
    import userNav from "~/components/userNav.vue";
    import { mapState } from "vuex";
    import { mapActions } from "vuex";

    export default {
        middleware: 'auth',
        components: {
            userNav
        },
        created() {
            this.getOrders(this.user.company)
        },
        computed: {
            ...mapState({
                orders: state => state.orders.orders,
                user: state => state.user
            })
        },
        head: {
            htmlAttrs: {
                class: "is-boxed-layout fontsLoaded"
            }
        },
        methods: {
            ...mapActions({
                getOrders: "orders/GET_ORDERS",
                removeOrder: "orders/REMOVE_ORDER",
            })
        }
    }
</script>
