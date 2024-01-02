header = {

    // Initialise the search bar for use on the index page
    initSearchBar: function() {
        const pathname = window.location.pathname.replace('/Shop/', '');
        const isIndexPage = pathname === 'index.php';

        const $searchBar = $('input.search-bar', '.top-menu');

        if (!isIndexPage) {
            $searchBar.remove();
            return;
        }
        
        $searchBar
            .removeClass('hidden', !isIndexPage)
            .on('input', () => {
                const $items = $('.item', '.item-list');
                const sbValue = $searchBar.val();

                if (!sbValue) {
                    $items.removeClass('hidden');
                    $('.no-items-found').addClass('hidden').text('');
                    return;
                }

                let itemFound = false;

                for (const item of $items) {
                    const $name = $('.name', item).text().toLowerCase();                
                    $(item).toggleClass('hidden', !$name.startsWith(sbValue.toLowerCase()));

                    itemFound = itemFound || $name.startsWith(sbValue.toLowerCase());
                }

                if (!itemFound) {
                    $('.no-items-found').removeClass('hidden').text(`No items starting with '${sbValue}'`);
                }
                else {
                    $('.no-items-found').addClass('hidden').text('');
                }
            });
    },

    // Update the cart section (number of items in cart and total cost of items in cart)
    updateCartSection: function() {
        const $numCartItems = $('.number', '.cart-cost .cart-and-items');
        const $cost = $('.cost', '.cart-cost');

        const payload = JSON.stringify({ action: 'gnci' });

        const _successCallback = (data) => {
            data = JSON.parse(data);

            if (data.errMsg) {
                return;
            }

            let numItems = 0;
            let totalCost = 0;
            if (data.length) {
                for (const item of data) {
                    numItems += parseInt(item.quantity);
                    totalCost += parseFloat(item.itemPrice);
                }
            }

            $numCartItems.text(numItems);

            if (numItems > 0) {
                $cost.removeClass('hidden');
                $cost.text(`$${totalCost.toFixed(2)}`);
            }
            else {
                $cost.addClass('hidden');
                $cost.text('');
            }
        };

        window.ajaxReq(payload, _successCallback);
    }
}
