<?php

namespace App\Http\Controllers;

use App\Models\KategoriLowongan;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    // Menampilkan daftar lowongan milik penyedia
    public function index()
    {
    $penyedia = auth()->user()->penyediaProfile;

    $lowongan = $penyedia->lowongan()->latest()->get();

    return view('penyedia.lowongan-index', compact('lowongan'));
    }

    // Menampilkan detail lowongan
    public function show($id)
    {
    $penyedia = auth()->user()->penyediaProfile;

    $lowongan = $penyedia->lowongan()
        ->with('kategori')
        ->findOrFail($id);

    return view('penyedia.lowongan-detail', compact('lowongan'));
    }

    // Menampilkan formulir tambah lowongan
    public function create()
    {
        $kategori = KategoriLowongan::all();

        return view('penyedia.lowongan-create', compact('kategori'));
    }

    // Menyimpan lowongan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_posisi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_lowongan,id',
            'jenis_pekerjaan' => 'required|in:magang,freelance,paruh_waktu',
            'tipe_lokasi' => 'required|in:onsite,remote,hybrid',
            'lokasi_kota' => 'nullable|string|max:255',
            'waktu_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|string',
            'kompensasi' => 'nullable|string|max:255',
        ]);

        $penyedia = auth()->user()->penyediaProfile;

        if (!$penyedia) {
            return back()->with('error', 'Profil penyedia tidak ditemukan.');
        }

        $penyedia->lowongan()->create($validated + [
            'status_aktif' => true,
        ]);


        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', 'Lowongan berhasil ditambahkan.');
    }

    // Menampilkan form edit lowongan
    public function edit($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);
        $kategori = KategoriLowongan::all();

        return view('penyedia.lowongan-edit', compact('lowongan', 'kategori'));
    }

    // Menyimpan perubahan lowongan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul_posisi' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_lowongan,id',
            'jenis_pekerjaan' => 'required|in:magang,freelance,paruh_waktu',
            'tipe_lokasi' => 'required|in:onsite,remote,hybrid',
            'lokasi_kota' => 'nullable|string|max:255',
            'waktu_kerja' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|string',
            'kompensasi' => 'nullable|string|max:255',
        ]);

        $penyedia = auth()->user()->penyediaProfile;
        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->update($validated);

        return redirect()
            ->route('penyedia.lowongan.show', $lowongan->id)
            ->with('success', 'Lowongan berhasil diperbarui.');
    }
    // Mengubah status lowongan
    public function toggleStatus($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->update([
            'status_aktif' => !$lowongan->status_aktif,
        ]);

        $pesan = $lowongan->status_aktif
            ? 'Lowongan berhasil diaktifkan kembali.'
            : 'Lowongan berhasil ditutup.';

        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', $pesan);
    }
    // Menghapus lowongan
    public function destroy($id)
    {
        $penyedia = auth()->user()->penyediaProfile;

        $lowongan = $penyedia->lowongan()->findOrFail($id);

        $lowongan->delete();

        return redirect()
            ->route('penyedia.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }
}
