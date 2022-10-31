const MomentLocalesPlugin = require('moment-locales-webpack-plugin');

module.exports = {

    head: {
        title: 'Enjoy the ride, love the views - Adriatic Sunsets',
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: "Panoramic Rides around Dubrovnik. Check out Dubrovnik's hidden gems, beautiful views and gorgeous Adriatic sunsets with our unique tour rides." }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
            { rel: 'alternate', hreflang: 'hr', href: '/hr' },
            { rel: 'alternate', hreflang: 'en', href: '/' },
        ]
    },

    loading: { color: '#F7941E' },

    plugins: [
        '~/plugins/i18n.js',
        '~/plugins/geolocation.js',
        '~/plugins/mq.js',
        '~/plugins/svg-inline.js',
        '~/plugins/smoothscroll.js',
        '~/plugins/distances.js',
        '~/plugins/axios.js',
        { src: '~/plugins/flickity.js', ssr: false },
        { src: '~/plugins/svg.js', ssr: false },
        { src: '~/plugins/moment.js', ssr: true },
        { src: '~/plugins/yt.js', ssr: true },
        { src: '~/plugins/svgfix.js', ssr: false }
    ],

    router: {
        middleware: ['i18n'],
        base: process.env.NODE_ENV === 'production' ? '/app/' : '/'
    },

    generate: {
        routes: ['/', '/hr']
    },

    modules: [
        '@nuxtjs/axios',
        '@nuxtjs/component-cache',
        // '@nuxtjs/pwa',
        ['nuxt-validate', {
            lang: 'en'
        }],
        ['nuxt-facebook-pixel-module', {
            track: 'PageView',
            pixelId: '2135253016710264',
        }],
        ['@nuxtjs/google-tag-manager', { id: 'GTM-T83JRT4', dev: true }],
        'cookie-universal-nuxt'
    ],

    axios: {
        baseURL: 'https://www.adriaticsunsets.com/booking/',
        debug: true,
        crossDomain: true,
        credentials: true
    },

    meta: {
        name: 'Adriatic Sunsets - Enjoy the ride, love the views',
        description: "Panoramic Rides around Dubrovnik. Check out Dubrovnik's hidden gems, beautiful views and gorgeous Adriatic sunsets with our unique tour rides.",
        ogHost: 'https://www.adriaticsunsets.com/',
        ogImage: {
            path: 'app/static/ogImage.png',
            width: 1200,
            height: 630,
            type: 'image/jpeg'
        },
        twitterCard: "Panoramic Rides around Dubrovnik. Check out Dubrovnik's hidden gems, beautiful views and gorgeous Adriatic sunsets with our unique tour rides.",
    },

    manifest: {
        name: 'Adriatic Sunsets',
        lang: 'en',
        start_url: 'index.html'
    },

    build: {
        vendor: [
            'vue-i18n',
            'vue-browser-geolocation',
            'vue-mq',
            'vue-flickity',
            'lazysizes',
            'picturefill',
            'svg.js',
            'vue-smooth-scroll',
            'vue-moment',
            'vuejs-datepicker'
        ],
        postcss: [
            require('autoprefixer')()
        ],

        plugins: [
            new MomentLocalesPlugin({
                localesToKeep: ['es-us', 'hr'],
            })
        ],

        extend (config, { isDev, isClient }) {
            // if (isDev && isClient) {
            //     config.module.rules.push({
            //         enforce: 'pre',
            //         test: /\.(js|vue)$/,
            //         loader: 'eslint-loader',
            //         exclude: /(node_modules)/
            //     })
            // }


            // Store Vue loaders
            const vueLoader = config.module.rules.find(function (module) {
                return module.test.toString() === '/\\.vue$/'
            })

            // Remove SVG from default rules
            config.module.rules.forEach((rule) => {
                if (rule.test.toString() === '/\\.(png|jpe?g|gif|svg)$/') {
                    rule.test = /\.(png|jpe?g|gif)$/
                }
            })


            const path = require('path');

            // Add svg inline loader configuration
            config.module.rules.push({
                test: /\.svg$/,
                // loader: 'svg-inline-loader',
                oneOf: [
                    {
                        exclude: path.resolve(__dirname, 'assets/vector/bg/'),
                        use: 'svg-inline-loader'
                    },
                    {
                        include: path.resolve(__dirname, 'assets/vector/bg/'),
                        use: 'file-loader'
                    },
                ],
            })

            // Important to apply transforms on svg-inline:src
            vueLoader.options.transformToRequire['svg-inline'] = ['src']
            vueLoader.options.transformToRequire['img'] = ['src', 'data-src', 'data-flickity-lazyload']
            vueLoader.options.transformToRequire['vImage'] = ['src', 'data-src', 'data-flickity-lazyload']
            vueLoader.options.transformToRequire['source'] = ['srcset']

        }
    }
}
