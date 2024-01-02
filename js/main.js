$(document).ready(() => {
    fetchItems();

    header.initSearchBar();

    if (!$('.error-msg-div').length) {
        header.updateCartSection();
    }

    button.initEvents();
});

// Centralised function for all AJAX requests
window.ajaxReq = function(payload, _successCallback) {
    $.ajax({
        type: 'POST',
        url: 'scripts/api.php',
        contentType: 'application/json; charset=utf-8',
        data: payload,
        success: (data) => {
            _successCallback(data);
        },
        error: (data) => {
            const errorMessageDiv = document.createElement('div');
            errorMessageDiv.className = 'error-msg-div center';
            errorMessageDiv.textContent = JSON.parse(data.responseText).errMsg;
            $(errorMessageDiv).insertAfter('.top-menu');
        },
    });
}

// Fetch the item for display on the page
function fetchItems() {
    const pathname = window.location.pathname.replace('/Shop/', '');
    const isIndexPage = pathname === 'index.php';
    const isCartPage = pathname === 'cart.php';

    const itemSlug = window.location.search.replace('?', '');

    let payload = { action: isCartPage ? 'gci' : 'fi' };
    payload.items = itemSlug || 'all';

    const $itemList = $('.item-list');
    const $shopItem = $('.shop-item');

    payload = JSON.stringify(payload);

    const _successCallback = (data) => {
        data = JSON.parse(data);

        if (data.errMsg) {
            return;
        }

        // Create DOM elements
        for (const item of data) {
            if (isIndexPage || isCartPage) {
                this.itemDiv = document.createElement('div');
                this.itemDiv.className = `item`;
                this.itemDiv.dataset.slug = item.slug;
                $itemList.append(this.itemDiv);
            }

            const nameContainer = document.createElement('div');
            nameContainer.className = 'name-container';
            let parentDescContainer = isIndexPage || isCartPage ?
                this.itemDiv : $shopItem;
            parentDescContainer.append(nameContainer);

            if (isCartPage) {
                $('.key', '.item-list').removeClass('hidden');
                $('.cart-actions').removeClass('hidden');

                const removeButton = document.createElement('div');
                removeButton.className = 'button remove-item';
                removeButton.textContent = 'Remove';
                removeButton.onclick = button.removeItemEvent;
                parentDescContainer.prepend(removeButton);
            }

            const name = document.createElement(isIndexPage ? 'a' : 'div');
            if (isIndexPage || isCartPage) {
                name.href = `item.php?${item.slug}`;
            }
            name.className = 'name';
            name.textContent = item.itemName + (item.quantity ? ` (${item.quantity})` : '');
            if (item.quantity) {
                this.itemDiv.dataset.quantity = item.quantity;
                this.itemDiv.dataset.pid = item.purchaseId;
            }
            nameContainer.append(name);

            if (pathname === 'item.php') {
                document.title = `${item.itemName} | Shop Project`;		
            }
            
            if (item.itemDesc) {
                const desc = document.createElement('div');
                desc.className = 'desc';
                desc.textContent = item.itemDesc;

                parentDescContainer = isIndexPage || isCartPage ? nameContainer : $shopItem;
                parentDescContainer.append(desc);
            }

            const price = document.createElement('div');
            price.className = 'price';
            price.textContent = item.itemPrice;
            parentDescContainer = isIndexPage || isCartPage ?
                this.itemDiv : $('.name-container', $shopItem);
            parentDescContainer.append(price);

            const dollarSign = document.createElement('span');
            dollarSign.textContent = '$';
            price.prepend(dollarSign);
        }

        if (isCartPage && !data.length) {
            $('.item-list').remove();
            $('.no-items-found').removeClass('hidden').text('No items in cart');
        }
    };

    window.ajaxReq(payload, _successCallback);
}
