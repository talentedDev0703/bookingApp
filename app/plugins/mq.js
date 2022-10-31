import Vue from 'vue'
import VueMq from 'vue-mq'

if (process.browser){
    Vue.use(VueMq, {
        breakpoints: {
            sm: 700,
            sm2: 800,
            md: 900,
            lg: 1200,
            xl: Infinity
        }
    })
}
