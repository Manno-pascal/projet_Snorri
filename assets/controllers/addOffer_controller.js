import {Controller} from '@hotwired/stimulus';
import Swal from 'sweetalert2'
import 'select2'
import $ from 'jquery';

export default class extends Controller {

    static values = {
        popup: String,
    }

    connect() {
        if (this.popupValue === "1") {
            Swal.fire(
                'Offre soumise !',
                'Votre offre à bien été proposé à la soumission !',
                'success'
            )
        }
        $('.select2-city').select2({
            minimumInputLength: 3,
            placeholder: "Rechercher une ville",
            ajax: {
                url: 'https://api-adresse.data.gouv.fr/search/',
                data: (params) => {
                    return {
                        q: params.term,
                        type: 'municipality'
                    }
                },
                delay: 500,
                processResults: (response) => {
                    let data = response.features.map((feature) => ({
                        id: feature.properties.label,
                        text: feature.properties.label
                    }));
                    return {
                        results: data
                    };
                },
            }
        });
    }
}
