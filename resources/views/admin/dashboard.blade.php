@extends('layouts.auth-layout')
@section('content')
    <div class="h-screen p-2 overflow-y-auto sm:px-2 lg:px-2">
        <h1 class="text-3xl font-bold mb-8">Dashboard Analytics</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-600">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_transactions']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-600">
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_revenue']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-emerald-600">
                <p class="text-sm text-gray-500">Total Laba Kotor</p>
                <p class="text-2xl font-bold">Rp {{ number_format($summary['total_gross_profit']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-600">
                <p class="text-sm text-gray-500">Total Member</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_members']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-pink-600">
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_users']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-600">
                <p class="text-sm text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold">{{ number_format($summary['total_products']) }}</p>
            </div>
        </div>

        <div class="bg-white grid grid-cols-1 p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-semibold mb-4">Grafik Penjualan Harian</h2>
            <canvas id="salesChart" class="max-h-76"></canvas>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 mt-4 gap-4">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                <h2 class="text-xl font-semibold mb-4">Produk Terlaris (Unit)</h2>
                <table class="min-w-full divide-y divide-gray-200 border-collapse border border-gray-400">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">No</th>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">Produk</th>
                            <th class="px-3 py-2 text-xs text-white border-b border-gray-400">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topSellingProducts as $item)
                            <tr>
                                <td class="text-sm text-center border-b px-2 py-1 border-gray-400">{{ $loop->iteration }}
                                </td>
                                <td class="text-sm px-2 py-1 border-b border-gray-400">{{ $item->product_name }}</td>
                                <td class="text-sm px-2 py-1 font-semibold text-center border-b border-gray-400">
                                    {{ number_format($item->total_sold) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Notifikasi Stok</h2>
                <table class="min-w-full divide-y divide-gray-200 border border-gray-400 border-collapse">
                    <thead class="bg-gray-700">
                        <tr class="border-b border-gray-400">
                            <th class="px-3 py-2 text-xs text-white">Produk</th>
                            <th class="px-3 py-2 text-xs text-white">Stok</th>
                            <th class="px-3 py-2 text-xs text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $item)
                            <tr>
                                <td class="text-left text-sm px-2 py-1 border-b border-gray-400">{{ $item->product_name }}
                                </td>
                                <td class="text-center text-sm px-2 py-1 border-b border-gray-400">{{ $item->stock }}</td>
                                <td class="text-center text-sm px-2 py-1 border-b border-gray-400 font-semibold">
                                    <span
                                        class="p-0.5 rounded-lg {{ $item->status === 'Akan Habis' ? 'text-yellow-500 bg-yellow-50' : 'text-red-500 bg-red-50' }}">{{ $item->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">Stok Aman!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" class="fixed bottom-10 right-10 z-40">

        <button @click="open = !open"
            class="bg-blue-600 flex items-center gap-1 text-white px-4 py-2 cursor-pointer rounded-full shadow-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            <span class="text-sm font-medium">
                Ai Toko
            </span>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-24 right-10 w-80 h-96 bg-white rounded-lg shadow-2xl border flex flex-col overflow-hidden">

            <header class="bg-gray-800 text-white p-3 flex justify-between items-center">
                <h4 class="font-bold">Gemini Ai Toko</h4>
                <button @click="open = false" class="text-white cursor-pointer hover:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </header>

            <div id="chat-output" class="flex-1 p-3 overflow-y-auto space-y-3 bg-gray-50">
                <div class="p-2 bg-gray-200 text-gray-800 rounded-lg self-start text-sm max-w-full">
                    Halo, saya Gemini Ai Toko.
                </div>
            </div>

            <footer class="p-3 border-t">
                <form id="chat-form" class="flex">
                    <input type="text" id="chat-input" placeholder="Tanyakan Gemini Ai Toko..." required
                        class="flex-1 px-3 py-2 border rounded-l-lg focus:ring-blue-500 text-sm">
                    <button type="submit" id="chat-submit-btn"
                        class="px-3 py-2 bg-gray-700 cursor-pointer text-white rounded-r-lg hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
            </footer>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatOutput = document.getElementById('chat-output');
            const chatSubmitBtn = document.getElementById('chat-submit-btn');

            if (!chatForm) return;

            const appendMessage = (sender, text) => {
                const msgDiv = document.createElement('div');
                msgDiv.className = `p-2 rounded-lg text-sm whitespace-pre-wrap w-full ${sender === 'user' 
                    ? 'bg-blue-600 text-white self-start text-left' 
                    : 'bg-gray-200 text-gray-800 self-start text-left'}`;

                msgDiv.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>').replace(/\n/g, '<br>');

                msgDiv.style.maxWidth = '85%';

                chatOutput.appendChild(msgDiv);
                chatOutput.scrollTop = chatOutput.scrollHeight;
            };

            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const userQuery = chatInput.value.trim();
                if (!userQuery) return;

                appendMessage('user', userQuery);
                chatInput.value = '';
                chatSubmitBtn.disabled = true;

                const loadingMessage = document.createElement('div');
                loadingMessage.className =
                    'p-2 rounded-lg text-sm self-start text-left max-w-full bg-gray-200 text-gray-800';
                loadingMessage.innerHTML = 'Sedang Menganalisis data...';
                chatOutput.appendChild(loadingMessage);
                chatOutput.scrollTop = chatOutput.scrollHeight;

                try {
                    const finalResponse = await fetch('/admin/ai', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                        },
                        body: JSON.stringify({
                            query: userQuery
                        })
                    });

                    if (!finalResponse.ok) {
                        throw new Error(`Gagal memproses. Status: ${finalResponse.status}`);
                    }

                    const result = await finalResponse.json();

                    const aiAnswer = result.answer;

                    const boldAnswer = aiAnswer.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

                    loadingMessage.innerHTML = boldAnswer.replace(/\n/g, '<br>');

                } catch (error) {
                    loadingMessage.innerHTML = `Gagal memproses: ${error.message}`;
                    console.error('AI Chat Error:', error);
                } finally {
                    chatSubmitBtn.disabled = false;
                }
            });

            // chatForm.addEventListener('submit', async function(e) {
            //     e.preventDefault();
            //     const userQuery = chatInput.value.trim();
            //     if (!userQuery) return;

            //     appendMessage('user', userQuery);
            //     chatInput.value = '';
            //     chatSubmitBtn.disabled = true;

            //     const loadingMessage = document.createElement('div');
            //     loadingMessage.className =
            //         'p-2 rounded-lg text-sm self-start text-left max-w-full bg-gray-200 text-gray-800';
            //     loadingMessage.innerHTML = 'Sedang Menganalisis data...';
            //     chatOutput.appendChild(loadingMessage);
            //     chatOutput.scrollTop = chatOutput.scrollHeight;

            //     try {
            //         const contextResponse = await fetch('/admin/ai', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                     .content,
            //             },
            //             body: JSON.stringify({
            //                 query: userQuery
            //             })
            //         });

            //         if (!contextResponse.ok) {
            //             throw new Error(
            //                 `Gagal mengambil konteks data dari server: ${contextResponse.status}`);
            //         }

            //         const context = await contextResponse.json();

            //         const payload = {
            //             contents: [{
            //                 parts: [{
            //                     text: context.prompt
            //                 }]
            //             }],
            //             systemInstruction: {
            //                 parts: [{
            //                     text: context.system_instruction
            //                 }]
            //             },
            //         };

            //         let geminiResponse;
            //         if (GEMINI_API_KEY.length > 0) {
            //             geminiResponse = await fetch(GEMINI_API_URL, {
            //                 method: 'POST',
            //                 headers: {
            //                     'Content-Type': 'application/json'
            //                 },
            //                 body: JSON.stringify(payload)
            //             });
            //         } else {
            //             await new Promise(r => setTimeout(r, 1500));
            //             loadingMessage.innerHTML =
            //                 'Mode Simulasi Aktif. Harap masukkan kunci API Gemini Anda.';
            //             return;
            //         }

            //         if (!geminiResponse.ok) {
            //             const errorData = await geminiResponse.json();
            //             throw new Error(errorData.error || errorData.message ||
            //                 `API Error: ${geminiResponse.status}`);
            //         }

            //         const result = await geminiResponse.json();

            //         let aiAnswer = 'Kesalahan dalam format jawaban AI.';
            //         if (result.candidates && result.candidates[0].content.parts[0].text) {
            //             aiAnswer = result.candidates[0].content.parts[0].text;
            //         }

            //         const boldedAnswer = aiAnswer.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');

            //         loadingMessage.innerHTML = boldedAnswer.replace(/\n/g, '<br>');

            //     } catch (error) {
            //         loadingMessage.innerHTML = `Gagal memproses: ${error.message}`;
            //         console.error('AI Chat Error:', error);
            //     } finally {
            //         chatSubmitBtn.disabled = false;
            //     }
            // });

            const ctx = document.getElementById('salesChart');
            if (ctx) {
                const chartData = @json($chartData);
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: chartData.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                            ".");
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.parsed.y !== null) {
                                            return 'Pendapatan: Rp ' + context.parsed.y.toString()
                                                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                        }
                                        return '';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
    </script>
@endpush
