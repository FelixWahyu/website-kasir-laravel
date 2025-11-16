use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
// API untuk menyimpan transaksi dari POS (Kasir)
// Kita gunakan POST karena ini adalah aksi pembuatan data
Route::post('/transactions/store', [TransactionStoreController::class, 'store']);

// API lain bisa ditambahkan di sini (misalnya, untuk pencarian produk cepat)
});