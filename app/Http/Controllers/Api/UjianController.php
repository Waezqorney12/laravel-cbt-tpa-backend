<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SoalResource;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\UjianSoalList;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function createUjian(Request $request)
    {
        // Soal angka
        $soalAngka = Soal::where('kategori', 'Numeric')->inRandomOrder()->limit(20)->get();
        $soalVerbal = Soal::where('kategori', 'Verbal')->inRandomOrder()->limit(20)->get();
        $soalLogika = Soal::where('kategori', 'Logika')->inRandomOrder()->limit(20)->get();

        $ujian = Ujian::create([
            'user_id' => $request->user()->id,
        ]);

        foreach ($soalAngka as $soal) {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id' => $soal->id,
            ]);
        }
        foreach ($soalVerbal as $soal) {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id' => $soal->id
            ]);
        }

        foreach ($soalLogika as $soal) {
            UjianSoalList::create([
                'ujian_id' => $ujian->id,
                'soal_id' => $soal->id
            ]);
        }

        return response()->json([
            'message' => 'Soal berhasil dibuat',
            'data' => $ujian
        ], 200);
    }
    public function getListSoalByKategori(Request $request)
    {
        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)->get();
        $soalIds = $ujianSoalList->pluck('soal_id');

        $soal = Soal::whereIn('id', $soalIds)->where('kategori', $request->kategori)->get();
        return response()->json([
            'message' => 'Berhasil mendapatkan soal',
            'data' => SoalResource::collection($soal)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function jawabSoal(Request $request)
    {
        $validateData = $request->validate([
            'soal_id' => 'required',
            'jawaban' => 'required',
        ]);

        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianList = UjianSoalList::where('ujian_id', $ujian->id)->where('soal_id', $validateData['soal_id'])->first();
        $soal = Soal::where('id', $validateData['soal_id'])->first();

        if ($soal->kunci == $validateData['jawaban']) {
            $ujianList->update([
                'kebenaran' => true
            ]);
        } else {
            $ujianList->update([
                'kebenaran' => false
            ]);
        }
        return response()->json([
            'message' => 'Berhasil simpan jawaban',
            'jawaban' => $ujianList->kebenaran
        ]);
    }

    public function hitungNilaiUjianByKategori(Request $request)
    {
        $kategori = $request->kategori;
        $ujian = Ujian::where('user_id', $request->user()->id)->first();
        $ujianSoalList = UjianSoalList::where('ujian_id', $ujian->id)->get();
        // Ujian Soal List by kategori
        $ujianSoalList = $ujianSoalList->filter(function ($value, $key) use ($kategori) {
            return $value->soal->kategori == $kategori;
        });

        // Hitung nilai
        $totalBenar = $ujianSoalList->where('kebenaran', true)->count();
        $totalSoal = $ujianSoalList->count();
        $nilai = ($totalBenar / $totalSoal) * 100;

        $kategori_field = 'nilai_verbal';
        switch ($kategori) {
            case 'Numeric':
                $kategori_field = 'nilai_angka';
                break;
            case 'Logika':
                $kategori_field = 'nilai_logika';
                break;
        }

        // Update nilai
        $ujian->update([
            $kategori_field => $nilai
        ]);

        return response()->json([
            'message' => 'Berhasil menghitung nilai',
            'kategori' => $kategori_field,
            'nilai' => $nilai
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
