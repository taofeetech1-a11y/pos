@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Point of Sale (POS)</h4>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="posForm">
                        @csrf

                        <!-- Product Search -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Search Product:</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="search" id="product_search" class="form-control" placeholder="Search product by code or name" autocomplete="off">
                                </div>
                                <div id="product_list" class="list-group mt-2" style="position: absolute; z-index: 1000; max-height: 300px; overflow-y: auto; width: 95%;"></div>
                            </div>
                        </div>

                        <!-- Cart Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Cart Items: <span class="text-danger">*</span></label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart_items">
                                            <tr id="empty_cart">
                                                <td colspan="6" class="text-center text-muted">No items in cart. Search and add products.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Summary -->
                        <div class="row mt-3">
                            <div class="col-md-6 ms-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table border">
                                                <tbody>
                                                    <tr>
                                                        <td class="py-3"><strong>Subtotal</strong></td>
                                                        <td class="py-3 text-end" id="subtotalDisplay">₦ 0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3">Discount</td>
                                                        <td class="py-3 text-end" id="discountDisplay">₦ 0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3">Shipping</td>
                                                        <td class="py-3 text-end" id="shippingDisplay">₦ 0.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-primary"><strong>Grand Total</strong></td>
                                                        <td class="py-3 text-primary text-end"><strong id="grandTotalDisplay">₦ 0.00</strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Discount, Shipping & Payment -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label class="form-label">Discount:</label>
                                <input type="number" id="discount" name="discount" class="form-control" value="0" min="0" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Discount Type:</label>
                                <select id="discount_type" name="discount_type" class="form-control form-select">
                                    <option value="fixed">Fixed (₦)</option>
                                    <option value="percentage">Percentage (%)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Shipping:</label>
                                <input type="number" id="shipping" name="shipping" class="form-control" value="0" min="0" step="0.01">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Payment Method: <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-control form-select" required>
                                    <option value="">Select Payment</option>
                                    <option value="cash">Cash</option>
                                    <option value="pos">POS</option>
                                    <option value="wire">Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Payment Status: <span class="text-danger">*</span></label>
                                <select name="payment_status" id="payment_status" class="form-control form-select" required>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Notes:</label>
                                <textarea class="form-control" name="note" id="note" rows="2" placeholder="Optional notes"></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-xl-12">
                            <div class="d-flex mt-4 justify-content-end">
                                <button class="btn btn-primary btn-lg me-3" type="submit" id="submitSale">
                                    <i class="fas fa-check me-2"></i>Complete Sale
                                </button>
                                <button class="btn btn-secondary btn-lg" type="button" id="resetCart">
                                    <i class="fas fa-times me-2"></i>Clear Cart
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sale Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="receiptContent">
                <!-- Receipt will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.open('/pos/receipt/' + currentReceiptNumber, '_blank')">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let searchTimeout;
    let currentReceiptNumber = '';

    // Product Search
    document.getElementById('product_search').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        let search = this.value;

        if (search.length < 2) {
            document.getElementById('product_list').innerHTML = '';
            document.getElementById('product_list').style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(function() {
            fetch(`/search/products?search=${encodeURIComponent(search)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.products.length > 0) {
                        let html = '';
                        data.products.forEach(function(product) {
                            html += `
                            <a href="#" class="list-group-item list-group-item-action add-to-cart" 
                               data-product='${JSON.stringify(product)}'>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>${product.name}</strong>
                                        <small class="text-muted d-block">Code: ${product.code}</small>
                                    </div>
                                    <div class="text-end">
                                        <div><strong>₦${parseFloat(product.price).toFixed(2)}</strong></div>
                                        <small class="text-muted">Stock: ${product.product_qty}</small>
                                    </div>
                                </div>
                            </a>
                        `;
                        });
                        document.getElementById('product_list').innerHTML = html;
                        document.getElementById('product_list').style.display = 'block';
                    } else {
                        document.getElementById('product_list').innerHTML = '<div class="list-group-item">No products found</div>';
                        document.getElementById('product_list').style.display = 'block';
                    }
                });
        }, 300);
    });

    // Add to Cart
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-to-cart')) {
            e.preventDefault();
            let product = JSON.parse(e.target.closest('.add-to-cart').getAttribute('data-product'));

            let existingItem = cart.find(item => item.product_id === product.id);

            if (existingItem) {
                if (existingItem.qty < product.product_qty) {
                    existingItem.qty++;
                } else {
                    showAlert('error', 'Cannot add more. Stock limit reached!');
                    return;
                }
            } else {
                cart.push({
                    product_id: product.id
                    , name: product.name
                    , code: product.code
                    , unit_price: parseFloat(product.price)
                    , stock: product.product_qty
                    , qty: 1
                });
            }

            document.getElementById('product_search').value = '';
            document.getElementById('product_list').innerHTML = '';
            document.getElementById('product_list').style.display = 'none';
            renderCart();
            calculateTotals();
        }
    });

    // Render Cart
    function renderCart() {
        if (cart.length === 0) {
            document.getElementById('cart_items').innerHTML = `
            <tr id="empty_cart">
                <td colspan="6" class="text-center text-muted">No items in cart. Search and add products.</td>
            </tr>
        `;
            return;
        }

        let html = '';
        cart.forEach(function(item, index) {
            let subtotal = item.unit_price * item.qty;
            html += `
            <tr>
                <td>
                    <strong>${item.name}</strong><br>
                    <small class="text-muted">Code: ${item.code}</small>
                </td>
                <td>₦${item.unit_price.toFixed(2)}</td>
                <td>${item.stock}</td>
                <td>
                    <div class="input-group" style="width: 130px;">
                        <button class="btn btn-sm btn-outline-secondary qty-decrease" data-index="${index}" type="button">-</button>
                        <input type="number" class="form-control form-control-sm text-center qty-input" 
                               data-index="${index}" value="${item.qty}" min="1" max="${item.stock}">
                        <button class="btn btn-sm btn-outline-secondary qty-increase" data-index="${index}" type="button">+</button>
                    </div>
                </td>
                <td><strong>₦${subtotal.toFixed(2)}</strong></td>
                <td>
                    <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        });
        document.getElementById('cart_items').innerHTML = html;
    }

    // Quantity Decrease
    document.addEventListener('click', function(e) {
        if (e.target.closest('.qty-decrease')) {
            let index = e.target.closest('.qty-decrease').getAttribute('data-index');
            if (cart[index].qty > 1) {
                cart[index].qty--;
                renderCart();
                calculateTotals();
            }
        }
    });

    // Quantity Increase
    document.addEventListener('click', function(e) {
        if (e.target.closest('.qty-increase')) {
            let index = e.target.closest('.qty-increase').getAttribute('data-index');
            if (cart[index].qty < cart[index].stock) {
                cart[index].qty++;
                renderCart();
                calculateTotals();
            } else {
                showAlert('error', 'Stock limit reached!');
            }
        }
    });

    // Manual Quantity Input
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('qty-input')) {
            let index = e.target.getAttribute('data-index');
            let newQty = parseInt(e.target.value);

            if (newQty < 1) {
                newQty = 1;
            } else if (newQty > cart[index].stock) {
                newQty = cart[index].stock;
                showAlert('error', 'Stock limit reached!');
            }

            cart[index].qty = newQty;
            renderCart();
            calculateTotals();
        }
    });

    // Remove Item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            let index = e.target.closest('.remove-item').getAttribute('data-index');
            cart.splice(index, 1);
            renderCart();
            calculateTotals();
        }
    });

    // Calculate Totals
    function calculateTotals() {
        let subtotal = 0;
        cart.forEach(function(item) {
            subtotal += item.unit_price * item.qty;
        });

        let discount = parseFloat(document.getElementById('discount').value) || 0;
        let discountType = document.getElementById('discount_type').value;
        let shipping = parseFloat(document.getElementById('shipping').value) || 0;

        let discountAmount = 0;
        if (discountType === 'percentage') {
            discountAmount = (subtotal * discount) / 100;
        } else {
            discountAmount = discount;
        }

        let grandTotal = subtotal - discountAmount + shipping;

        if (grandTotal < 0) grandTotal = 0;

        document.getElementById('subtotalDisplay').textContent = '₦' + subtotal.toFixed(2);
        document.getElementById('discountDisplay').textContent = '₦' + discountAmount.toFixed(2);
        document.getElementById('shippingDisplay').textContent = '₦' + shipping.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = '₦' + grandTotal.toFixed(2);
    }

    // Recalculate on discount/shipping change
    document.getElementById('discount').addEventListener('input', calculateTotals);
    document.getElementById('shipping').addEventListener('input', calculateTotals);
    document.getElementById('discount_type').addEventListener('change', calculateTotals);

    // Reset Cart
    document.getElementById('resetCart').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            cart = [];
            renderCart();
            calculateTotals();
            document.getElementById('discount').value = 0;
            document.getElementById('shipping').value = 0;
            document.getElementById('payment_method').value = '';
            document.getElementById('payment_status').value = 'paid';
        }
    });

    // Submit Sale
    document.getElementById('posForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            showAlert('error', 'Cart is empty! Add products first.');
            return;
        }

        if (!document.getElementById('payment_method').value) {
            showAlert('error', 'Please select payment method!');
            return;
        }

        let formData = {
            items: cart
            , discount: parseFloat(document.getElementById('discount').value) || 0
            , discount_type: document.getElementById('discount_type').value
            , shipping: parseFloat(document.getElementById('shipping').value) || 0
            , payment_method: document.getElementById('payment_method').value
            , payment_status: document.getElementById('payment_status').value
            , _token: document.querySelector('input[name="_token"]').value
        };

        document.getElementById('submitSale').disabled = true;
        document.getElementById('submitSale').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        fetch('/pos/sale', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': formData._token
                }
                , body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert('success', data.message);
                    currentReceiptNumber = data.receipt_number;

                    // Show receipt modal
                    showReceiptModal(data);

                    // Reset cart
                    cart = [];
                    renderCart();
                    calculateTotals();
                    document.getElementById('posForm').reset();
                    document.getElementById('discount_type').value = 'fixed';

                    document.getElementById('submitSale').disabled = false;
                    document.getElementById('submitSale').innerHTML = '<i class="fas fa-check me-2"></i>Complete Sale';
                } else {
                    showAlert('error', data.message);
                    document.getElementById('submitSale').disabled = false;
                    document.getElementById('submitSale').innerHTML = '<i class="fas fa-check me-2"></i>Complete Sale';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showAlert('error', 'An error occurred while processing the sale!');
                document.getElementById('submitSale').disabled = false;
                document.getElementById('submitSale').innerHTML = '<i class="fas fa-check me-2"></i>Complete Sale';
            });
    });

    // Show Receipt Modal
    function showReceiptModal(saleData) {
        const receiptContent = `
        <div class="text-center mb-4">
            <h3>Sale Receipt</h3>
            <p class="mb-1"><strong>Receipt #:</strong> ${saleData.receipt_number}</p>
            <p class="mb-1"><strong>Date:</strong> ${new Date().toLocaleString()}</p>
        </div>
        <hr>
        <div class="alert alert-success">
            <h4 class="text-center mb-0">
                <i class="fas fa-check-circle me-2"></i>
                Sale Completed Successfully!
            </h4>
            <h2 class="text-center mt-2 mb-0">Grand Total: ₦${parseFloat(saleData.grand_total).toFixed(2)}</h2>
        </div>
        <p class="text-center text-muted">Click "Print Receipt" to print full details</p>
    `;

        document.getElementById('receiptContent').innerHTML = receiptContent;
        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
    }

    // Alert Function
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        const container = document.getElementById('alertContainer');
        container.innerHTML = alertHtml;

        setTimeout(function() {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => container.innerHTML = '', 150);
            }
        }, 5000);
    }

</script>
@endsection
