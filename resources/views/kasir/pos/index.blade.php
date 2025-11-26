@extends('layouts.auth-layout')
@section('content')
    <div class="h-screen w-full">
        <h1 class="text-2xl font-bold mb-4">Sistem Kasir - {{ Auth::user()->name }}</h1>

        <div id="pos-container" class="flex flex-col py-2 lg:flex-row gap-4">
            <div
                class="flex flex-col lg:w-8/12 bg-white p-2 rounded-lg border border-gray-300 shadow-lg h-[80vh] lg:h-[90vh]">
                <div class="flex mb-4 gap-2">
                    <input type="text" id="search-input" placeholder="Cari Produk..."
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500"
                        onkeydown="if(event.key === 'Enter') handleScan(event)">
                    <button onclick="resetSearch()" id="reset-btn"
                        class="hidden px-4 py-2 bg-blue-600 text-white font-bold cursor-pointer rounded-lg hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>

                <div id="category-filter" class="mb-4 flex space-x-2 overflow-x-auto pb-2 custom-scroll">
                    <button id="all-products-btn" onclick="selectCategory(null)"
                        class="flex-shrink-0 category-btn px-3 py-1 text-sm rounded-full cursor-pointer transition duration-150 bg-blue-600 text-white hover:bg-blue-600 hover:text-white">
                        Semua Produk
                    </button>
                    @foreach ($categories as $category)
                        <button onclick="selectCategory({{ $category->id }})" data-category-id="{{ $category->id }}"
                            class="flex-shrink-0 category-btn px-3 py-1 text-sm rounded-full cursor-pointer transition duration-150 bg-gray-200 text-gray-700 hover:bg-blue-600 hover:text-white">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>

                <div id="product-grid"
                    class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 mt-4 gap-4 overflow-y-auto custom-scroll p-1 flex-1">
                </div>
            </div>

            <div
                class="flex flex-col lg:w-4/12 bg-white p-4 border border-gray-300 rounded-lg shadow-lg overflow-y-auto h-[80vh] lg:h-[90vh]">
                <div id="cart-wrapper">
                    <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>

                    <div id="cart-list" class="overflow-y-auto max-h-64 border-b pb-4 mb-4 custom-scroll">
                        <div id="empty-cart-message" class="text-center text-gray-500 mt-10">
                            Keranjang kosong.
                        </div>
                    </div>

                    <div id="summary-section" class="space-y-2 mb-4 hidden">
                        <div id="subtotal-display" class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span id="subtotal-value">Rp 0</span>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span>Diskon (<span id="discount-display">Rp 0</span>):</span>
                            <input type="number" id="discount-input" oninput="updateState('discountInput', this.value)"
                                placeholder="Nilai Diskon" value="0"
                                class="w-24 text-right px-2 py-1 border rounded-md text-sm">
                            <select id="discount-type-select" onchange="updateState('discountType', this.value)"
                                class="text-sm p-1 border rounded-md">
                                <option value="fixed">Rp</option>
                                <option value="percentage">%</option>
                            </select>
                        </div>

                        <div class="flex justify-between font-bold text-lg border-t pt-2">
                            <span>Total Tagihan:</span>
                            <span id="total-tagihan">Rp 0</span>
                        </div>

                        <div class="border-t pt-2">
                            <label for="customer-select" class="block text-sm font-medium mt-2">Pelanggan (Member)</label>
                            <select onchange="updateState('selectedCustomer', this.value)" id="customer-select"
                                class="w-full mt-1 p-1 border rounded-lg text-sm">
                                <option value="umum">Umum (Tanpa Member)</option>
                            </select>
                        </div>
                    </div>

                    <div id="payment-section" class="hidden">
                        <h3 class="font-semibold mb-2">Metode Pembayaran:</h3>
                        <div id="payment-buttons" class="flex space-x-2 mb-4">
                            <button onclick="updateState('paymentMethod', 'tunai')" data-method="tunai"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-blue-600 text-white">Tunai</button>
                            <button onclick="updateState('paymentMethod', 'debit')" data-method="debit"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-gray-200">Debit</button>
                            <button onclick="updateState('paymentMethod', 'e_wallet')" data-method="e_wallet"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-gray-200">E
                                Wallet</button>
                        </div>

                        <div class="mb-6">
                            <label for="amount-paid-input" class="block text-sm font-medium">Uang Dibayar (Tunai)</label>
                            <input type="number" oninput="updateState('amountPaid', this.value)" value="0"
                                id="amount-paid-input" placeholder="0"
                                class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div id="checkout-area" class="hidden">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        window.POS_DATA = {
            products: @json($productsJson),
            customers: @json($customersJson),
            discounts: @json($discountsJson)
        };
        const initialProductsJson = @json($productsJson);
        const initialCustomersJson = @json($customersJson);


        const productsData = JSON.parse(window.POS_DATA.products || '[]');
        const customersData = JSON.parse(window.POS_DATA.customers || '[]');
        const discountsData = JSON.parse(window.POS_DATA.discounts || '[]');

        const state = {
            products: productsData,
            customers: customersData,
            discounts: discountsData,
            searchTerm: '',
            selectedCategory: null,
            cart: [],
            selectedCustomer: null,
            paymentMethod: 'tunai',
            discountInput: 0,
            amountPaid: 0,
            discountType: 'fixed',
        };

        function formatCurrency(value) {
            if (value === null || value === undefined) return "0";
            return new Intl.NumberFormat("id-ID").format(Math.round(value));
        }

        function getAutomaticDiscount() {
            if (state.selectedCustomer === 'umum' || state.selectedCustomer === null) {
                return 0;
            }

            const customerId = Number(state.selectedCustomer);
            const customer = state.customers.find(c => c.id === customerId);

            if (!customer) return 0;

            let bestDiscountPercentage = 0;
            const totalSpent = customer.total_spent || 0;
            const transactionCount = customer.transaction_count || 0;

            state.discounts.forEach(discount => {
                if (!discount.is_active) {
                    return;
                }

                const meetsMinTotal = totalSpent >= discount.min_total_transaction;

                const meetsMinTransCount = transactionCount >= discount.max_transactions_count;

                if (meetsMinTotal && meetsMinTransCount && discount.percentage > bestDiscountPercentage) {
                    bestDiscountPercentage = discount.percentage;
                }
            });

            return bestDiscountPercentage;
        }

        function calculateDiscountValue() {
            const sub = calculateSubtotal();
            const discInput = Number(state.discountInput);

            const autoDiscountPercentage = getAutomaticDiscount();
            const autoDiscountAmount = sub * (autoDiscountPercentage / 100);

            let manualDiscountAmount = 0;
            if (state.discountType === "percentage") {
                const percentage = Math.min(discInput, 100) / 100;
                manualDiscountAmount = sub * percentage;
            } else {
                manualDiscountAmount = Math.min(discInput, sub);
            }

            if (autoDiscountAmount > manualDiscountAmount) {
                return autoDiscountAmount;
            }

            return manualDiscountAmount;
        }

        function calculateSubtotal() {
            return state.cart.reduce(
                (total, item) => total + item.quantity * item.selling_price,
                0
            );
        }

        function calculateTotal() {
            return calculateSubtotal() - calculateDiscountValue();
        }

        function calculateChange() {
            if (state.paymentMethod === "tunai") {
                const paid = Number(state.amountPaid) || 0;
                return paid - calculateTotal();
            }
            return 0;
        }

        function isReadyToCheckout() {
            if (state.cart.length === 0 || calculateTotal() <= 0) return false;
            if (state.paymentMethod === "tunai") {
                return calculateChange() >= 0;
            }
            return true;
        }

        const elements = {
            productGrid: document.getElementById("product-grid"),
            cartList: document.getElementById("cart-list"),
            summarySection: document.getElementById("summary-section"),
            paymentSection: document.getElementById("payment-section"),
            checkoutArea: document.getElementById("checkout-area"),
            searchInput: document.getElementById("search-input"),
            categoryFilter: document.getElementById("category-filter"),
            emptyCartMessage: document.getElementById("empty-cart-message"),
            totalTagihanSpan: document.getElementById("total-tagihan"),
            kembalianSpan: document.getElementById("kembalian"),
            discountInputEl: document.getElementById("discount-input"),
            discountTypeSelectEl: document.getElementById("discount-type-select"),
            amountPaidInputEl: document.getElementById("amount-paid-input"),
            customerSelectEl: document.getElementById("customer-select"),
            discountDisplaySpan: document.getElementById("discount-display"),
            subtotalValueSpan: document.getElementById("subtotal-value"),
            paymentButtons: document.querySelectorAll('#payment-buttons button'),
        };

        function renderProductGrid() {
            let filteredProducts = state.products;
            // console.log("ALL PRODUCTS:", state.products);


            if (state.selectedCategory) {
                filteredProducts = filteredProducts.filter(
                    (p) => p.category_id === state.selectedCategory
                );
            }

            if (state.searchTerm) {
                const search = state.searchTerm.toLowerCase();
                filteredProducts = filteredProducts.filter((p) =>
                    (p.product_name && p.product_name.toLowerCase().includes(search)) ||
                    (p.sku && p.sku.toLowerCase().includes(search))
                );
            }

            if (filteredProducts.length === 0) {
                elements.productGrid.innerHTML =
                    `<div class="col-span-4 text-center text-gray-500 mt-10">Produk tidak ditemukan.</div>`;
                return;
            }

            elements.productGrid.innerHTML = filteredProducts
                .map(
                    (product) => `
            <div ${product.stock === 0 ? '':`onclick="addToCart(${product.id})"`}
             class=" ${product.stock === 0 ? 'cursor-not-allowed opacity-50':'cursor-pointer hover:shadow-lg'} w-full h-auto border rounded-lg p-3 transition duration-150 
             ${
                 state.cart.some((item) => item.id === product.id)
                     ? "border-blue-600 shadow-md shadow-blue-600"
                     : ""
             }">
            <img src="${
                product.image
                    ? "/storage/" + product.image
                    : "https://via.placeholder.com/150"
            }" 
                 alt="${
                     product.product_name
                 }" class="w-full h-auto object-cover border border-gray-200 shadow-sm rounded-md mb-2">
            <h3 class="font-semibold text-sm truncate my-1">${product.product_name}</h3>
            <p class="text-xs text-gray-500">SKU: ${product.sku || "-"}</p>
            <p class="text-sm font-bold text-blue-600 my-1">Rp ${formatCurrency(
                product.selling_price
            )}</p>
            <span class="text-xs px-2 py-1 rounded-full
                ${product.stock === 0 
                    ? "bg-gray-300 text-gray-700" 
                    : product.stock > product.stock_minimum 
                        ? "bg-green-100 text-green-700" 
                        : "bg-red-100 text-red-700"
                }"
            >
            ${product.stock === 0 ? "Habis" : `${product.stock} Pcs`}
            </span>
        </div>
    `
                )
                .join("");
        }

        function renderCartAndSummary() {
            const subtotal = calculateSubtotal();
            const total = calculateTotal();
            const change = calculateChange();
            const isCartEmpty = state.cart.length === 0;
            const autoDiscountPercentage = getAutomaticDiscount();

            if (isCartEmpty) {
                elements.cartList.innerHTML = `<div class="text-center text-gray-500 mt-10">Keranjang kosong.</div>`;
                elements.summarySection.classList.add("hidden");
                elements.paymentSection.classList.add("hidden");
                elements.checkoutArea.classList.add("hidden");
                return;
            } else {
                elements.summarySection.classList.remove("hidden");
                elements.paymentSection.classList.remove("hidden");
                elements.checkoutArea.classList.remove("hidden");
            }

            elements.cartList.innerHTML = state.cart
                .map(
                    (item) => `
        <div class="flex justify-between items-center mb-3 pb-2 border-b last:border-b-0">
            <div class="flex-1 min-w-0 mr-2">
                <p class="font-semibold text-sm truncate">${item.name}</p>
                <p class="text-xs text-gray-500">Rp ${formatCurrency(
                    item.selling_price
                )}</p>
            </div>
            <div class="flex items-center space-x-1">
                <input type="number" onchange="updateCartQuantity(${
                    item.id
                }, this.value)" value="${item.quantity}" min="1"
                       class="w-10 text-center p-1 text-sm border rounded-md">
                <button onclick="removeFromCart(${
                    item.id
                })" class="text-red-500 ml-4 hover:text-red-700 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="ml-2 font-semibold w-20 text-right text-sm">Rp ${formatCurrency(
                item.quantity * item.selling_price
            )}</p>
        </div>`).join("");

            elements.subtotalValueSpan.textContent = `Rp ${formatCurrency(calculateSubtotal())}`;

            const isDiscountAuto = autoDiscountPercentage > 0;
            elements.discountInputEl.value = state.discountInput;
            elements.discountInputEl.disabled = isDiscountAuto;
            elements.discountInputEl.classList.toggle('bg-gray-100', isDiscountAuto);

            elements.discountTypeSelectEl.value = state.discountType;

            elements.customerSelectEl.innerHTML = `
        <option value="umum">Umum (Tanpa Member)</option>
        ${state.customers.map(customer => `
                                                                                                                                                                                                                                                                                                                                                    <option value="${customer.id}" ${state.selectedCustomer == customer.id ? "selected" : ""}>
                                                                                                                                                                                                                                                                                                                                                    ${customer.name} (Member)
                                                                                                                                                                                                                                                                                                                                                    </option>
                                                                                                                                                                                                                                                                                                                                                `).join("")}
    `;
            elements.customerSelectEl.value = state.selectedCustomer || 'umum';

            const isTunai = state.paymentMethod === "tunai";
            elements.amountPaidInputEl.disabled = !isTunai;
            elements.amountPaidInputEl.value = state.amountPaid;

            const discount = calculateDiscountValue();
            if (autoDiscountPercentage > 0) {
                elements.discountDisplaySpan.innerHTML = autoDiscountPercentage + "%";
            } else {
                elements.discountDisplaySpan.innerHTML =
                    state.discountType === "percentage" ?
                    state.discountInput + "%" :
                    "Rp " + formatCurrency(discount);
            }

            elements.paymentButtons.forEach(btn => {
                const method = btn.getAttribute('data-method');
                if (state.paymentMethod === method) {
                    btn.classList.add("bg-blue-600", "text-white");
                    btn.classList.remove("bg-gray-200");
                } else {
                    btn.classList.remove("bg-blue-600", "text-white");
                    btn.classList.add("bg-gray-200");
                }
            });

            updateTotals();

            elements.checkoutArea.innerHTML = `
        <div class="flex justify-between font-bold text-lg border-t pt-2 mb-4">
            <span>Kembalian:</span>
            <span id="kembalian" class="${
                change < 0 ? "text-red-500" : "text-green-600"
            }">Rp ${formatCurrency(change)}</span>
        </div>
        <button onclick="checkout()" ${!isReadyToCheckout() ? "disabled" : ""}
                class="w-full py-3 font-semibold text-white rounded-lg transition duration-150 ${
                    isReadyToCheckout()
                        ? "bg-blue-600 hover:bg-blue-700 cursor-pointer"
                        : "bg-gray-400 cursor-not-allowed"
                }">
            Bayar & Cetak Struk
        </button>
    `;
        }

        function updateActiveCategoryButton() {
            const buttons = document.querySelectorAll(".category-btn");

            buttons.forEach(btn => {
                const categoryId = btn.getAttribute("data-category-id");

                if (state.selectedCategory === null && btn.id === "all-products-btn") {
                    btn.classList.add("bg-blue-600", "text-white");
                    btn.classList.remove("bg-gray-200", "text-gray-700");
                    return;
                }

                if (categoryId == state.selectedCategory) {
                    btn.classList.add("bg-blue-600", "text-white");
                    btn.classList.remove("bg-gray-200", "text-gray-700");
                } else {
                    btn.classList.remove("bg-blue-600", "text-white");
                    btn.classList.add("bg-gray-200", "text-gray-700");
                }
            });
        }

        function updateTotals() {
            const total = calculateTotal();
            const change = calculateChange();
            const isReady = isReadyToCheckout();

            const kembalianSpan = document.getElementById('kembalian');

            if (elements.totalTagihanSpan) {
                elements.totalTagihanSpan.textContent = `Rp ${formatCurrency(total)}`;
            }

            if (kembalianSpan) {
                kembalianSpan.textContent = `Rp ${formatCurrency(change)}`;
                kembalianSpan.className = change < 0 ? "text-red-500" : "text-green-600";
            }

            const checkoutBtn = elements.checkoutArea.querySelector('button');
            if (checkoutBtn) {
                checkoutBtn.disabled = !isReady;
                checkoutBtn.className =
                    `w-full py-3 font-semibold text-white rounded-lg transition duration-150 ${
                isReady ? "bg-blue-600 hover:bg-blue-700 cursor-pointer" : "bg-gray-400 cursor-not-allowed"
            }`;
            }
        }

        window.updateState = function(key, value) {
            state[key] = value;

            if (key === 'amountPaid' || key === 'discountInput' || key === 'discountType') {
                updateTotals();
            } else {
                renderAll();
            }
        };

        window.addToCart = function(productId) {
            const product = state.products.find((p) => p.id === productId);

            if (product.stock === 0) {
                alert(`${product.product_name} stock sedang habis dan tidak bisa dibeli.`);
                return;
            }

            const existingItem = state.cart.find((item) => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++;
                } else {
                    alert(
                        `Stok maksimal untuk ${product.product_name} adalah ${product.stock}`
                    );
                }
            } else {
                state.cart.push({
                    id: product.id,
                    name: product.product_name,
                    selling_price: product.selling_price,
                    stock: product.stock,
                    quantity: 1,
                });
            }
            // console.log(state.cart);
            state.searchTerm = "";
            renderAll();
        };

        window.removeFromCart = function(productId) {
            state.cart = state.cart.filter((item) => item.id !== productId);
            renderAll();
        };

        window.updateCartQuantity = function(productId, quantity) {
            quantity = Number(quantity);
            const item = state.cart.find((item) => item.id === productId);
            const product = state.products.find((p) => p.id === productId);

            if (item && quantity >= 1) {
                if (quantity > product.stock) {
                    alert(
                        `Stok maksimal untuk ${product.product_name} adalah ${product.stock}`
                    );
                    item.quantity = product.stock;
                } else {
                    item.quantity = quantity;
                }
            } else if (item && quantity < 1) {
                window.removeFromCart(productId);
            }
            renderAll();
        };

        window.selectCategory = function(categoryId) {
            state.selectedCategory = categoryId;
            renderAll();
        };

        window.resetSearch = function() {
            state.searchTerm = "";
            state.selectedCategory = null;

            document.getElementById('reset-btn').classList.add('hidden');

            renderAll();
        };

        window.handleScan = function(e) {
            const scannedSku = elements.searchInput.value;
            const product = state.products.find((p) => p.sku === scannedSku);

            if (product) {
                window.addToCart(product.id);
                elements.searchInput.value = "";
            }
            e.preventDefault();
            elements.searchInput.value = "";
        };

        function renderAll() {
            renderProductGrid();
            renderCartAndSummary();
            updateTotals();
            updateActiveCategoryButton();
        }

        window.checkout = async function() {
            if (!isReadyToCheckout()) return;

            const transactionData = {
                customer_id: state.selectedCustomer || null,
                payment_method: state.paymentMethod,
                discount_amount: Math.round(calculateDiscountValue()),
                subtotal: Math.round(calculateSubtotal()),
                total_amount: Math.round(calculateTotal()),
                amount_paid: Math.round(state.amountPaid),
                change_due: Math.round(calculateChange()),
                items: state.cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    selling_price: item.selling_price
                }))
            };

            // console.log("DATA DIKIRIM:", transactionData);
            try {
                const response = await fetch("/kasir/transactions/store", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    },
                    credentials: "same-origin",
                    body: JSON.stringify(transactionData),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || errorData.message ||
                        `Gagal memproses (Status: ${response.status})`);
                }

                const data = await response.json();

                alert(`Transaksi SUKSES! Invoice: ${data.invoice_number}`);

                window.printReceipt(data.transaction_id);

                resetTransaction();
                window.location.reload();
            } catch (error) {
                alert(`Gagal Checkout: ${error.message}`);
                console.error("Checkout Error:", error);
            }
        };

        window.printReceipt = function(transactionId) {
            const receiptUrl = `/receipt/${transactionId}`;

            const printWindow = window.open(receiptUrl, '_blank');
            if (printWindow) {
                printWindow.focus();
            }
        }

        function resetTransaction() {
            state.cart = [];
            state.selectedCustomer = 'umum';
            state.discountInput = 0;
            state.amountPaid = 0;
            state.paymentMethod = "tunai";
            renderAll();
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderAll();

            elements.searchInput.addEventListener("input", (e) => {
                state.searchTerm = e.target.value;
                const resetBtn = document.getElementById('reset-btn');
                if (state.searchTerm.trim() !== "") {
                    resetBtn.classList.remove('hidden');
                } else {
                    resetBtn.classList.add('hidden');
                }
                renderAll();
            });
        });
    </script>
@endpush
