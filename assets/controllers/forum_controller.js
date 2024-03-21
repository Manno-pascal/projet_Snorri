import {Controller} from '@hotwired/stimulus';
import Routing from "fos-router";
import 'twbs-pagination';
import {fetchThreads} from "./dataFetcher";

export default class extends Controller {
    static values = {
        userId: String,
        currentCategory: String,
        currentKeyword: String,
        currentPage: Number,
    }

    async connect() {
        this.resetDisplay()
        let threads = await this.threadCards('css')
        this.pagination(threads, 1)
    }

    resetDisplay(element) {
        let container = document.querySelector('.threadsContainer')
        let skeleton =
            `
            <tr class="skeleton skeleton-bg border">
                <td>
                    <div class="d-flex align-items-center">
                        <div class="lh-1"><span></span></div>
                    </div>
                </td>
                <td><span class="badge"></span></td>
                <td><div><p></p></div></td>
                <td><div class="lh-1"><span></span></div></td>
                <td></td>
            </tr>
            `
        container.innerHTML = ''
        for (let i = 0; i < 10; i++) {
            container.innerHTML += skeleton
        }
        if (element) {
            document.querySelectorAll('.nav-link').forEach(categoryButton => {
                if (categoryButton.classList.contains('active')) {
                    categoryButton.classList.remove('active')
                }
                element.target.classList.add('active');
            })
        }
    }

    async threadCards(category = null, keyword = null,page = 1) {
        this.currentCategory = category;
        this.currentKeyword = keyword;
        this.currentPage = page;
        let container = document.querySelector('.threadsContainer')
        let data = await fetchThreads(category,keyword,page)
            container.innerHTML = ''
            data['threads'].forEach(thread => {
                container.innerHTML +=
                    `
                        <tr class="threadContainer cursor-pointer" data-action="click->forum#redirectToThread" data-id=${thread.id}>
                            <td>
                            <div class="d-flex align-items-center">
                                <div class="lh-1"> 
                                    <span>${thread.title}</span>  
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-warning-transparent">${thread.category}</span></td>
                        <td>
                            <span>
                                ${thread.user_creator.firstname} ${thread.user_creator.lastname}
                            </span>
                        </td>
                        <td>
                            <div class="lh-1">
                                <span>${thread.createdAt}</span>
                            </div>
                        </td>                
                    </tr>
                        `
            })
        return data['pagesNumber']
    }

    async displayThreadsByCategory(element) {
        this.resetDisplay(element)
        let threads = await this.threadCards(element.target.getAttribute('data-category'))
        this.pagination(threads,1)
    }

    async displayThreadsByKeyword(element) {
        if (document.querySelector('#searchInput').value === '') {
            return
        }
        this.resetDisplay(element)
        let threads = await this.threadCards(null, document.querySelector('#searchInput').value)
        this.pagination(threads,1)
    }

    redirectToThread(element) {
        window.location.href = Routing.generate('app_forum_thread', {
            thread: element.target.closest(".threadContainer").getAttribute("data-id")
        });

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
            onPageClick: (event, page) => {
                this.resetDisplay()
                this.threadCards(this.currentCategory, this.currentKeyword, page)
                window.scroll(0, 0)
            }
        });
    }
}
