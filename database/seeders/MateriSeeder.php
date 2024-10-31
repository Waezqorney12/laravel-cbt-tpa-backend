<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materi')->insert([
            [
                'judul' => 'Yasonna Minta Pigai Realistis soal Anggaran Kementerian HAM Rp 20 Triliun',
                'deskripsi' => 'Yasonna menilai, Pigai mempunyai semangat
                yang baik untuk memimpin Kementerian HAM, tetapi ia mengingatkan
                agar Pigai tetap realistis. "Sejak awal tentunya tadi dengan penjelasan
                latar belakang sebagai aktivis orang yang berjuang di jalur HAM semangatnya
                cukup baik dan kita apresiasi tapi semangat aja nggak cukup pak menteri, dari
                pengalaman-pengalaman. Realitas juga kita harus lihat," kata Yasonna dalam rapat
                Komisi XIII DPR RI, Jakarta, Kamis (31/10/2024). Mantan Menteri Hukum dan HAM (Menkumham)
                ini menyoroti APBN yang mengalami defisit. "Maka saya kira apa sebab teman-teman
                dan banyak masyarakat kaget dan apa lompatan angka itu sangat besar,
                ideal mungkin saja ideal, tetapi realita juga harus tetap kita lihat," ujar dia.
                Yasonna pun bercerita bahwa ia harus memimpin Kemenkumham dengan anggaran terbatas.
                Ia mencontohkan, Direktorat Jenderal Pemasyarakat yang memiliki sekitar 35.000 pegawai
                hanya mendapat anggaran Rp 5 triliun. "Tahun 2024 anggarannya itu semua 18,3 triliun yang
                paling besar Direktorat Jenderal Pemasyarakatan dengan hampir 35.000 pegawai existing itu,
                Dirjen PAS dengan segera kompleksitasnya itu hanya sekitar 5 triliun saja," ujar dia.',
                'materi_kategori' => 'Logika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Flutter. What is CLEAN architecture and why you should never use it',
                'deskripsi' => 'Flutter is the preferred tool for cross-platform development due to
                its adaptability and effectiveness, but there are a lot of sophisticated ways that can
                improve the functionality and performance of your project. This tutorial is intended for
                experienced mobile developers who want to advance their Flutter abilities. Here are some
                key Flutter principles that can help you become a more proficient developer: from
                platform-specific code and performance optimisation to complex state management and
                unique animations.',
                'materi_kategori' => 'Verbal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Advanced Flutter Techniques Every Mobile Developer Should Know',
                'deskripsi' => 'For managing different font sizes and other dimensions,
                use the MediaQuery class for responsive design or define constants in a separate
                file to maintain consistency. Additionally, consider using packages like
                flutter_screenutil for scaling sizes based on screen dimensions. While Go has
                its strengths and a strong following, it comes with significant drawbacks that cannot be
                ignored. Its simplicity can be deceptive, leading to verbose and repetitive code.
                The lack of essential features like generics, a cumbersome error handling system,
                and a concurrency model that is easy to misuse make it a challenging language to master
                efficiently. Before you commit to learning Go, weigh these cons carefully against its pros.
                SSL pinning is a security mechanism used by Android applications to prevent man-in-the-middle
                (MITM) attacks. However, as security researchers and pentesters, we often need to bypass SSL
                pinning to analyze and intercept network traffic for security testing purposes.
                In this blog post, we will demonstrate how to bypass SSL pinning in Android applications
                using ReFlutter.',
                'materi_kategori' => 'Numeric',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Why Go is the worst language you could ever learn',
                'deskripsi' => 'Menurut Tito, hal tersebut bisa menjadi salah satu ikhtiar yang
                dilakukan untuk menyempurnakan kembali sistem demokrasi dan juga kepemiluan di Indonesia.
                "Kita tadi yang disampaikan kita mulai memikirkan kembali tentang sistem demokrasi, sistem
                kepemiluan, sistem pilkada, apakah mungkin termasuk ide dari DPR, saya sudah baca juga
                untuk menyusun revisi UU tersebut dalam satu paket omnibus law? Boleh saja,” ujar Tito
                dalam rapat kerja bersama Komisi II DPR RI, Kamis (31/10/2024). “Makanya saya tadi
                mengusulkan ya sudah kita harus mulai berpikir tentang membentuk undang-undang politik
                dengan metodologi omnibus law. Jadi karena itu saling terkait semua ya,” ujar Doli di
                Kompleks Parlemen, Rabu. Doli menyampaikan bahwa sistem politik dan pemilu di Indonesia
                masih perlu disempurnakan, terutama untuk mengatasi persoalan biaya tinggi dan
                kompleksitas pelaksanaan pemilu. “Ayo kita mulai bicara tentang soal menyempurnakan
                sistem politik termasuk sistem pemilu kita. Kan sudah banyak bicara tadi soal
                penyelenggaraan katanya begini, soal biaya mahal politik kita seperti itu.
                Nah itu sudah bisa mulai sebetulnya,” ucap Doli. Menurut Doli, setidaknya ada delapan
                UU terkait sistem pemilu dan politik yang perlu dikaji kembali dan disatukan melalui
                omnibus law. Beberapa di antaranya adalah UU Pemilu, UU Pilkada,
                UU Partai Politik, UU MPR, DPR, DPD dan DPRD (MD3), UU Pemerintah Desa,
                serta UU Hubungan Keuangan Antara Pusat dan Pemerintah Daerah.',
                'materi_kategori' => 'Logika',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
