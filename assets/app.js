import {startStimulusApp} from '@symfony/stimulus-bridge';
import Swal from 'sweetalert2'

const context = require.context('./controllers', true, /\.js$/);
startStimulusApp(context);
import './styles/app.css';

let loadedElements = document.querySelectorAll('.loaded-element');

loadedElements.forEach((element) => {
    let loader = document.createElement('div');
    loader.classList.add("loader");
    element.parentNode.insertBefore(loader, element);
    loader.appendChild(element);

    setTimeout(() => {
        loader.parentNode.insertBefore(element, loader);
        loader.parentNode.removeChild(loader);
        element.classList.remove('loaded-element')
    }, 1000);
});

if (!checkCookie("acceptCookie")) {
    Swal.fire({
        title: "Ce site utilise des cookies nécessaires au bon fonctionnement.",
        text: "Ce site utilise des cookies pour améliorer votre expérience. Certains cookies sont nécessaires au bon fonctionnement du site, y compris ceux permettant le thème sombre. En utilisant ce site, vous acceptez l'utilisation de cookies.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "J'accepte les cookies",
        cancelButtonText: 'Je refuse les cookies'
    })
        .then((result) => {
            if (result.isConfirmed) {
                let expirationDate = new Date();
                expirationDate.setTime(expirationDate.getTime() + (365 * 24 * 60 * 60 * 1000));
                let expiration = " ; expires=" + expirationDate.toUTCString();
                let path = "; domain=" + window.location.hostname + "; path=/";
                document.cookie = 'acceptCookie = true' + expiration + path

            } else {
                window.location.href = `https://google.fr`;
            }
        })
}

function checkCookie(name) {
    let cookies = document.cookie.split(';');

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
            return true;
        }
    }
    return false;
}



