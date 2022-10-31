import Vue from 'vue';
const Distances = {
    install(Vue, options) {
        Vue.prototype.$calculateAirDistance = function(lat, lng) {
            const pi = 0.017453292519943295;
            const cos = Math.cos;
            const a =
                0.5 -
                cos((lat - this.$store.state.location.userCoords.lat) * pi) / 2 +
                cos(this.$store.state.location.userCoords.lat * pi) *
                cos(lat * pi) *
                (1 - cos((lng - this.$store.state.location.userCoords.lng) * pi)) /
                2;

            const distance = Math.round(12742 * Math.asin(Math.sqrt(a)) * 100) / 100;

            return distance.toString().replace('.', ',');
        }
    }
};

Vue.use(Distances)
