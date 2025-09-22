<h1><?php echo $title; ?></h1>

<form id="purchase-form" action="/purchases/store" method="POST">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="supplier_name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
        </div>
    </div>

    <hr>

    <h4>Add Products</h4>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="product_search" class="form-label">Search for a product by name or SKU</label>
            <input type="text" class="form-control" id="product_search" autocomplete="off">
            <div id="search-results" class="list-group position-absolute" style="z-index: 1000;"></div>
        </div>
    </div>

    <h5 class="mt-3">Purchase Items</h5>
    <table class="table" id="purchase-items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- Items will be added here by JavaScript -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Grand Total:</th>
                <th id="grand-total">0.00</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <input type="hidden" name="items" id="items-json">
    <input type="hidden" name="total_amount" id="total-amount-input">

    <button type="submit" class="btn btn-primary mt-3">Save Purchase</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product_search');
    const searchResults = document.getElementById('search-results');
    const itemsTbody = document.querySelector('#purchase-items tbody');
    const grandTotalEl = document.getElementById('grand-total');
    const form = document.getElementById('purchase-form');
    const itemsJsonInput = document.getElementById('items-json');
    const totalAmountInput = document.getElementById('total-amount-input');

    let purchaseItems = {}; // Use an object to store items by product ID

    // --- Search for products ---
    searchInput.addEventListener('input', async function() {
        const term = this.value;
        searchResults.innerHTML = '';
        if (term.length < 2) return;

        const response = await fetch(`/products/search?q=${encodeURIComponent(term)}`);
        const products = await response.json();

        products.forEach(product => {
            const a = document.createElement('a');
            a.href = '#';
            a.classList.add('list-group-item', 'list-group-item-action');
            a.textContent = `${product.name} (SKU: ${product.sku})`;
            a.dataset.product = JSON.stringify(product);
            searchResults.appendChild(a);
        });
    });

    // --- Add product to list ---
    searchResults.addEventListener('click', function(e) {
        e.preventDefault();
        if (e.target.tagName === 'A') {
            const product = JSON.parse(e.target.dataset.product);
            if (!purchaseItems[product.id]) { // Prevent adding duplicates
                purchaseItems[product.id] = {
                    id: product.id,
                    name: product.name,
                    quantity: 1,
                    price: product.sale_price // Default to sale price, user can change
                };
                renderItems();
            }
            searchInput.value = '';
            searchResults.innerHTML = '';
        }
    });

    // --- Handle changes and removal in the items table ---
    itemsTbody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity') || e.target.classList.contains('item-price')) {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            purchaseItems[productId].quantity = quantity;
            purchaseItems[productId].price = price;
            renderItems();
        }
    });

    itemsTbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('tr');
            const productId = row.dataset.id;
            delete purchaseItems[productId];
            renderItems();
        }
    });

    // --- Render the table and update totals ---
    function renderItems() {
        itemsTbody.innerHTML = '';
        let grandTotal = 0;

        for (const id in purchaseItems) {
            const item = purchaseItems[id];
            const total = item.quantity * item.price;
            grandTotal += total;

            const tr = document.createElement('tr');
            tr.dataset.id = item.id;
            tr.innerHTML = `
                <td>${item.name}</td>
                <td><input type="number" class="form-control item-quantity" value="${item.quantity}" min="1"></td>
                <td><input type="number" step="0.01" class="form-control item-price" value="${item.price.toFixed(2)}"></td>
                <td class="item-total">${total.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">&times;</button></td>
            `;
            itemsTbody.appendChild(tr);
        }

        grandTotalEl.textContent = grandTotal.toFixed(2);
    }

    // --- Prepare form for submission ---
    form.addEventListener('submit', function(e) {
        // Convert the items object to an array for the backend
        const itemsArray = Object.values(purchaseItems);
        if (itemsArray.length === 0) {
            alert('Please add at least one product to the purchase.');
            e.preventDefault();
            return;
        }
        itemsJsonInput.value = JSON.stringify(itemsArray);
        totalAmountInput.value = parseFloat(grandTotalEl.textContent);
    });

    // Hide search results if clicked outside
    document.addEventListener('click', function (e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.innerHTML = '';
        }
    });

});
</script>
