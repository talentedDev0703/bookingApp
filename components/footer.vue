<template>
    <footer
        class="Footer"
        id="contact">
        <div class="Footer-holder container">

            <div class="Footer-section Footer--sectionContact">
                <p class="Footer-sectionTitle">
                    {{ $t('footer.contact') }}
                </p>

                <ul>
                    <li><a href="tel:+385 99 366 0606">+385 99 366 0606</a></li>
                    <li><a href="mailto:hello@adriaticsunsets.com">hello@adriaticsunsets.com</a></li>
                    <li>Uz Glavičino 6, 20207 Mlini<br >OIB 21242579244</li>
                </ul>
                <ul>
                    <li class="Footer-sectionTitle">{{ $t('footer.headquaters') }}</li>
                    <li>Frana Supila 3, <br> 20000 Dubrovnik (Ploče gate),<br> <b>Office Hours:</b> 09:00 - 20:00; <br> Obala Pape Ivana Pavla II</li>
                    <li>
                        Obala Pape Ivana Pavla II, 1, <br> 20000 Dubrovnik (Cargo port, Cruise terminal), <br> <b>Office Hours:</b> 07:00 - 17:00
                    </li>
                </ul>
            </div>

            <div class="Footer-section Footer--sectionLogo">
                <img
                    src="~/assets/vector/bg/logo.svg"
                    alt="">
                <p class="Footer-copy">
                    &copy; 2018 Adriatic Sunsets d.o.o.
                </p>
            </div>

            <div class="Footer-section Footer--sectionLinks">
                <p class="Footer-sectionTitle">
                    {{ $t('footer.links') }}
                </p>
                <ul>
                    <li><nuxt-link :to="{ name: 'lang-about', params: { lang: $i18n.locale }}">{{ $t('footer.about') }}</nuxt-link></li>
                    <!-- <li><nuxt-link :to="{ name: 'lang-faq', params: { lang: $i18n.locale }}">{{ $t('footer.faq') }}</nuxt-link></li> -->
                    <li><nuxt-link :to="{ name: 'lang-terms', params: { lang: $i18n.locale }}">{{ $t('footer.terms') }}</nuxt-link></li>
                </ul>
            </div>

            <div class="Footer-section Footer--sectionTripadvisor">
                <no-ssr>
                    <tripadvisor />
                </no-ssr>
            </div>

            <div class="Footer-section Footer--sectionCopy">
                <p class="Footer-sectionTitle">
                    Adriatic Sunsets d.o.o.
                </p>
                <p class="Footer-copy">
                    &copy; 2018 Adriatic Sunsets d.o.o.
                </p>
            </div>

        </div>
    </footer>
</template>
<script>
    import tripadvisor from '~/components/tripadvisor.vue'
    export default {
        components: {
            tripadvisor
        }
    }
</script>

