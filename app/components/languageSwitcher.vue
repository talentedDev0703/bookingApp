<template>
    <div
        class="LanguageSwitcher"
        data-inclusive-menu>
        <button
            ref="language"
            data-inclusive-menu-opens="language"
            class="LanguageSwitcher-toggle">
            <span class="u-isHiddenVisually">Choose language</span>
            <span
                aria-hidden="true"
                v-if="$i18n.locale === 'en'"
                class="LanguageSwitcher-txt">English</span>
            <span
                aria-hidden="true"
                v-if="$i18n.locale === 'hr'"
                class="LanguageSwitcher-txt">Hrvatski</span>

            <span
                aria-hidden="true"
                class="LanguageSwitcher-arrow">
                <svg-inline
                    src="~/assets/vector/inline/arrow.svg" />
            </span>
        </button>
        <div
            id="language"
            data-inclusive-menu-from="right"
            class="LanguageSwitcher-switcher">
            <button
                :aria-checked="$i18n.locale === 'en'"
                @click="switchLanguage('en')">English</button>
            <button
                :aria-checked="$i18n.locale === 'hr'"
                @click="switchLanguage">Hrvatski</button>
        </div>
    </div>
</template>
<script>
    const MenuButton = require("inclusive-menu-button");
    export default {
        mounted() {
            this.languageSwitcher();
        },
        methods: {
            languageSwitcher() {
                const languageSwitcher = this.$refs.language;
                const languageMenuButton = new MenuButton(languageSwitcher, {
                    checkable: "one"
                });
            },
            switchLanguage(lang) {
                if (lang === "en") {
                    this.$router.push("/en");
                } else {
                    this.$router.push("/hr");
                }
            }
        }
    }
</script>

