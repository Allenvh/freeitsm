<?php
/**
 * Bahasa Indonesia (id) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Pelaporan',

    'nav' => [
        'logs'    => 'Log',
        'tickets' => 'Tiket',
        'intune'  => 'Intune',
        'help'    => 'Bantuan',

        'logs_title'    => 'Log Sistem',
        'tickets_title' => 'Dasbor Tiket',
        'intune_title'  => 'Dasbor Intune',
        'help_title'    => 'Bantuan',
    ],

    'landing' => [
        'heading'  => 'Pelaporan',
        'subtitle' => 'Pilih area pelaporan untuk memulai',

        'logs_title'    => 'Log Sistem',
        'logs_desc'     => 'Lihat upaya login, impor email, dan log aktivitas sistem lainnya.',
        'tickets_title' => 'Dasbor Tiket',
        'tickets_desc'  => 'Dasbor KPI untuk kinerja tiket, waktu penyelesaian, dan beban kerja tim.',
        'intune_title'  => 'Dasbor Intune',
        'intune_desc'   => 'Kepatuhan, enkripsi, distribusi OS, tren pendaftaran, dan kesehatan sinkronisasi terakhir di seluruh perangkat terkelola.',
    ],

    'logs' => [
        'heading'  => 'Log sistem',
        'refresh'  => 'Segarkan',
        'tab_login'        => 'Login pengguna',
        'tab_email_import' => 'Impor email',

        'loading'        => 'Memuat log...',
        'no_logs'        => 'Tidak ada log ditemukan',
        'load_error'     => 'Gagal memuat log: {error}',

        'col_datetime'    => 'Tanggal/waktu',
        'col_username'    => 'Nama pengguna',
        'col_status'      => 'Status',
        'col_ip'          => 'Alamat IP',
        'col_user_agent'  => 'User agent',
        'col_from'        => 'Dari',
        'col_subject'     => 'Subjek',
        'col_type'        => 'Tipe',
        'col_attachments' => 'Lampiran',

        'status_success' => 'Berhasil',
        'status_failed'  => 'Gagal',
        'unknown'        => 'Tidak diketahui',
        'no_subject'     => '(Tanpa Subjek)',
        'new_ticket'     => 'Tiket Baru',
        'reply'          => 'Balasan',
        'none'           => 'Tidak ada',

        'row_title'  => 'Klik untuk melihat detail JSON',

        'pagination' => 'Halaman {current} dari {total} ({count} total)',
        'prev'       => 'Sebelumnya',
        'next'       => 'Berikutnya',

        'modal_title' => 'Detail Log (JSON)',
        'close'       => 'Tutup',
    ],

    'tickets' => [
        'heading' => 'Dasbor Tiket',
        'coming_soon' => 'Dasbor KPI dan pelaporan untuk kinerja tiket, waktu penyelesaian, dan beban kerja tim akan segera tersedia di sini.',
    ],

    'intune' => [
        'heading'      => 'Dasbor Intune',
        'loading_meta' => 'Memuat…',
        'refresh'      => 'Segarkan',
        'refresh_title'=> 'Segarkan data',
        'loading_data' => 'Memuat data Intune…',

        'last_sync'    => 'Sinkronisasi terakhir: {when}',
        'error'        => 'Kesalahan: {error}',
        'load_failed'  => 'Gagal memuat dasbor: {error}',
        'no_devices_title' => 'Tidak ada perangkat Intune ditemukan.',
        'no_devices_body'  => 'Jalankan sinkronisasi Intune dari modul Aset untuk mengimpor perangkat, lalu kembali ke sini.',
        'no_data'      => 'Tidak ada data',
        'unknown'      => 'Tidak diketahui',

        // KPI strip
        'kpi_total'            => 'Total Perangkat',
        'kpi_total_sub'        => 'Semua perangkat terkelola',
        'kpi_compliant'        => 'Patuh',
        'kpi_compliant_sub'    => '{count} dari {total}',
        'kpi_encrypted'        => 'Terenkripsi',
        'kpi_encrypted_sub'    => '{count} dari {total}',
        'kpi_stale'            => 'Usang (30+ hari)',
        'kpi_stale_sub'        => 'Tidak ada sinkronisasi dalam 30 hari terakhir',
        'kpi_enrolled'         => 'Terdaftar (30 hari)',
        'kpi_enrolled_sub'     => 'Baru dalam 30 hari terakhir',

        'kpi_compliant_drill'  => 'Perangkat patuh',
        'kpi_encrypted_drill'  => 'Perangkat terenkripsi',
        'kpi_stale_drill'      => 'Usang (30+ hari)',
        'kpi_enrolled_drill'   => 'Terdaftar dalam 30 hari terakhir',

        // Widgets
        'w_compliance_title'   => 'Rincian Kepatuhan',
        'w_compliance_desc'    => 'Perangkat menurut status kepatuhan',
        'w_os_title'           => 'Sistem Operasi',
        'w_os_desc'            => 'Perangkat dikelompokkan menurut OS',
        'w_owner_title'        => 'Tipe Pemilik',
        'w_owner_desc'         => 'Perangkat korporat vs pribadi',
        'w_manufacturers_title'=> 'Produsen Teratas',
        'w_manufacturers_desc' => 'Perangkat menurut produsen (10 teratas)',
        'w_os_versions_title'  => 'Versi OS Teratas',
        'w_os_versions_desc'   => 'Kombinasi OS + versi yang paling umum',
        'w_last_sync_title'    => 'Jendela Sinkronisasi Terakhir',
        'w_last_sync_desc'     => 'Seberapa baru perangkat melakukan check-in',
        'w_enrolment_title'    => 'Pendaftaran (90 hari terakhir)',
        'w_enrolment_desc'     => 'Perangkat baru yang didaftarkan per hari',
        'w_encryption_title'   => 'Enkripsi menurut OS',
        'w_encryption_desc'    => 'Terenkripsi vs tidak terenkripsi, per OS',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} terdaftar (klik untuk merinci)',
        'drill_enrolled_on'    => 'Terdaftar pada {date}',

        // Drill-down modal
        'drill_devices'        => 'Perangkat',
        'drill_loading'        => 'Memuat…',
        'drill_count'          => '{count} perangkat',
        'drill_count_plural'   => '{count} perangkat',
        'drill_no_match'       => 'Tidak ada perangkat yang cocok dengan filter ini.',
        'drill_error'          => 'Kesalahan: {error}',
        'drill_load_failed'    => 'Gagal memuat: {error}',
        'drill_page_info'      => 'Halaman {current} dari {total}',
        'drill_prev'           => '‹ Sebelumnya',
        'drill_next'           => 'Berikutnya ›',
        'drill_export'         => 'Ekspor CSV',
        'drill_close'          => 'Tutup',

        'drill_col_device'     => 'Perangkat',
        'drill_col_user'       => 'Pengguna',
        'drill_col_os'         => 'OS',
        'drill_col_compliance' => 'Kepatuhan',
        'drill_col_encrypted'  => 'Terenkripsi',
        'drill_col_last_sync'  => 'Sinkronisasi Terakhir',

        'never'                => 'Tidak pernah',
        'yes'                  => 'Ya',
        'no'                   => 'Tidak',
    ],

    'help' => [
        'page_title' => 'Panduan Pelaporan',
        'guide'      => 'Panduan',

        'hero_heading' => 'Panduan pelaporan',
        'hero_sub'     => 'Ubah data service desk Anda menjadi wawasan yang dapat ditindaklanjuti dengan log, analitik, dan dasbor.',

        'nav_overview'           => 'Ikhtisar',
        'nav_ticket_reports'     => 'Laporan tiket',
        'nav_system_logs'        => 'Log sistem',
        'nav_understanding_data' => 'Memahami data',
        'nav_settings_filters'   => 'Pengaturan & filter',
        'nav_tips'               => 'Tips singkat',

        // Section 1: Overview
        's1_heading' => 'Ikhtisar',
        's1_intro'   => 'Modul Pelaporan menyatukan semua yang terjadi di service desk Anda ke dalam satu tempat. Lacak kinerja tiket, pantau aktivitas sistem, tinjau upaya login, dan audit impor email — semuanya dari satu modul yang dirancang untuk membantu Anda menemukan tren dan mengambil keputusan berbasis data.',
        's1_card1_title' => 'Analitik tiket',
        's1_card1_body'  => 'Visualisasikan volume tiket, waktu penyelesaian, kepatuhan SLA, dan beban kerja tim melalui dasbor interaktif yang diperbarui secara real time.',
        's1_card2_title' => 'Log sistem',
        's1_card2_body'  => 'Tinjau setiap upaya login, impor email, dan peristiwa sistem dalam tabel yang dapat dicari dan difilter dengan stempel waktu serta indikator status.',
        's1_card3_title' => 'Pelacakan aktivitas',
        's1_card3_body'  => 'Pantau aktivitas analis di seluruh platform — siapa yang login, tiket apa yang sedang dikerjakan, dan di mana waktu dihabiskan.',
        's1_card4_title' => 'Jejak audit',
        's1_card4_body'  => 'Setiap tindakan dicatat beserta siapa yang melakukannya, kapan, dan apa yang berubah. Penting untuk kepatuhan, tinjauan keamanan, dan pemecahan masalah.',

        // Section 2: Ticket reports
        's2_heading' => 'Laporan tiket',
        's2_intro'   => 'Area Tiket pada pelaporan menyediakan dasbor KPI yang memberi Anda gambaran jelas tentang kinerja service desk Anda. Dasbor ini menarik data langsung dari catatan tiket Anda dan menyajikannya melalui grafik dan kartu ringkasan.',
        's2_card1_title' => 'Volume tiket',
        's2_card1_body'  => 'Lihat berapa banyak tiket yang dibuat, diselesaikan, dan masih terbuka selama periode waktu apa pun. Identifikasi hari-hari sibuk dan pola musiman.',
        's2_card2_title' => 'Kepatuhan SLA',
        's2_card2_body'  => 'Lacak berapa persen tiket yang memenuhi target respons dan penyelesaiannya. Rinci menurut prioritas atau kategori untuk menemukan area bermasalah.',
        's2_card3_title' => 'Waktu penyelesaian',
        's2_card3_body'  => 'Ukur waktu rata-rata dan median untuk menyelesaikan tiket. Bandingkan antar tim, kategori, atau tingkat prioritas untuk menemukan hambatan.',
        's2_card4_title' => 'Beban kerja tim',
        's2_card4_body'  => 'Lihat bagaimana tiket didistribusikan di antara para analis. Identifikasi siapa yang kelebihan beban dan siapa yang masih punya kapasitas untuk mengambil lebih banyak pekerjaan.',
        's2_card5_title' => 'Rincian kategori',
        's2_card5_body'  => 'Pahami jenis masalah mana yang menghasilkan tiket paling banyak. Gunakan ini untuk menargetkan pelatihan, dokumentasi, atau peningkatan layanan mandiri.',
        's2_card6_title' => 'Analisis tren',
        's2_card6_body'  => 'Lihat data tiket selama beberapa minggu, bulan, atau kuartal untuk menemukan tren jangka panjang dan mengukur dampak perbaikan proses.',
        's2_tip'         => 'Dasbor tiket diakses melalui tab Tiket di navigasi header. Gunakan filter rentang tanggal untuk membandingkan periode yang berbeda secara berdampingan.',

        // Section 3: System logs
        's3_heading' => 'Log sistem',
        's3_intro'   => 'Area Log menangkap semua yang terjadi di balik layar pada instans FreeITSM Anda. Setiap upaya login, impor email, dan peristiwa sistem dicatat dengan stempel waktu dan status sehingga Anda selalu memiliki gambaran lengkap tentang aktivitas platform.',
        's3_badge_login'  => 'LOGIN',
        's3_badge_email'  => 'EMAIL',
        's3_badge_system' => 'SISTEM',
        's3_badge_audit'  => 'AUDIT',
        's3_login_title'  => 'Upaya login',
        's3_login_body'   => 'Setiap login yang berhasil dan gagal dicatat beserta nama analis, alamat IP, dan stempel waktu. Upaya yang gagal ditandai dengan warna merah sehingga Anda dapat dengan cepat menemukan upaya akses tidak sah atau pengguna yang terkunci.',
        's3_email_title'  => 'Impor email',
        's3_email_body'   => 'Ketika sistem memproses email masuk menjadi tiket, setiap impor dicatat beserta alamat pengirim, baris subjek, dan apakah berhasil dikonversi. Impor yang gagal menampilkan alasannya sehingga Anda dapat menyelidiki pesan yang terpental atau tidak valid.',
        's3_system_title' => 'Peristiwa sistem',
        's3_system_body'  => 'Proses latar belakang, tugas terjadwal, perubahan konfigurasi, dan aktivitas API semuanya ditangkap di sini. Gunakan log ini untuk memverifikasi bahwa tugas otomatis berjalan dengan benar dan untuk mendiagnosis masalah.',
        's3_audit_title'  => 'Entri audit',
        's3_audit_body'   => 'Pelacakan perubahan tingkat bidang di seluruh platform. Lihat persis siapa yang mengubah apa, kapan, dan apa nilai sebelumnya. Sangat berharga untuk persyaratan kepatuhan dan penyelesaian perselisihan.',
        's3_step1_title' => 'Buka tab Log',
        's3_step1_body'  => 'klik Log di navigasi header untuk mengakses penampil log sistem.',
        's3_step2_title' => 'Beralih antar tipe log',
        's3_step2_body'  => 'gunakan bilah tab di bagian atas untuk memfilter menurut upaya login, impor email, atau peristiwa sistem.',
        's3_step3_title' => 'Tinjau detailnya',
        's3_step3_body'  => 'setiap baris menampilkan stempel waktu, lencana status (berhasil atau gagal), dan detail kontekstual seperti alamat IP, subjek email, atau deskripsi peristiwa.',
        's3_tip'         => 'Periksa log login secara teratur untuk upaya gagal berulang dari alamat IP yang tidak dikenal. Ini dapat mengindikasikan serangan brute-force atau kredensial yang disusupi yang memerlukan perhatian segera.',

        // Section 4: Understanding the data
        's4_heading' => 'Memahami data',
        's4_intro'   => 'Data mentah hanya menjadi berguna ketika Anda tahu apa yang harus dicari. Berikut adalah metrik utama yang perlu diperhatikan dan cara menafsirkannya untuk mendorong peningkatan nyata dalam operasi service desk Anda.',
        's4_metric1_title' => 'Waktu respons pertama',
        's4_metric1_body'  => 'Berapa lama pengguna menunggu sebelum seorang analis mengakui tiket mereka. Tren yang meningkat di sini berarti tim Anda mungkin kekurangan staf atau tiket tidak dirutekan secara efektif. Target: di bawah ambang SLA Anda.',
        's4_metric2_title' => 'Tingkat penyelesaian',
        's4_metric2_body'  => 'Persentase tiket yang diselesaikan dalam periode tertentu dibandingkan yang dibuat. Jika lebih banyak tiket masuk daripada yang keluar, tumpukan Anda bertambah dan Anda perlu menyelidiki penyebabnya.',
        's4_metric3_title' => 'Kontak berulang',
        's4_metric3_body'  => 'Tiket yang dibuka kembali atau pengguna yang mengajukan masalah yang sama berkali-kali. Tingkat kontak berulang yang tinggi menunjukkan akar masalah tidak ditangani, atau solusi tidak dikomunikasikan dengan jelas.',
        's4_metric4_title' => 'Titik panas kategori',
        's4_metric4_body'  => 'Kategori mana yang menghasilkan tiket paling banyak dari waktu ke waktu. Lonjakan pada kategori tertentu dapat menandakan sistem yang gagal, pembaruan perangkat lunak yang buruk, atau celah dalam pelatihan pengguna yang perlu ditangani.',
        's4_combine'     => 'Gunakan metrik ini secara bersama-sama, bukan secara terpisah. Misalnya, tingkat penyelesaian yang tinggi dikombinasikan dengan tingkat kontak berulang yang tinggi dapat mengindikasikan bahwa tiket ditutup terlalu cepat tanpa menyelesaikan masalah yang mendasarinya.',
        's4_tip'         => 'Jadwalkan tinjauan mingguan terhadap metrik utama Anda bersama tim. Pola yang tidak terlihat dari hari ke hari sering kali menjadi jelas saat dilihat dalam irama mingguan atau bulanan.',

        // Section 5: Settings & filters
        's5_heading' => 'Pengaturan & filter',
        's5_intro'   => 'Baik penampil log maupun dasbor tiket mendukung berbagai filter untuk membantu Anda mempersempit data yang Anda butuhkan. Penggunaan filter yang efektif mengubah tumpukan data menjadi informasi yang tertarget dan dapat ditindaklanjuti.',
        's5_step1_title' => 'Rentang tanggal',
        's5_step1_body'  => 'filter log dan laporan ke jendela waktu tertentu. Gunakan rentang preset (hari ini, minggu ini, bulan ini) atau tetapkan tanggal mulai dan akhir khusus untuk kontrol yang presisi.',
        's5_step2_title' => 'Filter status',
        's5_step2_body'  => 'di penampil log, filter menurut status berhasil atau gagal untuk dengan cepat mengisolasi masalah. Pada laporan tiket, filter menurut status terbuka, terselesaikan, atau tertutup.',
        's5_step3_title' => 'Pencarian',
        's5_step3_body'  => 'gunakan kotak pencarian untuk menemukan entri tertentu menurut kata kunci. Pada log, ini mencari di seluruh nama analis, alamat IP, subjek email, dan deskripsi peristiwa.',
        's5_step4_title' => 'Pengelompokan waktu',
        's5_step4_body'  => 'di dasbor tiket, kelompokkan data menurut hari, minggu, atau bulan untuk mengubah granularitas grafik Anda. Tampilan harian menunjukkan lonjakan jangka pendek; tampilan bulanan mengungkap tren jangka panjang.',
        's5_step5_title' => 'Filter departemen',
        's5_step5_body'  => 'persempit hasil dasbor ke departemen tertentu untuk membandingkan kinerja di berbagai bagian organisasi.',
        's5_tip'         => 'Gabungkan beberapa filter untuk analisis yang tertarget. Misalnya, filter menurut departemen tertentu dan rentang tanggal untuk melihat bagaimana perubahan proses terbaru memengaruhi volume tiket tim tersebut.',

        // Section 6: Quick tips
        's6_heading' => 'Tips singkat',
        's6_tip1_title' => 'Tinjau secara teratur',
        's6_tip1_body'  => 'Laporan paling berharga ketika ditinjau secara konsisten. Tetapkan irama — mingguan untuk metrik operasional, bulanan untuk analisis tren — dan patuhi itu.',
        's6_tip2_title' => 'Selidiki anomali',
        's6_tip2_body'  => 'Lonjakan atau penurunan mendadak pada metrik apa pun adalah sinyal yang layak diselidiki. Periksa log untuk konteks — apakah ada pemadaman sistem, peluncuran perangkat lunak, atau perubahan staf?',
        's6_tip3_title' => 'Bandingkan periode',
        's6_tip3_body'  => 'Gunakan filter tanggal untuk membandingkan minggu ini dengan minggu lalu, atau bulan ini dengan bulan yang sama tahun lalu. Perbandingan relatif mengungkap peningkatan atau penurunan lebih jelas daripada angka mentah.',
        's6_tip4_title' => 'Pantau keamanan',
        's6_tip4_body'  => 'Awasi upaya login yang gagal di log sistem. Kegagalan berulang dari alamat IP yang sama atau terhadap akun yang sama dapat mengindikasikan masalah keamanan yang perlu dieskalasi.',
    ],
];
