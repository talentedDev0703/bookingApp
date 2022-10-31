<template>
    <div class="BoxedLayout Login">

        <p class="BoxedLayout-siteTitle"><nuxt-link :to="{ name: 'lang', params: { lang: $i18n.locale }}">Adriatic Sunsets</nuxt-link></p>

        <div class="BoxedLayout-main">
            <template v-if="reports && user.role === 'Administrator'">
                <h1 class="BoxedLayout-mainTitle">{{ reports.company }}</h1>
                <p
                    class="BoxedLayout-subTitle">{{ $t('admin.reportsFor') }} {{ reports.current_month_name }}</p>

                <table class="Table">
                    <thead>
                        <tr>
                            <th class="u-textLeft">{{ $t('admin.reports.seller') }}</th>
                            <th class="u-textRight">{{ $t('admin.reports.tours') }}</th>
                            <th class="u-textRight">{{ $t('admin.reports.price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in reports.users"
                            :key="user.id">
                            <td>
                                {{ user.name }}
                            </td>
                            <td class="u-textRight">
                                {{ user.tours }}
                            </td>
                            <td class="u-textRight">
                                {{ user.value }} kn
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                colspan="3"
                                class="u-textRight">
                                <span>{{ $t('admin.reports.total') }}</span>
                                <strong>{{ reports.current_month_total }} kn</strong>
                            </td>
                        </tr>
                    </tfoot>
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
            if (this.user.role === 'Administrator'){
                this.getReports({id: this.user.company})
            }
        },
        computed: {
            ...mapState({
                reports: state => state.admin.reports,
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
                getReports: "admin/GET_REPORTS",
            })
        }
    }
</script>
