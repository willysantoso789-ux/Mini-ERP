@extends('layout.app')

@section('title', 'Wallets')

@section('content')
    <div x-data="walletModal()" @keydown.escape.window="close()" class="p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Wallets</h1>

            <button @click="openCreate()"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition hover:scale-[1.02]">
                + Add Wallet
            </button>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @forelse($wallets as $wallet)
                @php
                    $color = match ($wallet->type) {
                        'cash' => 'bg-green-100',
                        'bank' => 'bg-blue-100',
                        'ewallet' => 'bg-purple-100',
                        default => 'bg-gray-100',
                    };
                @endphp

                <div class="{{ $color }} rounded-xl p-5 shadow hover:shadow-lg transition hover:scale-[1.02]">

                    <!-- TOP -->
                    <div class="flex justify-between items-center">
                        <div class="text-2xl">
                            {{ $wallet->type == 'cash' ? '💵' : ($wallet->type == 'bank' ? '🏦' : ($wallet->type == 'ewallet' ? '📱' : '💰')) }}
                        </div>

                        <button @click='openEdit(@json($wallet))'
                            class="text-sm text-gray-500 hover:bg-gray-200 px-2 py-1 rounded transition duration-200">
                            Edit
                        </button>

                        <button @click='openDelete(@json($wallet))'
                            class="text-red-500 hover:bg-red-200 px-2 py-1 rounded transition duration-200">
                            Delete
                        </button>
                    </div>

                    <!-- NAME -->
                    <h2 class="text-lg font-semibold mt-2">
                        {{ $wallet->name }}
                    </h2>

                    <!-- BALANCE -->
                    <p
                        class="text-2xl font-bold mt-4 
                    {{ ($wallet->balance ?? 0) < 0 ? 'text-red-500' : 'text-green-600' }}">

                        Rp {{ number_format($wallet->balance ?? 0, 0, ',', '.') }}
                    </p>

                </div>

            @empty
                <div class="col-span-3 text-center text-gray-500">
                    No wallets yet. Create one 🚀
                </div>
            @endforelse

        </div>

        <!-- MODAL -->
        <div x-show="open" x-transition.opacity
            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

            <div @click.outside="close()" x-transition.scale class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">

                <!-- ================= CREATE / EDIT ================= -->
                <template x-if="!isDelete">
                    <div>
                        <h2 class="text-xl font-bold mb-4" x-text="isEdit ? 'Edit Wallet' : 'Create Wallet'"></h2>

                        <form method="POST" :action="submitUrl()" class="space-y-4">
                            @csrf

                            <template x-if="isEdit">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <div>
                                <label class="block mb-1">Wallet Name</label>
                                <input type="text" name="name" x-model="form.name"
                                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block mb-1">Type</label>
                                <select name="type" x-model="form.type" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <option value="cash">💵 Cash</option>
                                    <option value="bank">🏦 Bank</option>
                                    <option value="ewallet">📱 E-Wallet</option>
                                    <option value="other">💰 Other</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <template x-if="!isEdit">
                                <div>
                                    <label class="block mb-1">Initial Balance</label>
                                    <input type="number" name="initial_balance" x-model="form.initial_balance"
                                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    @error('initial_balance')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </template>

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="close()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition duration-200">
                                    Cancel
                                </button>

                                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 hover:transition duration-200">
                                    <span x-text="isEdit ? 'Update' : 'Save'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <!-- ================= DELETE ================= -->
                <template x-if="isDelete">
                    <div>
                        <h2 class="text-xl font-bold mb-4 text-red-500">Delete Wallet</h2>

                        <p class="mb-4">
                            Are you sure want to delete
                            <span class="font-semibold" x-text="form.name"></span>?
                        </p>

                        <form method="POST" :action="deleteUrl()">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="close()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition duration-200">
                                    Cancel
                                </button>

                                <button class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 hover:transition duration-200">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>


    <!-- ALPINE SCRIPT -->
    <script>
        function walletModal() {
            return {
                open: {{ $errors->any() ? 'true' : 'false' }},
                isEdit: false,
                isDelete: false,

                form: {
                    id: null,
                    name: '{{ old('name', '') }}',
                    type: '{{ old('type', 'cash') }}',
                    initial_balance: '{{ old('initial_balance', '') }}'
                },

                openCreate() {
                    this.reset();
                    this.isEdit = false;
                    this.open = true;
                },

                openEdit(wallet) {
                    this.reset();
                    this.isEdit = true;
                    this.form = {
                        id: wallet.id,
                        name: wallet.name,
                        type: wallet.type,
                        initial_balance: ''
                    };
                    this.open = true;
                },

                openDelete(wallet) {
                    this.reset();
                    this.isDelete = true;
                    this.form.id = wallet.id;
                    this.form.name = wallet.name;
                    this.open = true;
                },

                close() {
                    this.open = false;
                },

                reset() {
                    this.open = false;
                    this.isEdit = false;
                    this.isDelete = false;
                    this.form = {
                        id: null,
                        name: '',
                        type: 'cash',
                        initial_balance: ''
                    };
                },

                submitUrl() {
                    return this.isEdit ?
                        `/wallets/${this.form.id}` :
                        `/wallets`;
                },

                deleteUrl() {
                    return `/wallets/${this.form.id}`;
                }
            }
        }
    </script>
@endsection
