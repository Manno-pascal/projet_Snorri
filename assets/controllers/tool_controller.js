import {Controller} from '@hotwired/stimulus';
import 'twbs-pagination';
import {fetchTools, editFavoriteToolFetch} from './dataFetcher'

export default class extends Controller {
    static values = {
        userId: Number,
        currentCategory: String,
        currentKeyword: String,
        currentPage: Number,

    }
    paginationInstance;

    async connect() {
        this.resetDisplay()
        let tools = await this.toolCards('css')
        this.pagination(tools, 1)

    }

    resetDisplay(element = null) {
        let container = document.querySelector('.toolsContainer')
        if (element) {
            document.querySelectorAll('.nav-link').forEach(categoryButton => {
                if (categoryButton.classList.contains('active')) {
                    categoryButton.classList.remove('active')
                }
                element.target.classList.add('active');
            })
        }
        container.innerHTML =
            `    
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12"><div class="card custom-card skeleton skeleton-bg" style="height:60vh;"></div></div>
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12"><div class="card custom-card skeleton skeleton-bg" style="height:60vh;"></div></div>
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12"><div class="card custom-card skeleton skeleton-bg" style="height:60vh;"></div></div>
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12"><div class="card custom-card skeleton skeleton-bg" style="height:60vh;"></div></div>
            `
    }

    async toolCards(category = null, keyword = null, page = 1) {
        this.currentCategory = category;
        this.currentKeyword = keyword;
        this.currentPage = page;
        let container = document.querySelector('.toolsContainer')
        let data = await fetchTools(category, keyword, page)
        container.innerHTML = ''
        data['tools'].forEach(tool => {
            container.innerHTML +=
                `
                        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6 col-sm-12 toolCard">
                            <div class="card custom-card" style="height:60vh">
                                <img src="${tool.image}" class="card-img-top h-50 object-fit-cover" alt="...">
                                <div class="card-body h-25">
                                    <h6 class="card-title fw-semibold">${tool.title}</h6>
                                    <div class="card-text text-muted overflow-auto h-75">${tool.description}</div>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="${tool.toolLink}" target="_blank" class="btn btn-primary"><em class="bx bx-link-external side-menu__icon me-3"></em>Lien</a>
                                    </div>
                                    <div class="bx ${tool.userTools.some(userTool => userTool.user.id === this.userIdValue) ? 'bxs-star' : 'bx-star'} bx-tada-hover bx-sm text-warning cursor-pointer" data-action="click->tool#editFavorite" data-id="${tool.id}" data-category=${category === 'favorites' ? "favorites" : ""}></div>
                                </div>
                            </div>
                        </div>
                        `
        })
        return data['pagesNumber']
    }

    async displayToolsByCategory(element) {
        this.resetDisplay(element)
        let tools = await this.toolCards(element.target.getAttribute('data-category'))
        this.pagination(tools, 1)
    }

    async displayToolsByKeyword() {
        if (document.querySelector('#searchInput').value === '') {
            return
        }
        this.resetDisplay()
        let tools = await this.toolCards(null, document.querySelector('#searchInput').value)
        this.pagination(tools, 1)
    }

    async editFavorite(element) {
        let data = await editFavoriteToolFetch(element.target.getAttribute('data-id'))
        if (data) {
            element.target.classList.remove('bx-star')
            element.target.classList.add('bxs-star')
        } else {
            element.target.classList.remove('bxs-star')
            element.target.classList.add('bx-star')
            if (element.target.getAttribute('data-category') === "favorites") {
                element.target.closest('.toolCard').remove()
            }
        }
    }

    pagination(numberOfPages, currentPage = 1) {
        if (this.paginationInstance) {
            this.paginationInstance.twbsPagination('destroy')
        }
        this.paginationInstance = $('#pagination').twbsPagination({
            totalPages: numberOfPages,
            startPage: currentPage,
            visiblePages: 5,
            first: "<<",
            prev: "<",
            next: ">",
            last: ">>",
            onPageClick: async (event, page) => {
                this.resetDisplay()
                await this.toolCards(this.currentCategory, this.currentKeyword, page)
                window.scroll(0, 0)
            }
        });
    }
}