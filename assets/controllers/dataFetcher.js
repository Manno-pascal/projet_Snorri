import Routing from "fos-router";

export const fetchTools = async (category, keyword, page)=>{
    try {
    const url = Routing.generate('api_tool_tools', {
        category: category,
        keyword: keyword,
        page: page,
    });

        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e){
        await fetchSaveLogs(e)
    }
}

export const editFavoriteToolFetch = async (toolId)=>{
    try {
        const url = Routing.generate('api_tool_edit_favorite', {
            tool: toolId
        });
        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchOffers = async (type, keyword, location ,page) => {
    try {
        const url = Routing.generate('api_offer_get_offers', {
            contract: type,
            keyword: keyword,
            location: location,
            page:page,
        });
        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchOffer = async (id) => {
    try {
        const url = Routing.generate('api_offer_get_offer', {
            id: id,
        });
        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchThreads = async (category, keyword, page) => {
    try {
        const url = Routing.generate('api_forum_threads', {
            category: category,
            keyword: keyword,
            page:page
        });
        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchMessages = async (threadId, page) => {
    try {
        const url = Routing.generate('api_forum_thread_messages', {
            id: threadId,
            page: page,
        });
        let response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchNewMessage = async (threadId, message) => {
    try {
        const url = Routing.generate('api_forum_add_message', {
            id: threadId,
        });
        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(message)
        })
        return await response.json()
    }catch (e) {
        await fetchSaveLogs(e)
    }

}

export const fetchSaveLogs = async(error) =>{
    const url = Routing.generate('send_error');
    let response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(error)
    })
    console.log('Une erreur est survenue. Numéro de ticket : ' + await response.json())
}