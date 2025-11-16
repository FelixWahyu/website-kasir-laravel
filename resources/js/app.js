import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();
// DEFINISIKAN FUNGSI POSAPP DI GLOBAL WINDOW SEBELUM START
// window.posApp = function (productsJson, customersJson) {
//     // ... (Sisa kode fungsi posApp yang panjang itu) ...
//     // HANYA BAGIAN `return { ... }`

//     // Parsing JSON yang dikirim dari controller
//     // CATATAN: Karena kita menggunakan @js(), data mungkin sudah berupa objek JS,
//     // jadi kita perlu cek. Mari kita asumsikan ia masih string JSON untuk amannya.
//     const allProducts = JSON.parse(productsJson);
//     const allCustomers = JSON.parse(customersJson);

//     return {
//         // State
//         products: allProducts,
//         customers: allCustomers,
//         searchTerm: "",
//         selectedCategory: null,
//         cart: [],
//         selectedCustomer: "",
//         paymentMethod: "tunai",
//         discountInput: 0,
//         discountType: "fixed", // 'fixed' (Rp) atau 'percentage' (%)
//         amountPaid: 0,

//         // Helpers
//         formatCurrency(value) {
//             if (value === null || value === undefined) return "0";
//             return new Intl.NumberFormat("id-ID").format(Math.round(value));
//         },

//         // Computed Properties (Menggunakan get/set untuk reaktivitas di computed)
//         get discountValue() {
//             const sub = this.cartSubtotal;
//             const discInput = Number(this.discountInput);

//             if (this.discountType === "percentage") {
//                 // Maksimal diskon 100% dari subtotal
//                 const percentage = Math.min(discInput, 100) / 100;
//                 return sub * percentage;
//             }
//             // Diskon fixed (tidak boleh melebihi subtotal)
//             return Math.min(discInput, sub);
//         },

//         get cartSubtotal() {
//             return this.cart.reduce(
//                 (total, item) => total + item.quantity * item.selling_price,
//                 0
//             );
//         },

//         get cartTotal() {
//             return this.cartSubtotal - this.discountValue;
//         },

//         get changeDue() {
//             // Hanya hitung kembalian jika metode tunai
//             if (this.paymentMethod === "tunai") {
//                 const paid = Number(this.amountPaid) || 0;
//                 return paid - this.cartTotal;
//             }
//             return 0; // Kembalian 0 untuk metode non-tunai
//         },

//         get isReadyToCheckout() {
//             if (this.cart.length === 0) return false;
//             if (this.cartTotal <= 0) return false; // Total tidak boleh nol atau negatif

//             if (this.paymentMethod === "tunai") {
//                 return this.changeDue >= 0; // Uang dibayar harus cukup
//             }

//             // Untuk Debit/E-wallet, kita anggap selalu siap jika total > 0
//             return true;
//         },

//         // Filtering Produk
//         get filteredProducts() {
//             let filtered = this.products;

//             // 1. Filter Kategori
//             if (this.selectedCategory) {
//                 filtered = filtered.filter(
//                     (p) => p.category_id === this.selectedCategory
//                 );
//             }

//             // 2. Filter Pencarian (Nama / SKU)
//             if (this.searchTerm) {
//                 const search = this.searchTerm.toLowerCase();
//                 filtered = filtered.filter(
//                     (p) =>
//                         p.product_name.toLowerCase().includes(search) ||
//                         (p.sku && p.sku.toLowerCase().includes(search))
//                 );
//             }

//             return filtered;
//         },

//         // Cart Logic
//         inCart(productId) {
//             return this.cart.some((item) => item.id === productId);
//         },

//         addToCart(product) {
//             const existingItem = this.cart.find(
//                 (item) => item.id === product.id
//             );

//             if (existingItem) {
//                 // Jika sudah ada, tambahkan kuantitas
//                 existingItem.quantity++;
//             } else {
//                 // Jika belum ada, tambahkan item baru
//                 this.cart.push({
//                     id: product.id,
//                     name: product.product_name,
//                     selling_price: product.selling_price,
//                     stock: product.stock, // Simpan stok untuk validasi
//                     quantity: 1,
//                 });
//             }
//             this.searchTerm = ""; // Kosongkan pencarian setelah ditambahkan

//             // Pindah fokus kembali ke input pencarian/scanner (Opsional)
//             // this.$refs.searchInput.focus();
//         },

//         handleScan(e) {
//             const scannedSku = this.searchTerm;
//             const product = this.products.find((p) => p.sku === scannedSku);

//             if (product) {
//                 this.addToCart(product);
//             }
//             // Jika tidak ditemukan, biarkan user mencari
//             e.target.value = ""; // Clear input setelah scan
//             this.searchTerm = "";
//         },

//         removeFromCart(productId) {
//             this.cart = this.cart.filter((item) => item.id !== productId);
//         },

//         updateCartQuantity(item) {
//             // Pastikan kuantitas tidak melebihi stok
//             if (item.quantity > item.stock) {
//                 alert(
//                     `Stok maksimal untuk ${item.product_name} adalah ${item.stock}`
//                 );
//                 item.quantity = item.stock;
//             }
//             if (item.quantity < 1) {
//                 item.quantity = 1;
//             }
//         },

//         selectCategory(categoryId) {
//             this.selectedCategory = categoryId;
//         },

//         resetSearch() {
//             this.searchTerm = "";
//             this.selectedCategory = null;
//         },

//         // Checkout (Placeholder)
//         checkout() {
//             if (!this.isReadyToCheckout) {
//                 alert(
//                     "Transaksi belum siap. Pastikan keranjang terisi dan uang dibayar mencukupi (jika tunai)."
//                 );
//                 return;
//             }

//             const transactionData = {
//                 customer_id: this.selectedCustomer || null,
//                 payment_method: this.paymentMethod,
//                 discount_amount: Math.round(this.discountValue), // Pastikan bulat
//                 subtotal: this.cartSubtotal,
//                 total_amount: this.cartTotal,
//                 amount_paid:
//                     this.paymentMethod === "tunai"
//                         ? this.amountPaid
//                         : this.cartTotal,
//                 change_due: this.changeDue,
//                 items: this.cart.map((item) => ({
//                     product_id: item.id,
//                     quantity: item.quantity,
//                     selling_price: item.selling_price,
//                 })),
//             };

//             // 1. Kirim data ke API Laravel
//             fetch("/api/transactions/store", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     // PENTING: Untuk CSRF, kita perlu mengirim token.
//                     // Jika menggunakan session auth, ambil dari meta tag.
//                     "X-CSRF-TOKEN": document
//                         .querySelector('meta[name="csrf-token"]')
//                         .getAttribute("content"),
//                 },
//                 body: JSON.stringify(transactionData),
//             })
//                 .then((response) => {
//                     // Cek apakah response sukses (status 200)
//                     if (!response.ok) {
//                         // Lempar error jika status bukan 2xx (misalnya 422/500)
//                         return response.json().then((errorData) => {
//                             throw new Error(
//                                 errorData.error ||
//                                     errorData.message ||
//                                     "Error server tidak diketahui."
//                             );
//                         });
//                     }
//                     return response.json();
//                 })
//                 .then((data) => {
//                     // Transaksi Sukses
//                     alert(`Transaksi SUKSES! Invoice: ${data.invoice_number}`);

//                     // 2. Panggil fungsi cetak struk (Langkah berikutnya)
//                     this.printReceipt(data.transaction_id);

//                     // 3. Reset state POS
//                     this.resetTransaction();

//                     // Opsional: Reload daftar produk (untuk update stok)
//                     window.location.reload();
//                 })
//                 .catch((error) => {
//                     alert(`Gagal Checkout: ${error.message}`);
//                     console.error("Checkout Error:", error);
//                 });
//         },

//         resetTransaction() {
//             this.cart = [];
//             this.selectedCustomer = "";
//             this.discountInput = 0;
//             this.amountPaid = 0;
//             this.paymentMethod = "tunai";
//         },
//     };
// };

// import "./pos";
