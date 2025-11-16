// resources/js/pos.js

// Ambil data dari elemen script di Blade
const productsData = JSON.parse(
    document
        .querySelector("script:last-of-type")
        .previousSibling.textContent.match(
            /initialProductsJson\s*=\s*(\{.*?\}|\[.*?\]);/s
        )[1]
);
const customersData = JSON.parse(
    document
        .querySelector("script:last-of-type")
        .previousSibling.textContent.match(
            /initialCustomersJson\s*=\s*(\{.*?\}|\[.*?\]);/s
        )[1]
);

// 1. STATE MANAGEMENT
const state = {
    products: productsData,
    customers: customersData,
    searchTerm: "",
    selectedCategory: null,
    cart: [],
    selectedCustomer: null,
    paymentMethod: "tunai",
    discountInput: 0,
    discountType: "fixed", // 'fixed' (Rp) atau 'percentage' (%)
    amountPaid: 0,
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
                p.name.toLowerCase().includes(search) ||
                (p.sku && p.sku.toLowerCase().includes(search))
        );
    }

    if (filteredProducts.length === 0) {
        elements.productGrid.innerHTML = `<div class="col-span-4 text-center text-gray-500 mt-10">Produk tidak ditemukan.</div>`;
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
                     product.name
                 }" class="w-full h-24 object-cover rounded-md mb-2">
            <h3 class="font-semibold text-sm truncate">${product.name}</h3>
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
    const discount = calculateDiscountValue();
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
                <p class="text-xs text-gray-500">@ Rp ${formatCurrency(
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
                })" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    elements.summarySection.innerHTML = `
        <div class="flex justify-between text-sm">
            <span>Subtotal:</span>
            <span>Rp ${formatCurrency(subtotal)}</span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span>Diskon (${
                state.discountType === "percentage"
                    ? state.discountInput + "%"
                    : "Rp " + formatCurrency(discount)
            }):</span>
            <input type="number" oninput="updateState('discountInput', this.value)" value="${
                state.discountInput
            }"
                   placeholder="Nilai Diskon" class="w-24 text-right px-2 border rounded-md text-sm">
            <select onchange="updateState('discountType', this.value)" class="text-sm border rounded-md">
                <option value="fixed" ${
                    state.discountType === "fixed" ? "selected" : ""
                }>Rp</option>
                <option value="percentage" ${
                    state.discountType === "percentage" ? "selected" : ""
                }>%</option>
            </select>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2">
            <span>Total Tagihan:</span>
            <span>Rp ${formatCurrency(total)}</span>
        </div>
        <div class="border-t pt-2">
            <label for="customer_id" class="block text-sm font-medium mt-2">Pelanggan (Member)</label>
            <select onchange="updateState('selectedCustomer', this.value)" class="w-full mt-1 border rounded-md text-sm">
                <option value="" ${
                    state.selectedCustomer === null ? "selected" : ""
                }>Umum (Tanpa Member)</option>
                ${state.customers
                    .map(
                        (customer) => `
                    <option value="${customer.id}" ${
                            state.selectedCustomer == customer.id
                                ? "selected"
                                : ""
                        }>
                        ${customer.name} (${customer.phone})
                    </option>
                `
                    )
                    .join("")}
            </select>
        </div>
    `;

    // C. Render Payment and Checkout Area
    const isTunai = state.paymentMethod === "tunai";
    elements.paymentSection.innerHTML = `
        <h3 class="font-semibold mb-2">Metode Pembayaran:</h3>
        <div class="flex space-x-2 mb-4">
            ${["tunai", "debit", "e_wallet"]
                .map(
                    (method) => `
                <button onclick="updateState('paymentMethod', '${method}')" 
                        class="flex-1 py-2 text-sm rounded-lg capitalize transition duration-150 ${
                            state.paymentMethod === method
                                ? "bg-green-600 text-white"
                                : "bg-gray-200"
                        }">
                    ${method.replace("_", " ")}
                </button>
            `
                )
                .join("")}
        </div>
        <div class="mb-4">
            <label for="amount_paid_input" class="block text-sm font-medium">Uang Dibayar (Tunai)</label>
            <input type="number" oninput="updateState('amountPaid', this.value)" value="${
                state.amountPaid
            }" id="amount_paid_input" ${isTunai ? "" : "disabled"}
                   placeholder="0" class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-blue-500">
        </div>
    `;

    // D. Render Checkout Button
    elements.checkoutArea.innerHTML = `
        <div class="flex justify-between font-bold text-lg border-t pt-2 mb-4">
            <span>Kembalian:</span>
            <span class="${
                change < 0 ? "text-red-500" : "text-green-600"
            }">Rp ${formatCurrency(change)}</span>
        </div>
        <button onclick="checkout()" ${!isReadyToCheckout() ? "disabled" : ""}
                class="w-full py-3 font-semibold text-white rounded-lg transition duration-150 ${
                    isReadyToCheckout()
                        ? "bg-blue-600 hover:bg-blue-700"
                        : "bg-gray-400 cursor-not-allowed"
                }">
            Bayar & Cetak Struk
        </button>
    `;
}

// 4. GLOBAL ACTION FUNCTIONS (Dipanggil dari HTML onclick)

// Global helper to update state and re-render
window.updateState = function (key, value) {
    state[key] = value;
    renderAll();
};

window.addToCart = function (productId) {
    const product = state.products.find((p) => p.id === productId);
    const existingItem = state.cart.find((item) => item.id === productId);

    if (existingItem) {
        if (existingItem.quantity < product.stock) {
            existingItem.quantity++;
        } else {
            alert(
                `Stok maksimal untuk ${product.name} adalah ${product.stock}`
            );
        }
    } else {
        state.cart.push({
            id: product.id,
            name: product.name,
            selling_price: product.selling_price,
            stock: product.stock,
            quantity: 1,
        });
    }
    state.searchTerm = "";
    renderAll();
};

window.removeFromCart = function (productId) {
    state.cart = state.cart.filter((item) => item.id !== productId);
    renderAll();
};

window.updateCartQuantity = function (productId, quantity) {
    quantity = Number(quantity);
    const item = state.cart.find((item) => item.id === productId);
    const product = state.products.find((p) => p.id === productId);

    if (item && quantity >= 1) {
        if (quantity > product.stock) {
            alert(
                `Stok maksimal untuk ${product.name} adalah ${product.stock}`
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

window.selectCategory = function (categoryId) {
    state.selectedCategory = categoryId;
    // Tambahkan logika untuk mengubah kelas button category (opsional)
    renderAll();
};

window.resetSearch = function () {
    state.searchTerm = "";
    state.selectedCategory = null;
    renderAll();
};

window.handleScan = function (e) {
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
    // Tambahkan logika untuk menyorot tombol kategori yang aktif (opsional)
}

// Global checkout function (menggunakan fetch API)
window.checkout = async function () {
    // ... (Kode fetch API dari langkah sebelumnya, menggunakan variabel global `state`)
    // ... (Pastikan Anda menambahkan kembali meta tag CSRF ke layout!)
    if (!isReadyToCheckout()) return;

    const transactionData = {
        customer_id: state.selectedCustomer || null,
        payment_method: state.paymentMethod,
        discount_amount: Math.round(calculateDiscountValue()),
        subtotal: calculateSubtotal(),
        total_amount: calculateTotal(),
        amount_paid:
            state.paymentMethod === "tunai"
                ? state.amountPaid
                : calculateTotal(),
        change_due: calculateChange(),
        items: state.cart.map((item) => ({
            product_id: item.id,
            quantity: item.quantity,
            selling_price: item.selling_price,
        })),
    };

    // Kirim data ke API Laravel
    try {
        const response = await fetch("/api/transactions/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(transactionData),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(
                errorData.error ||
                    errorData.message ||
                    "Error server tidak diketahui."
            );
        }

        const data = await response.json();
        alert(`Transaksi SUKSES! Invoice: ${data.invoice_number}`);

        // Panggil fungsi cetak struk (Langkah berikutnya)
        window.printReceipt(data.transaction_id);
        resetTransaction();
        window.location.reload(); // Reload untuk refresh stok produk
    } catch (error) {
        alert(`Gagal Checkout: ${error.message}`);
        console.error("Checkout Error:", error);
    }
};

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
