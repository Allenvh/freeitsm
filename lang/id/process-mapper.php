<?php
/**
 * Bahasa Indonesia (id) — Process Mapper module strings.
 *
 * Mirrors lang/en/process-mapper.php structure exactly. Only values change.
 */
return [
    'title' => 'Process Mapper',

    'nav' => [
        'processes' => 'Proses',
        'settings'  => 'Pengaturan',
        'help'      => 'Bantuan',
    ],

    'sidebar' => [
        'new_process'        => '+ Proses Baru',
        'search_placeholder' => 'Cari proses...',
        'no_processes_yet'   => 'Belum ada proses',
    ],

    'toolbar' => [
        'process'   => 'Proses',
        'decision'  => 'Keputusan',
        'terminal'  => 'Terminal',
        'document'  => 'Dokumen',
        'connect'   => 'Hubungkan',
        'group'     => 'Grup',
        'lane'      => 'Jalur',
        'export'    => 'Ekspor',
        'save'      => 'Simpan',
    ],

    'context' => [
        'create_new' => 'Buat baru…',
    ],

    'shapes' => [
        'rectangle'     => 'Persegi panjang',
        'rounded'       => 'Membulat',
        'pill'          => 'Pil',
        'circle'        => 'Lingkaran',
        'diamond'       => 'Belah ketupat',
        'parallelogram' => 'Jajaran genjang',
        'trapezoid'     => 'Trapesium',
        'hexagon'       => 'Segi enam',
        'document'      => 'Dokumen',
        'cylinder'      => 'Silinder',
        'cloud'         => 'Awan',
        'subroutine'    => 'Subrutin',
    ],

    'settings' => [
        'title'            => 'Tipe Langkah',
        'intro'            => 'Tentukan tipe blok yang tersedia di bilah alat Process Mapper. Setiap tipe adalah nama, bentuk, dan warna.',
        'add_type'         => 'Tambah tipe',
        'col_order'        => 'Urutan',
        'col_shape'        => 'Bentuk',
        'col_name'         => 'Nama',
        'col_colour'       => 'Warna',
        'col_active'       => 'Aktif',
        'col_actions'      => 'Tindakan',
        'none'             => 'Belum ada tipe langkah',
        'builtin'          => 'Bawaan',
        'yes'              => 'Ya',
        'no'               => 'Tidak',
        'add_title'        => 'Tambah Tipe Langkah',
        'edit_title'       => 'Edit Tipe Langkah',
        'field_name'       => 'Nama',
        'field_shape'      => 'Bentuk',
        'field_colour'     => 'Warna',
        'field_active'     => 'Aktif',
        'name_placeholder' => 'mis. Basis data, Langkah manual',
        'name_required'    => 'Nama wajib diisi',
        'saved'            => 'Tersimpan',
        'deleted'          => 'Dihapus',
        'delete_confirm'   => 'Hapus tipe langkah ini?',
    ],

    'autosave' => [
        'label'   => 'Simpan Otomatis',
        'saved'   => 'Tersimpan',
        'unsaved' => 'Belum disimpan',
        'unsaved_changes' => 'Perubahan belum disimpan',
        'saving'  => 'Menyimpan…',
        'failed'  => 'Penyimpanan gagal —',
        'retry'   => 'coba lagi',
        'off'     => 'Simpan otomatis nonaktif',
        'tooltip' => 'Menyimpan otomatis setiap beberapa detik setelah Anda berhenti mengedit',
    ],

    'detail' => [
        'step_title'   => 'Detail Langkah',
        'group_title'  => 'Detail Grup',
        'lane_title'   => 'Detail Jalur',
        'label'        => 'Label',
        'type'         => 'Tipe',
        'colour'       => 'Warna',
        'gradient'     => 'Gradien',
        'description'  => 'Deskripsi',
        'position'     => 'Posisi',
        'size'         => 'Ukuran',
        'height'       => 'Tinggi',
        'order'        => 'Urutan (atas ke bawah)',
        'connectors'   => 'Konektor',
        'no_connectors'=> 'Tidak ada konektor',
        'step_type' => [
            'process'  => 'Proses',
            'decision' => 'Keputusan',
            'terminal' => 'Terminal (Mulai/Akhir)',
            'document' => 'Dokumen',
        ],
        'step_description_placeholder' => 'Tambahkan catatan tentang langkah ini...',
        'lane_label_placeholder'       => 'misal, HR / IT / Vendor',
        'group_label_placeholder'      => 'misal, Tahap penyelesaian',
        'lane_hint'                    => 'Seret header tepi-kiri jalur untuk mengatur ulang urutan. Seret tepi bawah untuk mengubah ukuran. Lepaskan langkah ke dalam pita untuk menetapkannya ke jalur ini.',
    ],

    'export_modal' => [
        'title'  => 'Ekspor — Bagan alir Mermaid',
        'hint'   => 'Tempel markup ini ke editor Markdown apa pun yang mendukung Mermaid (GitHub, GitLab, Notion, Confluence, Obsidian…). Jalur menjadi blok <code>subgraph</code>; tata letak otomatis menggantikan posisi yang Anda tempatkan secara manual.',
        'copy'   => 'Salin',
        'copied' => 'Tersalin ✓',
        'close'  => 'Tutup',
    ],

    'toast' => [
        'no_process_open' => 'Buka atau buat proses terlebih dahulu',
        'saved'           => 'Tersimpan',
        'save_failed'     => 'Gagal menyimpan',
    ],
];
