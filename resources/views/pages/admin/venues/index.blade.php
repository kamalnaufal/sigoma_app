<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Lapangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                   Daftar Lapangan
                   <a href="{{ route('admin.venues.create') }}" class="btn btn-primary">Tambah Lapangan</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Harga/Jam</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($venues as $venue)
                                    <tr>
                                        <td>{{ $venues->firstItem() + $loop->index }}</td>
                                        <td>
                                            <img src="{{ $venue->photo ? Storage::url($venue->photo) : 'https://placehold.co/100x100?text=No+Image' }}" alt="{{ $venue->name }}" width="100">
                                        </td>
                                        <td>{{ $venue->name }}</td>
                                        <td>Rp {{ number_format($venue->price_per_hour) }}</td>
                                        <td>
                                            <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.venues.destroy', $venue) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $venues->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
