import { Controller } from '@hotwired/stimulus';
import Swal from 'sweetalert2'

export default class extends Controller {

    static values = {
        popup: String,
    }
    connect() {
        if (this.popupValue === "1"){
            Swal.fire(
                'Outil soumis !',
                'Votre outil à bien été proposé à la soumission !',
                'success'
            )
        }
    }
}
