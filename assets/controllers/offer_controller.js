// voici la partie javascript de la page, au début nous importons les librairies que nous utilisons dans le front
// le controller permet d'utiliser stimulus
//fosrouter permet d'utiliser les routes d'api de php grâce à leur nom
// twbs-pagination permet de créer la pagination de la page
import {Controller} from '@hotwired/stimulus';
import 'twbs-pagination';
import {fetchOffers, fetchOffer} from './dataFetcher'

//stimulus fonctionne grâce a des classes et des méthodes, on retrouve donc le principe de la poo

export default class extends Controller {
//ici il s'agit de valeurs utilisables dans les méthodes, elles sont en static pour pouvoir les appeler grace au this, donc
    //directement a partir de la class en elle même
    //ces valeurs sont utilisables de cette facon là : this.currentTypeValue
    static values = {
        currentType: String,
        currentKeyword: String,
        currentLocation: String,
        currentPage: Number,

    }

    // La méthode connect s'execute au chargement du javascript, elle est asynchrone car on réalise des appels api qui ont
    // un temps de réponse, cela permet d'utiliser les promesses
    async connect() {
        //on appelle la méthode pour reset les offres et placer le squeletton
        this.resetDisplay()
        // on appelle la methode offerCards
        let offers = await this.offerCards()
        this.pagination(offers, 1)
    }

    paginationInstance;

    resetDisplay() {
        let container = document.querySelector('.offersContainer')
        let skeleton =
            `
                <div class="bg-white p-2 px-5 mb-4 rounded">
                    <div class="p-1 rounded mb-1 skeleton skeleton-bg w-50">ㅤ</div>
                    <div class="mb-1 skeleton skeleton-bg w-50">ㅤ</div>
                    <div class="mb-1 skeleton skeleton-bg w-50">ㅤ</div>
                    <div class="mb-1 skeleton skeleton-bg w-50">ㅤ</div>
                    <div class="skeleton skeleton-bg w-50">ㅤ</div>
                </div>
            `
        container.innerHTML = ''
        for (let i = 0; i < 10; i++) {
            container.innerHTML += skeleton
        }
    }

    async offerCards(type = null, keyword = null, location = null, page = 1) {
        this.currentType = type;
        this.currentKeyword = keyword;
        this.currentLocation = location;
        let container = document.querySelector('.offersContainer')
        let data = await fetchOffers(type,keyword,location,page)
        container.innerHTML = ''
        data['offers'].forEach((offer,index) => {
            index === 0 ? this.displayOffer(null, offer.id):null;
            container.innerHTML +=
                `
                        <div class="bg-white p-3 px-4 mb-3 rounded d-flex flex-column offerContainer justify-content-center cursor-pointer" data-action="click->offer#displayOffer" data-id=${offer.id}>
                            <p class="${offer.contract_type.toLowerCase()}-bg p-1 px-2 rounded mb-1 text-fixed-black">${offer.contract_type}</p>
                            <p class="mb-1 fw-bold">${offer.title}</p>
                            <p class="mb-1">${offer.user_creator.company_name}</p>
                            <p class="mb-1">${offer.location}</p>
                            <p class="text-gray m-0">Le ${offer.date}</p>
                        </div>
                        `
        })
        return data['pagesNumber']
    }

    async displayOffer(element = null, id = null) {
            let data = await fetchOffer(element ? element.target.closest('.offerContainer').getAttribute('data-id'): id)
                document.querySelectorAll('#salaryOffer').forEach(elt => elt.innerText = data.salary);
                document.querySelectorAll('#contractTypeOffer').forEach(elt => elt.innerText = data.contract_type);
                document.querySelectorAll('#titleOffer').forEach(elt => elt.innerText = data.title);
                document.querySelectorAll('#descriptionOffer').forEach(elt => elt.innerText = data.description);
                document.querySelectorAll('#dateOffer').forEach(elt => elt.innerText = data.date);
                document.querySelectorAll('#locationOffer').forEach(elt => elt.innerText = data.location);
                if (element && window.innerWidth <= 576){
                    let modal = new bootstrap.Modal(document.getElementById('displayOfferModal'));
                    modal.show();
                }
    }
    async offersFilters() {
        let location = document.querySelector('#location').value !== "" ? document.querySelector('#location').value : null
        let contractType = document.querySelector('#contractType').value !== "" ? document.querySelector('#contractType').value : null
        let keyword = document.querySelector('#keyword').value !== "" ? document.querySelector('#keyword').value : null
        this.resetDisplay()
        let offers = await this.offerCards(contractType, keyword, location)
        this.pagination(offers,1)
    }

    pagination(numberOfPages, currentPage = 1) {
        if (this.paginationInstance) {
            this.paginationInstance.twbsPagination('destroy')
        }
        this.paginationInstance = $('#pagination').twbsPagination({
            totalPages: numberOfPages,
            startPage: currentPage,
            visiblePages: 3,
            first: "<<",
            prev: "<",
            next: ">",
            last: ">>",
            onPageClick: async (event, page) => {
                this.resetDisplay()
                await this.offerCards(this.currentType, this.currentKeyword, this.currentLocation, page)
                window.scroll(0, 0)
            }
        });
    }

}