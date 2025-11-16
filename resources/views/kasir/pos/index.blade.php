@extends('layouts.auth-layout')
@section('content')
    <div class="h-full overflow-y-auto">
        <h1 class="text-2xl font-bold mb-4">Sistem Kasir - {{ Auth::user()->name }}</h1>

        <div id="pos-container" class="flex flex-col px-2 py-2 lg:flex-row gap-6 h-full">

            {{-- 1. Kolom Kiri: Produk & Kategori (8/12) --}}
            <div class="flex flex-col lg:w-8/12 bg-white p-4 rounded-lg shadow-lg h-[80vh] lg:h-full">
                <div class="flex mb-4 gap-2">
                    {{-- Tambahkan ID dan Event Listener --}}
                    <input type="text" id="search-input" placeholder="Cari Produk / Scan SKU..."
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500"
                        onkeydown="if(event.key === 'Enter') handleScan(event)">
                    <button onclick="resetSearch()"
                        class="px-4 py-2 bg-gray-200 cursor-pointer rounded-lg hover:bg-gray-300">Reset</button>
                </div>

                {{-- Filter Kategori --}}
                <div id="category-filter" class="mb-4 flex space-x-2 overflow-x-auto pb-2 custom-scroll">
                    {{-- Konten Kategori akan di-render oleh JS --}}
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

                {{-- Kontainer Daftar Produk --}}
                <div id="product-grid"
                    class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 overflow-y-auto pr-2 custom-scroll flex-1">
                    {{-- Produk akan di-render di sini --}}
                </div>
            </div>

            {{-- 2. Kolom Kanan: Keranjang & Checkout (4/12) --}}
            <div class="flex flex-col lg:w-4/12 bg-white p-4 rounded-lg shadow-lg h-auto lg:h-full">
                <div id="cart-wrapper">
                    <h2 class="text-xl font-bold mb-4">Keranjang Belanja</h2>

                    {{-- Kontainer Daftar Keranjang --}}
                    <div id="cart-list" class="overflow-y-auto max-h-64 border-b pb-4 mb-4 custom-scroll">
                        <div id="empty-cart-message" class="text-center text-gray-500 mt-10">
                            Keranjang kosong.
                        </div>
                        {{-- Item keranjang akan di-render di sini --}}
                    </div>

                    {{-- Ringkasan Transaksi --}}
                    <div id="summary-section" class="space-y-2 mb-4 hidden">
                        <div id="subtotal-display" class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span id="subtotal-value">Rp 0</span>
                        </div>

                        {{-- DISKON (Jadikan Statis) --}}
                        <div class="flex justify-between items-center text-sm">
                            <span>Diskon (<span id="discount-display">Rp 0</span>):</span>
                            <input type="number" id="discount-input" oninput="updateState('discountInput', this.value)"
                                placeholder="Nilai Diskon" value="0"
                                class="w-24 text-right px-2 border rounded-md text-sm">
                            <select id="discount-type-select" onchange="updateState('discountType', this.value)"
                                class="text-sm border rounded-md">
                                <option value="fixed">Rp</option>
                                <option value="percentage">%</option>
                            </select>
                        </div>

                        {{-- Total Tagihan (Span dengan ID BARU) --}}
                        <div class="flex justify-between font-bold text-lg border-t pt-2">
                            <span>Total Tagihan:</span>
                            <span id="total-tagihan">Rp 0</span>
                        </div>

                        {{-- Pemilihan Pelanggan (Select Statis) --}}
                        <div class="border-t pt-2">
                            <label for="customer-select" class="block text-sm font-medium mt-2">Pelanggan (Member)</label>
                            {{-- customer-select adalah ID yang kita butuhkan untuk Label fix --}}
                            <select onchange="updateState('selectedCustomer', this.value)" id="customer-select"
                                class="w-full mt-1 border rounded-md text-sm">
                                <option value="">Umum (Tanpa Member)</option>
                                {{-- OPTION member biarkan di-render oleh JS jika perlu --}}
                            </select>
                        </div>
                    </div>

                    {{-- Area Pembayaran --}}
                    <div id="payment-section" class="hidden">
                        <h3 class="font-semibold mb-2">Metode Pembayaran:</h3>
                        <div id="payment-buttons" class="flex space-x-2 mb-4">
                            {{-- Tombol Payment bisa di-render atau statis. Kita buat statis/setengah statis --}}
                            <button onclick="updateState('paymentMethod', 'tunai')" data-method="tunai"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-blue-600 text-white">Tunai</button>
                            <button onclick="updateState('paymentMethod', 'debit')" data-method="debit"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-gray-200">Debit</button>
                            <button onclick="updateState('paymentMethod', 'e_wallet')" data-method="e_wallet"
                                class="flex-1 py-2 text-sm cursor-pointer rounded-lg capitalize transition duration-150 bg-gray-200">E
                                Wallet</button>
                        </div>

                        {{-- UANG DIBAYAR (Jadikan Statis) --}}
                        <div class="mb-4">
                            <label for="amount-paid-input" class="block text-sm font-medium">Uang Dibayar (Tunai)</label>
                            {{-- amount-paid-input adalah ID yang kita butuhkan --}}
                            <input type="number" oninput="updateState('amountPaid', this.value)" value="0"
                                id="amount-paid-input" placeholder="0"
                                class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Tombol Checkout (Dorong ke Bawah) --}}
                <div id="checkout-area" class="mt-auto hidden">
                    {{-- Konten Kembalian dan Tombol Checkout akan di-render di sini --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        window.POS_DATA = {
            products: @json($productsJson),
            customers: @json($customersJson)
        };
        // Data PHP dari Controller di-inject ke JavaScript
        const initialProductsJson = @json($productsJson);
        const initialCustomersJson = @json($customersJson);

        // resources/js/pos.js

        // Ambil data dari elemen script di Blade
        const productsData = JSON.parse(window.POS_DATA.products || '[]');
        const customersData = JSON.parse(window.POS_DATA.customers || '[]');

        // 1. STATE MANAGEMENT
        const state = {
            // Gunakan data yang sudah diparse
            products: productsData,
            customers: customersData,
            searchTerm: '',
            selectedCategory: null,
            cart: [],
            // ... (sisa state lainnya tetap sama)
            selectedCustomer: null,
            paymentMethod: 'tunai',
            discountInput: 0,
            amountPaid: 0,
            discountType: 'fixed',
        };

        // 2. HELPER FUNCTIONS
        function formatCurrency(value) {
            if (value === null || value === undefined) return "0";
            return new Intl.NumberFormat("id-ID").format(Math.round(value));
        }

        function calculateDiscountValue() {
            const sub = calculateSubtotal();
            const discInput = Number(state.discountInput);

            if (state.discountType === "percentage") {
                const percentage = Math.min(discInput, 100) / 100;
                return sub * percentage;
            }
            return Math.min(discInput, sub);
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

        // 3. DOM MANIPULATION / RENDER FUNCTIONS
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
            discountDisplaySpan: document.getElementById("discount-display"), // Span display diskon
            subtotalValueSpan: document.getElementById("subtotal-value"), // Span display subtotal
            paymentButtons: document.querySelectorAll('#payment-buttons button'),
        };

        function renderProductGrid() {
            let filteredProducts = state.products;

            // 1. Filter Kategori
            if (state.selectedCategory) {
                filteredProducts = filteredProducts.filter(
                    (p) => p.category_id === state.selectedCategory
                );
            }

            // 2. Filter Pencarian (Nama / SKU)
            if (state.searchTerm) {
                const search = state.searchTerm.toLowerCase();
                filteredProducts = filteredProducts.filter(
                    (p) =>
                    p.product_name.toLowerCase().includes(search) ||
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
        <div onclick="addToCart(${product.id})"
             class="cursor-pointer border rounded-lg p-3 hover:shadow-lg transition duration-150 relative 
             ${
                 state.cart.some((item) => item.id === product.id)
                     ? "border-blue-500 ring-2 ring-blue-500"
                     : ""
             }">
            <img src="${
                product.image
                    ? "/storage/" + product.image
                    : "https://via.placeholder.com/150"
            }" 
                 alt="${
                     product.product_name
                 }" class="w-full h-24 object-cover rounded-md mb-2">
            <h3 class="font-semibold text-sm truncate">${product.product_name}</h3>
            <p class="text-xs text-gray-500">SKU: ${product.sku || "-"}</p>
            <p class="text-sm font-bold text-green-600">Rp ${formatCurrency(
                product.selling_price
            )}</p>
            <span class="absolute top-1 right-1 text-xs px-2 py-0.5 rounded-full ${
                product.stock <= product.stock_minimum
                    ? "bg-red-100 text-red-700"
                    : "bg-green-100 text-green-700"
            }">
                ${product.stock} Pcs
            </span>
        </div>
    `
                )
                .join("");
        }

        function renderCartAndSummary() {
            const subtotal = calculateSubtotal();
            // const discount = calculateDiscountValue();
            const total = calculateTotal();
            const change = calculateChange();
            const isCartEmpty = state.cart.length === 0;

            // A. Render Cart List
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
                       class="w-10 text-center text-sm border rounded-md">
                <button onclick="removeFromCart(${
                    item.id
                })" class="text-red-500 hover:text-red-700 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.86 11.14A2 2 0 0116.14 20H7.86a2 2 0 01-1.92-1.86L5 7m5 4v6m4-6v6m4-10H5"/>
                    </svg>
                </button>
            </div>
            <p class="ml-4 font-semibold w-20 text-right text-sm">Rp ${formatCurrency(
                item.quantity * item.selling_price
            )}</p>
        </div>
    `
                )
                .join("");

            // B. Render Summary Section
            // 1. Update Subtotal Display
            elements.subtotalValueSpan.textContent = `Rp ${formatCurrency(calculateSubtotal())}`;

            // 2. Update Nilai Input Diskon (Jaga Fokus)
            elements.discountInputEl.value = state.discountInput;

            // 3. Update Dropdown Diskon (Perbaiki Seleksi)
            elements.discountTypeSelectEl.value = state.discountType;

            // 4. Update Dropdown Customer
            // Render OPSI customer di sini. Hanya render OPTIONS, bukan SELECT-nya.
            elements.customerSelectEl.innerHTML = `
        <option value="">Umum (Tanpa Member)</option>
        ${state.customers.map(customer => `
                <option value="${customer.id}" ${state.selectedCustomer == customer.id ? "selected" : ""}>
                    ${customer.name} (${customer.phone_number || 'N/A'})
                </option>
            `).join("")}
    `;
            elements.customerSelectEl.value = state.selectedCustomer; // Pastikan seleksi benar

            // 5. Update Input Uang Dibayar
            const isTunai = state.paymentMethod === "tunai";
            elements.amountPaidInputEl.disabled = !isTunai;
            elements.amountPaidInputEl.value = state.amountPaid;

            // 6. Update Display Diskon (Display di samping label)
            const discount = calculateDiscountValue();
            elements.discountDisplaySpan.innerHTML =
                `${state.discountType === "percentage" ? state.discountInput + "%" : "Rp " + formatCurrency(discount)}`;

            // 7. Update Tombol Payment Style
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

            // Panggil updateTotals secara eksplisit untuk memperbarui Kembalian/Total
            updateTotals();

            // D. Render Checkout Button
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

        // 4. GLOBAL ACTION FUNCTIONS (Dipanggil dari HTML onclick)

        function updateActiveCategoryButton() {
            const buttons = document.querySelectorAll(".category-btn");

            buttons.forEach(btn => {
                const categoryId = btn.getAttribute("data-category-id");

                // Active untuk "Semua Produk"
                if (state.selectedCategory === null && btn.id === "all-products-btn") {
                    btn.classList.add("bg-blue-600", "text-white");
                    btn.classList.remove("bg-gray-200", "text-gray-700");
                    return;
                }

                // Active untuk kategori lain
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

            // 2. Update Kembalian
            if (kembalianSpan) {
                kembalianSpan.textContent = `Rp ${formatCurrency(change)}`;
                kembalianSpan.className = change < 0 ? "text-red-500" : "text-green-600";
            }

            // 3. Update Tombol Checkout
            const checkoutBtn = elements.checkoutArea.querySelector('button');
            if (checkoutBtn) {
                checkoutBtn.disabled = !isReady;
                checkoutBtn.className =
                    `w-full py-3 font-semibold text-white rounded-lg transition duration-150 ${
                isReady ? "bg-blue-600 hover:bg-blue-700 cursor-pointer" : "bg-gray-400 cursor-not-allowed"
            }`;
            }
        }

        // Global helper to update state and re-render
        window.updateState = function(key, value) {
            state[key] = value;

            // Jika input yang sedang diketik
            if (key === 'amountPaid' || key === 'discountInput' || key === 'discountType') {
                updateTotals(); // HANYA panggil fungsi update yang ringan
            } else {
                // Untuk dropdown/tombol lain
                renderAll();
            }
        };

        window.addToCart = function(productId) {
            const product = state.products.find((p) => p.id === productId);
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
                // Hapus jika kuantitas kurang dari 1
                window.removeFromCart(productId);
            }
            renderAll();
        };

        window.selectCategory = function(categoryId) {
            state.selectedCategory = categoryId;
            // Tambahkan logika untuk mengubah kelas button category (opsional)
            renderAll();
        };

        window.resetSearch = function() {
            state.searchTerm = "";
            state.selectedCategory = null;
            renderAll();
        };

        window.handleScan = function(e) {
            const scannedSku = elements.searchInput.value;
            const product = state.products.find((p) => p.sku === scannedSku);

            if (product) {
                window.addToCart(product.id);
                elements.searchInput.value = ""; // Clear input setelah scan
            }
            e.preventDefault(); // Mencegah form submit
            elements.searchInput.value = "";
        };

        // 5. INITIATION & MAIN RENDER LOOP
        function renderAll() {
            renderProductGrid();
            renderCartAndSummary();
            updateTotals();
            updateActiveCategoryButton();
            // Tambahkan logika untuk menyorot tombol kategori yang aktif (opsional)
        }

        // Global checkout function (menggunakan fetch API)
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

                // 1. Cek jika respons TIDAK OK (misalnya 401, 422, atau 500)
                if (!response.ok) {
                    // Ambil respons JSON (untuk 422 validation error dari Laravel)
                    const errorData = await response.json();
                    throw new Error(errorData.error || errorData.message ||
                        `Gagal memproses (Status: ${response.status})`);
                }

                // 2. Jika respons OK (200), ambil data JSON
                const data = await response.json();

                // --- LOGIKA SUKSES ---
                alert(`Transaksi SUKSES! Invoice: ${data.invoice_number}`);

                // 3. Panggil fungsi cetak struk
                window.printReceipt(data.transaction_id);

                // 4. Reset dan Reload
                resetTransaction();
                window.location.reload();
            } catch (error) {
                // Tangani semua kesalahan (termasuk gagal fetch atau gagal response.ok)
                alert(`Gagal Checkout: ${error.message}`);
                console.error("Checkout Error:", error);
            }

            // const result = await response.json();
            // console.log(result);
            // .then(data => {
            //     // Transaksi Sukses
            //     alert(`Transaksi SUKSES! Invoice: ${data.invoice_number}`);

            //     // 2. Panggil fungsi cetak struk
            //     window.printReceipt(data.transaction_id); // <-- Panggil fungsi baru

            //     // 3. Reset state POS
            //     resetTransaction();
            //     window.location.reload();
            // })
        };

        window.printReceipt = function(transactionId) {
            // Bangun URL menggunakan rute yang sudah kita buat
            const receiptUrl = `/receipt/${transactionId}`; // Sesuai dengan rute /receipt/{transaction}

            // Buka di tab baru dan fokuskan
            const printWindow = window.open(receiptUrl, '_blank');
            if (printWindow) {
                printWindow.focus();
            }
        }

        function resetTransaction() {
            state.cart = [];
            state.selectedCustomer = null;
            state.discountInput = 0;
            state.amountPaid = 0;
            state.paymentMethod = "tunai";
            renderAll();
        }

        // Inisialisasi saat DOM siap
        document.addEventListener("DOMContentLoaded", () => {
            // Jalankan render awal
            renderAll();

            // Event listener untuk search input (agar DOM Manipulation tidak memutus alur)
            elements.searchInput.addEventListener("input", (e) => {
                state.searchTerm = e.target.value;
                renderAll();
            });
        });
    </script>
@endpush
