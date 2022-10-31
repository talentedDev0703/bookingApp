export default function ({ store, redirect, app}) {
    // If the user is not authenticated
    if (!store.state.user.token && !app.$cookies.get('auth')) {
        return redirect(`/${store.state.locale}/login`)
    }
}
