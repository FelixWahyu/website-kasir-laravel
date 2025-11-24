@extends('layouts.auth-layout')
@section('content')
    <div class="p-2">
        <h2 class="text-3xl font-bold mb-6">Manajemen Users</h2>

        @if (session('success'))
            <div id="alert-success" class="alert-message p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="alert-error" class="alert-message p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('users.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-md mb-8 shadow-sm hover:bg-blue-700">Tambah User</a>

        <div class="overflow-x-auto rounded-lg bg-white shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-2">Nama</th>
                        <th class="p-2">Username</th>
                        <th class="p-2">Email</th>
                        <th class="p-2">Role</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="border-b border-gray-300 p-2 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="border-b border-gray-300 p-2 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="border-b border-gray-300 p-2 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="border-b border-gray-300 p-2 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                            <td class="border-b border-gray-300 p-2 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2 justify-center">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="text-yellow-500 hover:text-yellow-600">Edit</a>

                                    <form action="{{ route('users.delete', $user) }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 cursor-pointer hover:text-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
@endsection
