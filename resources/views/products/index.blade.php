<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Produk</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Produk</a>
                <div class="overflow-x-auto mt-4">
                    <table class="w-full text-left border-collapse">
                        <thead><tr>
                            <th class="border-b p-2">SKU</th>
                            <th class="border-b p-2">Nama Produk</th>
                            <th class="border-b p-2">Kategori</th>
                            <th class="border-b p-2">Harga Jual</th>
                            <th class="border-b p-2">Stok</th>
                            <th class="border-b p-2">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($products as $prod)
                            <tr>
                                <td class="border-b p-2">{{ $prod->sku }}</td>
                                <td class="border-b p-2">{{ $prod->name }}</td>
                                <td class="border-b p-2">{{ $prod->category->name ?? '-' }}</td>
                                <td class="border-b p-2">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                                <td class="border-b p-2">{{ $prod->stock }}</td>
                                <td class="border-b p-2 whitespace-nowrap">
                                    <a href="{{ route('products.edit', $prod) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('products.destroy', $prod) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
