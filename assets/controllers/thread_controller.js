import {Controller} from '@hotwired/stimulus';
import Routing from "fos-router";
import 'twbs-pagination';
import {fetchMessages, fetchNewMessage} from "./dataFetcher";

export default class extends Controller {
    static values = {
        threadId: String,
        userId: String,
        currentPage: Number,
    }

    async connect() {

        let messages = await this.messageCards()
        this.pagination(messages,1)
    }

    async messageCards(page = 1) {
        let container = document.querySelector('.messagesContainer')

        let data = await fetchMessages(this.threadIdValue,page)
        this.displayMessages(data)
        return data['pagesNumber']
    }

    async newMessage() {
        let input = document.querySelector('.ql-editor')
        const url = Routing.generate('api_forum_add_message', {
            id: this.threadIdValue,
        });
        let data = await fetchNewMessage(this.threadIdValue, input.innerHTML)
        this.displayMessages(data)
        input.innerHTML = ""
    }

    displayMessages(messages) {
        let container = document.querySelector('.messagesContainer')
        container.innerHTML = ''
        let colorBoolean = true
        messages['messages'].forEach(message => {
            container.innerHTML +=
                `
                            <div class="col-12 mb-3 border border-gray p-3 ${colorBoolean ? 'bg-white' : 'bg-lightBlue'} messageContainer">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex">
                                        <img class="avatar avatar-rounded " src="${message.avatar ? message.avatar : "/assets/images/placeholder_profil.png"}">
                                        <div class="mx-5">
                                            <p>${message.user_creator.firstname} ${message.user_creator.lastname}</p>
                                            <p>${message.createdAt}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-light" data-action="click->thread#replyMessage">Répondre</button>
                                    </div>
                                </div>
                                <hr class="col-11 mx-auto">
                                <div>
                                <div class="textContainer">${message.content}</div>
                                </div>
                            </div>
                `
            colorBoolean = !colorBoolean
        })
    }

    replyMessage(element) {
        let input = document.querySelector('.ql-editor')
        let messageContainer = element.target.closest('.messageContainer')
        input.innerHTML = messageContainer.querySelector('.textContainer').innerHTML
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
                await this.messageCards(page)
                window.scroll(0, 0)
            }
        });
    }


}
