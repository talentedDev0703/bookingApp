export default function ({ store, redirect, app }) {
    // If the user is authenticated redirect to home page
    if (store.state.user.token || app.$cookies.get('auth')) {
        return redirect(`/${store.state.locale}/#tours`)
    }
}
