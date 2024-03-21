import { Controller } from '@hotwired/stimulus';
import Quill from "quill";
import "../../public/theme/assets/libs/quill/quill.snow.css";

export default class extends Controller {
    connect() {
        let editor = new Quill('#editor', {
            theme: 'snow'
        });
    }
}
