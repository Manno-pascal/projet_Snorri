import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    switch() {

            let body = document.querySelector('html')
        if (body.getAttribute('data-theme-mode') === "dark") {
            body.removeAttribute('data-theme-mode')
            body.removeAttribute('data-theme-dark')
            body.removeAttribute('data-header-styles')
            body.removeAttribute('data-menu-styles')
            let expirationDate = new Date();
            expirationDate.setTime(expirationDate.getTime() + (365 * 24 * 60 * 60 * 1000));
            let expiration = " ; expires=" + expirationDate.toUTCString();
            let path = "; domain=" + window.location.hostname + "; path=/";
            document.cookie = 'themeMode = light' + expiration + path
        } else {
            body.setAttribute('data-theme-dark', 'dark')
            body.setAttribute('data-header-styles', 'dark')
            body.setAttribute('data-menu-styles', 'dark')
            body.setAttribute('data-theme-mode', 'dark')
            let expirationDate = new Date();
            expirationDate.setTime(expirationDate.getTime() + (365 * 24 * 60 * 60 * 1000));
            let expiration = " ; expires=" + expirationDate.toUTCString();
            let path = "; domain=" + window.location.hostname + "; path=/";
            document.cookie = 'themeMode = dark' + expiration + path
        }
    }


}
