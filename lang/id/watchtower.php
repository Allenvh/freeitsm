<?php
/**
 * Bahasa Indonesia (id) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Dasbor',
        'help'      => 'Bantuan',
    ],

    'dashboard' => [
        'heading'      => 'Ikhtisar Perhatian',
        'refresh'      => 'Segarkan',
        'updated'      => 'Diperbarui {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Pemeriksaan Pagi',
        'tickets'        => 'Tiket',
        'changes'        => 'Perubahan',
        'calendar'       => 'Kalender',
        'service_status' => 'Status Layanan',
        'contracts'      => 'Kontrak',
        'knowledge'      => 'Pengetahuan',
        'assets'         => 'Aset',
        'tasks'          => 'Tugas',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Selesai',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Peringatan',
        'metric_fail' => 'Gagal',
        'not_started'      => 'Pemeriksaan belum dimulai hari ini',
        'pending'          => '{count} pemeriksaan masih tertunda',
        'failed'           => '{count} pemeriksaan gagal',
        'warnings'         => '{count} pemeriksaan dengan peringatan',
        'all_passing'      => 'Semua pemeriksaan selesai dan lolos',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Terbuka',
        'metric_new'    => 'Baru',
        'metric_active' => 'Aktif',
        'metric_hold'   => 'Ditahan',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> tiket prioritas mendesak/tinggi',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> tiket belum ditugaskan',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> tiket dijeda lebih dari {hours} jam (jam SLA dihentikan)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> tiket dijeda lebih dari {hours} jam (jam SLA dihentikan)',
        'all_clear'     => 'Tidak ada item mendesak',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => '7 hari ke depan',
        'metric_active'  => 'Aktif',
        'metric_pending' => 'Tertunda',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> perubahan menunggu persetujuan',
        'in_progress'    => '{count} perubahan sedang berlangsung sekarang',
        'scheduled'      => '{count} perubahan dijadwalkan minggu ini',
        'all_clear'      => 'Tidak ada perubahan mendatang',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Hari ini',
        'metric_week'  => 'Minggu ini',
        'all_day'      => 'Sepanjang hari',
        'no_events'    => 'Tidak ada acara hari ini',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Semua sistem beroperasi',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> insiden aktif',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 hari',
        'metric_90d'     => '90 hari',
        'metric_notices' => 'Pemberitahuan',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> kontrak akan berakhir dalam 30 hari',
        'notices'        => '<span class="wt-attention-bold">{count}</span> periode pemberitahuan sudah mendekat',
        'all_clear'      => 'Tidak ada kontrak yang memerlukan perhatian',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artikel terlambat untuk ditinjau',
        'published_week'  => 'Diterbitkan minggu ini',
        'up_to_date'      => 'Basis pengetahuan sudah terkini',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Total',
        'metric_offline'  => 'Luring',
        'metric_warranty' => 'Garansi',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> aset dengan garansi yang sudah atau akan berakhir dalam {days} hari',
        'offline'         => '<span class="wt-attention-bold">{count}</span> aset tidak terlihat selama 7+ hari',
        'all_active'      => 'Semua aset baru-baru ini aktif',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Akan Dikerjakan',
        'metric_active' => 'Aktif',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> tugas terlambat',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> jatuh tempo hari ini',
        'all_clear'     => 'Tidak ada tugas terlambat',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Panduan Watchtower',
        'sidebar_label' => 'Panduan',
        'hero_title'   => 'Panduan Watchtower',
        'hero_subtitle' => 'Dasbor perhatian terpadu yang menampilkan item yang dapat ditindaklanjuti dari setiap modul dalam satu pandangan.',

        'nav_overview'  => 'Ikhtisar',
        'nav_layout'    => 'Tata letak dasbor',
        'nav_dots'      => 'Memahami titik status',
        'nav_cards'     => 'Penjelasan kartu modul',
        'nav_refresh'   => 'Penyegaran otomatis',
        'nav_tips'      => 'Tips singkat',

        // Section 1 — Overview
        's1_title' => 'Ikhtisar',
        's1_intro' => 'Watchtower adalah satu panel kaca Anda untuk operasi TI. Alih-alih membuka setiap modul satu per satu untuk memeriksa item mendesak, Watchtower menarik informasi terpenting dari setiap modul ke dalam satu dasbor. Sekilas Anda dapat melihat apa yang perlu diperhatikan, apa yang berjalan lancar, dan di mana harus memfokuskan waktu Anda.',
        's1_feat1_title' => 'Papan perhatian',
        's1_feat1_desc'  => 'Lihat apa yang membutuhkan fokus Anda di semua modul dalam satu tempat. Pemeriksaan pagi, tiket, perubahan, acara kalender, status layanan, kontrak, artikel pengetahuan, dan aset semuanya diringkas dalam satu layar.',
        's1_feat2_title' => 'Status berkode warna',
        's1_feat2_desc'  => 'Setiap kartu modul menampilkan titik status hijau, kuning, atau merah untuk triase instan. Anda dapat mengetahui sekilas area mana yang sehat, mana yang perlu perhatian, dan mana yang memerlukan tindakan segera.',
        's1_feat3_title' => 'Penyegaran otomatis',
        's1_feat3_desc'  => 'Dasbor secara otomatis menyegar setiap 5 menit, sehingga informasi tetap terkini tanpa tindakan manual apa pun. Biarkan Watchtower tetap terbuka dan ia akan terus memperbarui dirinya sendiri di latar belakang.',
        's1_feat4_title' => 'Klik-tembus',
        's1_feat4_desc'  => 'Lompat langsung ke modul mana pun dari kartunya. Setiap nama modul adalah tautan yang dapat diklik yang membawa Anda langsung ke area yang relevan, sehingga Anda dapat menindaklanjuti masalah tanpa mencari halaman yang tepat.',

        // Section 2 — Dashboard layout
        's2_title' => 'Tata letak dasbor',
        's2_p1' => 'Dasbor Watchtower menggunakan kisi 3 kolom responsif berisi kartu modul. Pada layar yang lebih kecil, kisi beradaptasi menjadi 2 kolom atau satu kolom, sehingga berfungsi pada perangkat apa pun. Di atas kisi terdapat bilah judul dengan tombol segarkan dan stempel waktu "Diperbarui" yang menunjukkan kapan data terakhir diambil.',
        's2_p2' => 'Setiap kartu dalam kisi mengikuti struktur yang konsisten sehingga Anda dapat memindainya dengan cepat:',
        's2_diagram_name'   => 'Nama Modul',
        's2_diagram_open'   => 'TERBUKA',
        's2_diagram_active' => 'AKTIF',
        's2_diagram_hold'   => 'DITAHAN',
        's2_diagram_clear'  => 'Semua aman — tidak ada item mendesak',
        's2_field_icon'    => '<strong>Ikon berwarna</strong> &mdash; ikon kotak kecil dalam warna tema modul (teal untuk Pemeriksaan Pagi, biru untuk Tiket, dll.) sehingga Anda dapat mengenali setiap kartu secara instan.',
        's2_field_name'    => '<strong>Nama modul</strong> &mdash; tautan yang dapat diklik yang menavigasi langsung ke modul tersebut. Klik untuk langsung masuk dan mengambil tindakan.',
        's2_field_dot'     => '<strong>Titik status</strong> &mdash; titik hijau, kuning, atau merah di pojok kanan atas yang menunjukkan tingkat urgensi keseluruhan untuk modul tersebut.',
        's2_field_metrics' => '<strong>Metrik utama</strong> &mdash; angka besar yang meringkas jumlah terpenting (mis. tiket terbuka, pemeriksaan selesai, kontrak yang akan berakhir).',
        's2_field_attention' => '<strong>Item perhatian</strong> &mdash; baris pesan berkode warna yang menyoroti apa yang secara khusus memerlukan perhatian Anda dalam modul tersebut.',
        's2_tip' => 'Tata letak kartu dirancang untuk pemindaian, bukan analisis mendalam. Gunakan Watchtower untuk mengidentifikasi modul mana yang memerlukan perhatian Anda, lalu klik-tembus ke modul itu sendiri untuk detail lengkap.',

        // Section 3 — Status dots
        's3_title' => 'Memahami titik status',
        's3_intro' => 'Setiap kartu modul menampilkan titik status di tajuknya. Titik ini memberikan indikator visual instan apakah area operasi TI tersebut perlu perhatian. Warnanya ditentukan secara otomatis berdasarkan data yang dikembalikan dari setiap modul.',
        's3_green_label' => 'Hijau',
        's3_green_desc'  => 'Semuanya baik-baik saja. Tidak ada tindakan yang diperlukan. Modul dalam keadaan sehat tanpa masalah yang belum terselesaikan atau item yang memerlukan perhatian.',
        's3_green_examples' => '<strong>Contoh:</strong> Semua pemeriksaan pagi lolos, tidak ada tiket mendesak, semua sistem beroperasi, tidak ada kontrak yang akan segera berakhir.',
        's3_amber_label' => 'Kuning',
        's3_amber_desc'  => 'Ada sesuatu yang perlu perhatian tetapi tidak kritis. Ada item yang sebaiknya Anda tinjau saat ada kesempatan, tetapi tidak ada yang darurat.',
        's3_amber_examples' => '<strong>Contoh:</strong> Pemeriksaan dengan peringatan, tiket belum ditugaskan, perubahan menunggu persetujuan, kontrak yang akan berakhir dalam 90 hari.',
        's3_red_label' => 'Merah',
        's3_red_desc'  => 'Item mendesak memerlukan tindakan segera. Ada sesuatu yang gagal, terlambat, atau terdampak secara kritis dan perlu segera ditangani.',
        's3_red_examples' => '<strong>Contoh:</strong> Pemeriksaan pagi belum dimulai atau gagal, tiket prioritas mendesak/tinggi, gangguan layanan besar, kontrak yang akan berakhir dalam 30 hari.',
        's3_tip' => 'Anggap titik-titik itu seperti lampu lalu lintas. Hijau berarti lanjutkan hari Anda, kuning berarti tinjau jika memungkinkan, dan merah berarti hentikan apa yang Anda lakukan dan selidiki. Tujuannya adalah menjaga semua titik tetap hijau.',

        // Section 4 — Module cards explained
        's4_title' => 'Penjelasan kartu modul',
        's4_intro' => 'Watchtower memantau delapan modul. Setiap kartu disesuaikan untuk menampilkan informasi yang paling relevan untuk area tersebut. Berikut ini yang ditampilkan setiap kartu dan apa yang memicu warna titik statusnya.',
        's4_mc_title'    => 'Pemeriksaan Pagi',
        's4_mc_desc'     => 'Menampilkan progres penyelesaian (mis. 8/10 selesai) plus jumlah hasil OK, Peringatan, dan Gagal. Item perhatian ditandai saat pemeriksaan belum dimulai atau saat ada yang gagal.',
        's4_mc_triggers' => '<strong>Merah:</strong> Pemeriksaan belum dimulai hari ini, atau ada pemeriksaan yang gagal. <strong>Kuning:</strong> Pemeriksaan belum lengkap atau ada peringatan. <strong>Hijau:</strong> Semua pemeriksaan selesai dan lolos.',
        's4_tk_title'    => 'Tiket',
        's4_tk_desc'     => 'Menampilkan total jumlah tiket terbuka yang dirinci menjadi Baru, Aktif, dan Ditahan. Item perhatian menyoroti tiket prioritas mendesak/tinggi dan tiket yang belum ditugaskan.',
        's4_tk_triggers' => '<strong>Merah:</strong> Ada tiket prioritas mendesak atau tinggi. <strong>Kuning:</strong> Ada tiket yang belum ditugaskan. <strong>Hijau:</strong> Tidak ada item mendesak atau tiket yang belum ditugaskan.',
        's4_ch_title'    => 'Perubahan',
        's4_ch_desc'     => 'Menampilkan jumlah perubahan yang dijadwalkan dalam 7 hari ke depan, berapa banyak yang sedang berlangsung, dan berapa banyak yang menunggu persetujuan. Item perhatian menyoroti perubahan yang belum disetujui dan yang aktif.',
        's4_ch_triggers' => '<strong>Kuning:</strong> Perubahan menunggu persetujuan. <strong>Hijau:</strong> Tidak ada perubahan yang belum disetujui.',
        's4_cal_title'    => 'Kalender',
        's4_cal_desc'     => 'Menampilkan jumlah acara hari ini dan minggu ini. Jika ada acara hari ini, semuanya dicantumkan dengan waktunya (atau "Sepanjang hari" untuk acara seharian).',
        's4_cal_triggers' => '<strong>Kuning:</strong> Acara dijadwalkan untuk hari ini. <strong>Hijau:</strong> Tidak ada acara hari ini.',
        's4_ss_title'    => 'Status Layanan',
        's4_ss_desc'     => 'Menampilkan jumlah insiden aktif dan mencantumkan layanan yang terdampak beserta lencana tingkat dampaknya (Gangguan Besar, Gangguan Sebagian, Menurun, Pemeliharaan). Saat semuanya sehat, muncul spanduk hijau "Semua sistem beroperasi".',
        's4_ss_triggers' => '<strong>Merah:</strong> Gangguan besar atau sebagian pada layanan mana pun. <strong>Kuning:</strong> Status menurun atau pemeliharaan. <strong>Hijau:</strong> Semua sistem beroperasi.',
        's4_ct_title'    => 'Kontrak',
        's4_ct_desc'     => 'Menampilkan kontrak yang akan berakhir dalam 30 hari, dalam 90 hari, dan periode pemberitahuan yang mendekat. Item perhatian memperingatkan tentang masa berakhir yang sudah dekat dan tenggat pemberitahuan yang akan datang.',
        's4_ct_triggers' => '<strong>Merah:</strong> Kontrak yang akan berakhir dalam 30 hari. <strong>Kuning:</strong> Kontrak yang akan berakhir dalam 90 hari atau periode pemberitahuan yang mendekat. <strong>Hijau:</strong> Tidak ada kontrak yang memerlukan perhatian.',
        's4_kb_title'    => 'Pengetahuan',
        's4_kb_desc'     => 'Menampilkan jumlah artikel yang terlambat untuk ditinjau dan mencantumkan artikel yang baru diterbitkan minggu ini. Saat tidak ada tinjauan yang terlambat dan basis pengetahuan terkini, kartu menampilkan pesan semua aman.',
        's4_kb_triggers' => '<strong>Kuning:</strong> Artikel terlambat untuk ditinjau. <strong>Hijau:</strong> Basis pengetahuan sudah terkini.',
        's4_as_title'    => 'Aset',
        's4_as_desc'     => 'Menampilkan total jumlah aset yang dilacak dan berapa banyak yang tidak terlihat selama 7 hari atau lebih. Ini membantu mengidentifikasi perangkat yang mungkin luring, dinonaktifkan, atau hilang.',
        's4_as_triggers' => '<strong>Kuning:</strong> Aset tidak terlihat selama 7+ hari. <strong>Hijau:</strong> Semua aset baru-baru ini aktif.',

        // Section 5 — Auto-refresh
        's5_title' => 'Penyegaran otomatis dan penyegaran manual',
        's5_intro' => 'Watchtower dirancang sebagai alat pemantauan pasif yang dapat Anda biarkan terbuka di tab peramban sepanjang hari. Dasbor menjaga dirinya tetap terkini melalui siklus penyegaran otomatis.',
        's5_step1' => '<strong>Penyegaran otomatis</strong> &mdash; dasbor mengambil data terbaru dari semua modul setiap 5 menit. Anda tidak perlu memuat ulang halaman atau mengeklik apa pun; kartu dan titik status diperbarui secara diam-diam di latar belakang.',
        's5_step2' => '<strong>Penyegaran manual</strong> &mdash; klik tombol <strong>Segarkan</strong> di pojok kanan atas untuk mengambil data terbaru dengan segera. Ikon tombol berputar selama permintaan berlangsung, menandakan bahwa data baru sedang dimuat.',
        's5_step3' => '<strong>Stempel waktu diperbarui</strong> &mdash; di samping tombol segarkan, stempel waktu menunjukkan kapan terakhir data diambil (mis. "Diperbarui 09:15"). Ini memberi tahu Anda persis seberapa terkini informasi yang ditampilkan.',
        's5_tip' => 'Biarkan Watchtower terbuka di tab peramban khusus untuk pemantauan pasif. Siklus penyegaran 5 menit berarti Anda selalu memiliki tampilan operasi TI yang nyaris real-time tanpa perlu memeriksa setiap modul secara manual.',

        // Section 6 — Quick tips
        's6_title' => 'Tips singkat',
        's6_tip1_title' => 'Mulai hari Anda di sini',
        's6_tip1_desc'  => 'Buka Watchtower hal pertama setiap pagi untuk ikhtisar operasional yang cepat. Dalam hitungan detik Anda dapat melihat apakah pemeriksaan pagi sudah selesai, apakah ada tiket yang mendesak, dan apakah semua layanan sehat.',
        's6_tip2_title' => 'Titik merah dulu',
        's6_tip2_desc'  => 'Tangani titik status merah sebelum hal lainnya. Titik ini menunjukkan item mendesak yang membutuhkan perhatian segera &mdash; pemeriksaan yang gagal, tiket prioritas tinggi, atau gangguan layanan yang sedang berdampak pada pengguna.',
        's6_tip3_title' => 'Klik untuk masuk',
        's6_tip3_desc'  => 'Klik nama modul mana pun pada kartu untuk menavigasi langsung ke modul tersebut. Tidak perlu menggunakan menu utama atau navigasi wafel &mdash; Watchtower berfungsi sebagai pintasan langsung ke mana pun perhatian dibutuhkan.',
        's6_tip4_title' => 'Tekan Segarkan untuk yang terbaru',
        's6_tip4_desc'  => 'Meskipun dasbor menyegar otomatis setiap 5 menit, Anda dapat mengeklik tombol Segarkan kapan saja Anda menginginkan data paling terkini. Berguna setelah menyelesaikan masalah untuk memastikan titik status telah berubah.',
        's6_tip5_title' => 'Gunakan dalam rapat tim',
        's6_tip5_desc'  => 'Proyeksikan Watchtower ke layar saat rapat stand-up atau rapat tinjauan operasional. Titik berkode warna memudahkan untuk mendiskusikan area mana yang perlu perhatian dan menetapkan kepemilikan item kuning atau merah.',
        's6_tip6_title' => 'Hijau berarti semua aman',
        's6_tip6_desc'  => 'Saat setiap titik pada dasbor berwarna hijau, operasi TI Anda dalam kondisi baik. Tidak ada tiket mendesak, tidak ada pemeriksaan yang gagal, tidak ada kontrak yang akan berakhir, dan semua layanan beroperasi. Itulah tujuannya.',
    ],
];
