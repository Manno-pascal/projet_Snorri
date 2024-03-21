import {Controller} from '@hotwired/stimulus';
import Dropzone from "dropzone";
import Routing from 'fos-router';

import "../../public/theme/assets/libs/dropzone/dropzone.css"

export default class extends Controller {
    //on récupère les données transmisent dans la vue
    static values = {
        entityClass: String,
        entityColumn: String,
        entityId: String,
    }

    //la fonction connect et une fonction qui se lance automatiquement au chargement de la page
    connect() {
        //on récupère les dropzones via un id dynamique
        let dropzone = document.querySelector(`#${this.entityColumnValue}Dropzone`)
        //on assignes les mimetypes en fonction de la colonne dans laquelle on se trouve (cv = pdf, le reste = image)
        let mimetype = this.entityColumnValue === "cv" ? "application/pdf" : "image/jpg, image/png, image/jpeg"
        //on crée la route, ici on peut la générer directement avec le nom de la route grace a FOSrouting on lui passe
        // en query la classe de l'entity, le nom de la colonne et l'id de l'instance.
        const url = Routing.generate('api_profil_upload_document', {
            entityClass: this.entityClassValue,
            entityColumnName: this.entityColumnValue,
            entityId: this.entityIdValue,
        });
        //on configure l'upload des dropzone : l'url de la route, les types de fichiers qu'il accepte, le nombre d'upload
        //en parallèle, et la taille max
        let myDropzone = new Dropzone(dropzone, {
            url: url,
            acceptedFiles: mimetype,
            parallelUploads: 1,
            uploadMultiple: false,
            maxFilesize: 15000000,
        });


    }
}
