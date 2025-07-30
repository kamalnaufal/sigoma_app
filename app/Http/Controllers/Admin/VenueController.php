<?php

namespace App\Http\Controllers\Admin;

 use App\Http\Controllers\Controller;
    use App\Models\Venue;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    public function index()
        {
            $venues = Venue::latest()->paginate(10);
            return view('pages.admin.venues.index', compact('venues'));
        }

        public function create()
        {
            return view('pages.admin.venues.create');
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price_per_hour' => 'required|numeric',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('venues', 'public');
            }

            Venue::create($data);

            return redirect()->route('admin.venues.index')->with('success', 'Lapangan berhasil ditambahkan.');
        }

        public function edit(Venue $venue)
        {
            return view('pages.admin.venues.edit', compact('venue'));
        }

        public function update(Request $request, Venue $venue)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price_per_hour' => 'required|numeric',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($venue->photo) {
                    Storage::disk('public')->delete($venue->photo);
                }
                $data['photo'] = $request->file('photo')->store('venues', 'public');
            }

            $venue->update($data);

            return redirect()->route('admin.venues.index')->with('success', 'Data lapangan berhasil diperbarui.');
        }

        public function destroy(Venue $venue)
        {
            // Hapus foto jika ada
            if ($venue->photo) {
                Storage::disk('public')->delete($venue->photo);
            }

            $venue->delete();

            return redirect()->route('admin.venues.index')->with('success', 'Data lapangan berhasil dihapus.');
        }
}
