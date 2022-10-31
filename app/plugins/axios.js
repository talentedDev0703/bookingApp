export default function ({ $axios, store }) {
    $axios.onRequest(config => {
        if (config.url.indexOf('instagram') > -1){
            delete config.headers.common['Authorization'];
        }
    });
    $axios.setToken(store.state.user.token, 'Bearer');
}
