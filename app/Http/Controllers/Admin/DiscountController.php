<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    // Tampilkan daftar diskon
    public function index()
    {
        $discounts = Discount::latest()->paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }

    // Tampilkan form buat diskon baru
    public function create()
    {
        return view('admin.discounts.create');
    }

    // Simpan diskon baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_total_transaction' => ['required', 'numeric', 'min:0'],
            'max_transactions_count' => ['required', 'integer', 'min:0'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Discount::create($validated);

        return redirect()->route('discounts.index')->with('success', 'Diskon baru berhasil ditambahkan.');
    }

    // Tampilkan form edit diskon
    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    // Update diskon
    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_total_transaction' => ['required', 'numeric', 'min:0'],
            'max_transactions_count' => ['required', 'integer', 'min:0'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $discount->update($validated);

        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil diperbarui.');
    }

    // Hapus diskon
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil dihapus.');
    }

    // Toggle Status Aktif/Nonaktif
    public function toggleStatus(Discount $discount)
    {
        $discount->is_active = !$discount->is_active;
        $discount->save();

        $status = $discount->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Diskon '{$discount->name}' berhasil {$status}.");
    }
}
