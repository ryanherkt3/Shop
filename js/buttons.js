button = {

    // Initialise events for the buttons not dynamically created
    initEvents: function() {
        $('.button.add-to-cart').on('click', () => {
            this.updateCart(false);
        });

        $('.button.empty-cart').on('click', () => {
            this.updateCart(true);
        });

        $('.button.purchase-items').on('click', () => {
            this.purchaseItems();
        });
    },

    // Initialise events for the remove item button
    removeItemEvent: function() {
        const $itemToRemove = $(this).parent();
        const slug = $itemToRemove.data('slug');
        const pid = $itemToRemove.data('pid');

        $itemToRemove.remove();
        this.updateCart([slug, pid]);
    },
    
    // Update cart (add items, remove item(s))
    updateCart: function(removeItems) {
        this.quantityValue;
        this.priceValue;

        let payload = {
            action: removeItems ? 'rfc' : 'atc', 
            type: removeItems ? 'all' : window.location.search.replace('?', ''),
        }

        if (Array.isArray(removeItems)) {
            payload.type = removeItems[0];
            payload.num = removeItems[1];
        }
        else if (!removeItems) {
            const $quantityInput = $('.quantity-input');

            const quantityValue = parseInt($quantityInput.val());
            const priceValue = $('.price', '.name-container').text().replace('$', '');

            if (quantityValue > parseInt($quantityInput.attr('max')) || 
                quantityValue < parseInt($quantityInput.attr('min'))) {
                alert('Enter a value between 1 and 10 in the input field');
                return;
            }

            payload.num = quantityValue;
            payload.price = priceValue*quantityValue;
        }

        payload = JSON.stringify(payload);

        window.ajaxReq(payload, () => {
            if (removeItems === true || $('.item', '.item-list').length === 0) {
                $('.item-list').remove();
                $('.cart-actions').addClass('hidden');
                $('.no-items-found').removeClass('hidden').text('No items in cart');
            }
            header.updateCartSection();
        });
    },

    // Purchase the items
    purchaseItems: function() {
        const payload = JSON.stringify({
            action: 'purchase'
        });

        window.ajaxReq(payload, () => {
            $('.item-list')
                .empty()
                .text('Congratulations on your purchases!')
                .addClass('center');
            $('.cart-actions').addClass('hidden');

            header.updateCartSection();
        });
    }
}
