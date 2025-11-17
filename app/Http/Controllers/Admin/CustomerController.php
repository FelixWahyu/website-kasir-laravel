<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:customers,phone_number'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Simpan data pelanggan
        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // 1. Validasi
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:customers,phone_number'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Update data
        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        // Catatan: Jika Anda menerapkan sistem Transaksi, Anda harus mengecek 
        // apakah pelanggan ini memiliki riwayat transaksi sebelum menghapus.

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}
