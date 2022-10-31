<template>
    <header>

        <div class="Logo">
            <div class="LogoContainer">
                <nuxt-link
                    :to="{ name: 'lang', params: { lang: $i18n.locale }}"
                    :title="$t('home.logo')">
                    <img
                        src="~/assets/vector/bg/logo.svg"
                        alt="">
                </nuxt-link>
            </div>
        </div>

        <nav
            aria-label="Primary navigation"
            class="MainNav">
            <button
                aria-expanded="false"
                aria-controls="primary-menu"
                class="MainNav-toggle"
                ref="hamby"
                @click="hamby">
                <span class="u-isHiddenVisually">Menu</span>
                <span aria-hidden="true">
                    <svg-inline
                        src="~/assets/vector/inline/hamby.svg" />
                </span>
            </button>

            <ul
                id="primary-menu"
                ref="primaryMenu"
                class="MainNav-list">
                <li
                    v-for="link in $t('primaryNav')"
                    :key="link.title">
                    <a
                        :href="link.href"
                        v-smooth-scroll
                        @click="hamby($event, 'close')">{{ link.title }}</a>
                </li>
                <li>
                    <nuxt-link
                        :to="{ name: 'lang-login', params: { lang: $i18n.locale }}"
                    >B2B login</nuxt-link>
                </li>
            </ul>
        </nav>

    </header>
</template>
<script>
    export default {
        name: 'Header',
        serverCacheKey () {
            return 'header-new'
        },
        data() {
            return {
                mobileNavOpened: false
            }
        },
        mounted(){
            if (process.browser && this.$mq === "sm" || this.$mq === "sm2"){
                this.$refs.primaryMenu.style.height = window.innerHeight + 'px';
            }
        },
        watch: {
            mq: function() {
                if (this.$mq === "sm2" || this.$mq === "sm"){
                    this.$refs.primaryMenu.style.height = window.innerHeight + 'px';
                } else {
                    this.$refs.primaryMenu.removeAttribute('style');
                }
            }
        },
        computed: {
            mq() {
                return this.$mq;
            }
        },
        methods: {
            hamby(event, action) {

                let expanded =
                    event.currentTarget.getAttribute("aria-expanded") === "true" || false;
                let menu = event.currentTarget.nextElementSibling || this.$refs.primaryMenu;

                if (action !== 'close') {
                    this.$refs.hamby.setAttribute("aria-expanded", !expanded);
                    this.$refs.hamby.classList.toggle("is-active");

                    if (!expanded) {
                        menu.classList.toggle("is-flex");
                        setTimeout(function() {
                            menu.classList.toggle("is-active");
                            document.documentElement.classList.add('disable-scroll');
                        });
                    } else {
                        menu.classList.toggle("is-active");
                        setTimeout(function() {
                            menu.classList.toggle("is-flex");
                            document.documentElement.classList.remove('disable-scroll');
                        }, 200);
                    }
                } else {
                    if (document.documentElement.classList.contains('disable-scroll')) {
                        menu.classList.remove("is-active");
                        setTimeout(() => {
                            menu.classList.remove("is-flex");
                            this.$refs.hamby.setAttribute("aria-expanded", 'false');
                            this.$refs.hamby.classList.remove("is-active");
                            document.documentElement.classList.remove('disable-scroll');
                        }, 200);
                    }
                }

            }
        }
    };
</script>
