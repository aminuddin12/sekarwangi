<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user untuk author_id (Admin Utama)
        $adminUser = User::first() ?? User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@sekarwangi.com',
        ]);

        // ==========================================
        // 1. SEED PAGE TYPES
        // ==========================================
        $pageTypes = [
            ['name' => 'Landing Page', 'slug' => 'landing-page', 'description' => 'Halaman utama untuk pemasaran atau beranda situs.'],
            ['name' => 'Standard Page', 'slug' => 'standard-page', 'description' => 'Halaman informasi statis umum (Tentang Kami, Sejarah).'],
            ['name' => 'Service Page', 'slug' => 'service-page', 'description' => 'Halaman yang menjelaskan layanan atau produk jasa.'],
            ['name' => 'Blog Index', 'slug' => 'blog-index', 'description' => 'Halaman daftar artikel atau berita.'],
            ['name' => 'Career Page', 'slug' => 'career-page', 'description' => 'Halaman lowongan pekerjaan dan budaya kerja.'],
            ['name' => 'Contact Page', 'slug' => 'contact-page', 'description' => 'Halaman kontak dengan formulir dan peta.'],
            ['name' => 'Legal Document', 'slug' => 'legal-document', 'description' => 'Dokumen hukum seperti Kebijakan Privasi.'],
            ['name' => 'FAQ Page', 'slug' => 'faq-page', 'description' => 'Halaman Tanya Jawab.'],
            ['name' => 'Partnership', 'slug' => 'partnership', 'description' => 'Halaman informasi kemitraan dan afiliasi.'],
        ];

        foreach ($pageTypes as $type) {
            DB::table('page_types')->updateOrInsert(
                ['slug' => $type['slug']],
                array_merge($type, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $types = DB::table('page_types')->pluck('id', 'slug');

        // ==========================================
        // 2. SEED PAGE TEMPLATES
        // ==========================================
        $pageTemplates = [
            [
                'name' => 'Full Width Hero',
                'view_path' => 'public/templates/FullWidthHero',
                'default_config' => json_encode(['header_style' => 'transparent', 'show_footer' => true]),
                'thumbnail' => '/thumbnails/tpl-hero.jpg',
            ],
            [
                'name' => 'Sidebar Right Content',
                'view_path' => 'public/templates/SidebarRight',
                'default_config' => json_encode(['sidebar_position' => 'right', 'widgets' => ['search', 'categories']]),
                'thumbnail' => '/thumbnails/tpl-sidebar.jpg',
            ],
            [
                'name' => 'Minimalist Legal Doc',
                'view_path' => 'public/templates/MinimalistDoc',
                'default_config' => json_encode(['typography' => 'serif', 'max_width' => '800px']),
                'thumbnail' => '/thumbnails/tpl-legal.jpg',
            ],
            [
                'name' => 'Organization Profile',
                'view_path' => 'public/organization/MainPage',
                'default_config' => json_encode(['show_breadcrumbs' => true, 'sticky_sidebar' => true]),
                'thumbnail' => '/thumbnails/tpl-org.jpg',
            ],
            [
                'name' => 'Grid Listing',
                'view_path' => 'public/templates/GridListing',
                'default_config' => json_encode(['columns' => 3, 'gap' => 'large']),
                'thumbnail' => '/thumbnails/tpl-grid.jpg',
            ],
            [
                'name' => 'Contact Form & Map',
                'view_path' => 'public/templates/ContactLayout',
                'default_config' => json_encode(['map_position' => 'top', 'form_style' => 'modern']),
                'thumbnail' => '/thumbnails/tpl-contact.jpg',
            ],
        ];

        foreach ($pageTemplates as $tpl) {
            DB::table('page_templates')->updateOrInsert(
                ['name' => $tpl['name']],
                array_merge($tpl, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }

        $tplFullWidth = DB::table('page_templates')->where('name', 'Full Width Hero')->value('id');
        $tplOrgProfile = DB::table('page_templates')->where('name', 'Organization Profile')->value('id');
        $tplMinimalist = DB::table('page_templates')->where('name', 'Minimalist Legal Doc')->value('id');
        $tplGrid = DB::table('page_templates')->where('name', 'Grid Listing')->value('id');
        $tplContact = DB::table('page_templates')->where('name', 'Contact Form & Map')->value('id');

        // ==========================================
        // 3. SEED PAGES (HALAMAN UTAMA LENGKAP)
        // ==========================================
        $pages = [
            // --- GRUP UTAMA (BERANDA) ---
            [
                'title' => 'Beranda',
                'slug' => 'home',
                'subtitle' => 'Solusi Bisnis Terintegrasi',
                'page_type_id' => $types['landing-page'],
                'page_template_id' => $tplFullWidth,
                'content' => 'Selamat datang di Sekar Wangi Enterprise. Kami menyediakan solusi bisnis komprehensif mulai dari konsultasi hingga penyediaan aset.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Beranda - Sekar Wangi Enterprise',
                'meta_description' => 'Penyedia solusi bisnis, produk, dan layanan terintegrasi terbaik di Indonesia. Mitra terpercaya untuk pertumbuhan bisnis Anda.',
                'meta_keywords' => 'sekar wangi, enterprise, solusi bisnis, konsultan, surabaya',
                'featured_image' => '/storage/pages/home-featured.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP TENTANG KAMI ---
            [
                'title' => 'Tentang Kami',
                'slug' => 'about',
                'subtitle' => 'Sejarah dan Profil Perusahaan',
                'page_type_id' => $types['standard-page'],
                'page_template_id' => $tplOrgProfile,
                'content' => '<p>Sekar Wangi Enterprise berdiri sejak 2010 dengan semangat untuk menjadi katalisator pertumbuhan ekonomi...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Tentang Kami - Sekar Wangi Enterprise',
                'meta_description' => 'Pelajari sejarah, nilai-nilai, dan perjalanan Sekar Wangi Enterprise dalam melayani negeri.',
                'meta_keywords' => 'tentang kami, profil perusahaan, sejarah sekar wangi',
                'featured_image' => '/storage/pages/about-office.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'vision-mission',
                'subtitle' => 'Nilai Inti dan Tujuan',
                'page_type_id' => $types['standard-page'],
                'page_template_id' => $tplOrgProfile,
                'content' => '<p>Visi kami adalah menjadi pemimpin pasar global yang terpercaya...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Visi Misi - Sekar Wangi Enterprise',
                'meta_description' => 'Visi, misi, dan budaya kerja yang menjadi landasan operasional kami.',
                'meta_keywords' => 'visi misi, corporate values, budaya kerja',
                'featured_image' => '/storage/pages/vision-banner.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'organization',
                'subtitle' => 'Tim Manajemen',
                'page_type_id' => $types['standard-page'],
                'page_template_id' => $tplOrgProfile,
                'content' => '<p>Kami dipimpin oleh para profesional berpengalaman di bidangnya...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Struktur Organisasi - Sekar Wangi Enterprise',
                'meta_description' => 'Susunan Dewan Direksi dan Manajemen Eksekutif Sekar Wangi Enterprise.',
                'meta_keywords' => 'struktur organisasi, direksi, manajemen',
                'featured_image' => '/storage/pages/org-chart.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Legalitas & Sertifikasi',
                'slug' => 'legality',
                'subtitle' => 'Kepatuhan Hukum',
                'page_type_id' => $types['standard-page'],
                'page_template_id' => $tplOrgProfile,
                'content' => '<p>Keamanan dan kepercayaan mitra adalah prioritas kami. Berikut adalah dokumen legalitas resmi kami...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Legalitas - Sekar Wangi Enterprise',
                'meta_description' => 'Informasi NIB, NPWP, dan sertifikasi ISO perusahaan.',
                'meta_keywords' => 'legalitas perusahaan, iso, sertifikasi, nib',
                'featured_image' => '/storage/pages/legal-docs.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP LAYANAN ---
            [
                'title' => 'Layanan Kami',
                'slug' => 'services',
                'subtitle' => 'Solusi Profesional untuk Anda',
                'page_type_id' => $types['service-page'],
                'page_template_id' => $tplGrid,
                'content' => 'Temukan berbagai layanan profesional mulai dari konsultasi bisnis, penyewaan alat berat, hingga manajemen acara.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Layanan - Sekar Wangi Enterprise',
                'meta_description' => 'Katalog lengkap layanan jasa dan produk Sekar Wangi Enterprise.',
                'meta_keywords' => 'layanan jasa, produk, konsultasi, sewa, event organizer',
                'featured_image' => '/storage/pages/services-hero.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP KEMITRAAN ---
            [
                'title' => 'Peluang Kemitraan',
                'slug' => 'partnership-opportunities',
                'subtitle' => 'Tumbuh Bersama Kami',
                'page_type_id' => $types['partnership'],
                'page_template_id' => $tplFullWidth,
                'content' => 'Kami membuka peluang kerjasama strategis bagi vendor, supplier, dan investor.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Kemitraan - Sekar Wangi Enterprise',
                'meta_description' => 'Informasi peluang kerjasama bisnis dan afiliasi.',
                'meta_keywords' => 'kemitraan, franchise, kerjasama bisnis, investor',
                'featured_image' => '/storage/pages/partnership-handshake.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP MEDIA & KOMUNITAS ---
            [
                'title' => 'Berita & Artikel',
                'slug' => 'news',
                'subtitle' => 'Wawasan Terkini',
                'page_type_id' => $types['blog-index'],
                'page_template_id' => $tplGrid,
                'content' => 'Ikuti perkembangan terbaru dari perusahaan dan industri terkait.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Berita - Sekar Wangi Enterprise',
                'meta_description' => 'Blog resmi berisi berita perusahaan, press release, dan artikel edukasi.',
                'meta_keywords' => 'berita, blog, artikel, press release',
                'featured_image' => '/storage/pages/news-room.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Karir',
                'slug' => 'careers',
                'subtitle' => 'Bergabung dengan Tim Juara',
                'page_type_id' => $types['career-page'],
                'page_template_id' => $tplGrid,
                'content' => 'Bangun karir impian Anda bersama lingkungan kerja yang dinamis dan suportif.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Karir - Sekar Wangi Enterprise',
                'meta_description' => 'Informasi lowongan kerja terbaru dan budaya perusahaan.',
                'meta_keywords' => 'lowongan kerja, karir, rekrutmen, job vacancy',
                'featured_image' => '/storage/pages/career-team.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP DUKUNGAN ---
            [
                'title' => 'Hubungi Kami',
                'slug' => 'contact',
                'subtitle' => 'Kami Siap Membantu',
                'page_type_id' => $types['contact-page'],
                'page_template_id' => $tplContact,
                'content' => 'Jangan ragu untuk menghubungi tim kami untuk pertanyaan atau penawaran.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Hubungi Kami - Sekar Wangi Enterprise',
                'meta_description' => 'Kontak resmi, alamat kantor, nomor telepon, dan email support.',
                'meta_keywords' => 'kontak, alamat, telepon, email, customer service',
                'featured_image' => '/storage/pages/contact-center.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Pusat Bantuan (FAQ)',
                'slug' => 'faq',
                'subtitle' => 'Pertanyaan Umum',
                'page_type_id' => $types['faq-page'],
                'page_template_id' => $tplFullWidth,
                'content' => 'Temukan jawaban cepat untuk pertanyaan yang sering diajukan.',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'FAQ - Sekar Wangi Enterprise',
                'meta_description' => 'Database pertanyaan umum seputar layanan dan produk kami.',
                'meta_keywords' => 'faq, bantuan, pertanyaan umum, help center',
                'featured_image' => '/storage/pages/faq-illustration.jpg',
                'is_indexable' => true,
                'author_id' => $adminUser->id,
            ],

            // --- GRUP LEGAL ---
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'privacy-policy',
                'subtitle' => 'Perlindungan Data Anda',
                'page_type_id' => $types['legal-document'],
                'page_template_id' => $tplMinimalist,
                'content' => '<p>Kami berkomitmen untuk melindungi data pribadi Anda sesuai dengan UU PDP...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Kebijakan Privasi',
                'meta_description' => 'Ketentuan pengelolaan data pribadi pengguna.',
                'meta_keywords' => 'privasi, data pribadi, privacy policy',
                'is_indexable' => false,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'terms-conditions',
                'subtitle' => 'Ketentuan Layanan',
                'page_type_id' => $types['legal-document'],
                'page_template_id' => $tplMinimalist,
                'content' => '<p>Syarat dan ketentuan ini mengatur penggunaan layanan Sekar Wangi Enterprise...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Syarat & Ketentuan',
                'meta_description' => 'Perjanjian pengguna dan aturan layanan.',
                'meta_keywords' => 'syarat ketentuan, terms of service, aturan pakai',
                'is_indexable' => false,
                'author_id' => $adminUser->id,
            ],
            [
                'title' => 'Kebijakan Pengembalian',
                'slug' => 'refund-policy',
                'subtitle' => 'Garansi & Refund',
                'page_type_id' => $types['legal-document'],
                'page_template_id' => $tplMinimalist,
                'content' => '<p>Kami memberikan garansi kepuasan dengan ketentuan pengembalian sebagai berikut...</p>',
                'content_structure' => json_encode(['version' => '1.0', 'blocks' => []]),
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Kebijakan Refund',
                'meta_description' => 'Informasi prosedur klaim garansi dan pengembalian dana.',
                'meta_keywords' => 'refund, garansi, pengembalian barang',
                'is_indexable' => false,
                'author_id' => $adminUser->id,
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                array_merge($page, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // ==========================================
        // 4. SEED PAGE SECTIONS (DETAIL KONTEN)
        // ==========================================

        // --- SECTIONS HALAMAN HOME ---
        $homeId = DB::table('pages')->where('slug', 'home')->value('id');
        if ($homeId) {
            $sections = [
                [
                    'section_name' => 'Main Hero',
                    'component_type' => 'hero-slider',
                    'order' => 1,
                    'data' => json_encode([
                        'headline' => 'Membangun Masa Depan Bisnis Anda',
                        'subheadline' => 'Ekosistem layanan terintegrasi dari produk berkualitas hingga solusi digital.',
                        'primary_cta' => ['text' => 'Jelajahi Produk', 'url' => '/product'],
                        'secondary_cta' => ['text' => 'Hubungi Kami', 'url' => '/contact'],
                        'background_video' => 'hero-bg.mp4'
                    ]),
                    'style_config' => json_encode(['theme' => 'dark', 'height' => 'screen']),
                    'is_visible' => true,
                ],
                [
                    'section_name' => 'Key Features',
                    'component_type' => 'icon-grid',
                    'order' => 2,
                    'data' => json_encode([
                        'title' => 'Keunggulan Kami',
                        'features' => [
                            ['icon' => 'shield-check', 'title' => 'Terpercaya', 'desc' => 'Lebih dari 10 tahun melayani mitra bisnis.'],
                            ['icon' => 'star', 'title' => 'Kualitas Premium', 'desc' => 'Standar ISO 9001:2015 dalam setiap layanan.'],
                            ['icon' => 'clock', 'title' => 'Dukungan 24/7', 'desc' => 'Tim support siap membantu kapan saja.'],
                            ['icon' => 'globe', 'title' => 'Jangkauan Luas', 'desc' => 'Melayani pengiriman ke seluruh Indonesia.'],
                        ]
                    ]),
                    'is_visible' => true,
                ],
                [
                    'section_name' => 'Business Stats',
                    'component_type' => 'stats-counter',
                    'order' => 3,
                    'data' => json_encode([
                        'stats' => [
                            ['value' => '5000+', 'label' => 'Klien Puas'],
                            ['value' => '150+', 'label' => 'Mitra Korporat'],
                            ['value' => '12', 'label' => 'Cabang Kota'],
                            ['value' => '99%', 'label' => 'Tingkat Kepuasan'],
                        ]
                    ]),
                    'style_config' => json_encode(['bg_color' => '#cb9833', 'text_color' => '#ffffff']),
                    'is_visible' => true,
                ],
                [
                    'section_name' => 'Testimonials',
                    'component_type' => 'testimonial-slider',
                    'order' => 4,
                    'data' => json_encode([
                        'title' => 'Apa Kata Mereka',
                        'items' => [
                            ['name' => 'Budi Santoso', 'role' => 'CEO PT Maju Jaya', 'quote' => 'Pelayanan Sekar Wangi sangat profesional.'],
                            ['name' => 'Siti Aminah', 'role' => 'Owner Cafe Kopi', 'quote' => 'Produk berkualitas dengan harga bersaing.'],
                        ]
                    ]),
                    'is_visible' => true,
                ]
            ];
            $this->seedSections($homeId, $sections);
        }

        // --- SECTIONS HALAMAN ABOUT ---
        $aboutId = DB::table('pages')->where('slug', 'about')->value('id');
        if ($aboutId) {
            $sections = [
                [
                    'section_name' => 'Company Overview',
                    'component_type' => 'text-media-split',
                    'order' => 1,
                    'data' => json_encode([
                        'title' => 'Sekilas Perusahaan',
                        'content' => 'Sekar Wangi Enterprise dimulai sebagai usaha kecil pada tahun 2010...',
                        'image_url' => 'office-building.jpg',
                        'image_position' => 'right'
                    ]),
                    'is_visible' => true,
                ],
                [
                    'section_name' => 'Our Journey Timeline',
                    'component_type' => 'timeline-vertical',
                    'order' => 2,
                    'data' => json_encode([
                        'events' => [
                            ['year' => '2010', 'title' => 'Pendirian', 'desc' => 'Didirikan di Surabaya.'],
                            ['year' => '2015', 'title' => 'Ekspansi Regional', 'desc' => 'Membuka cabang di Jakarta dan Bali.'],
                            ['year' => '2020', 'title' => 'Transformasi Digital', 'desc' => 'Peluncuran platform layanan terintegrasi.'],
                        ]
                    ]),
                    'is_visible' => true,
                ]
            ];
            $this->seedSections($aboutId, $sections);
        }

        // --- SECTIONS HALAMAN CONTACT ---
        $contactId = DB::table('pages')->where('slug', 'contact')->value('id');
        if ($contactId) {
            $sections = [
                [
                    'section_name' => 'Contact Info Cards',
                    'component_type' => 'info-cards',
                    'order' => 1,
                    'data' => json_encode([
                        'cards' => [
                            ['icon' => 'map-pin', 'title' => 'Alamat', 'content' => 'Jl. Pemuda No. 123, Surabaya'],
                            ['icon' => 'phone', 'title' => 'Telepon', 'content' => '+62 31 1234567'],
                            ['icon' => 'mail', 'title' => 'Email', 'content' => 'info@sekarwangi.com'],
                        ]
                    ]),
                    'is_visible' => true,
                ],
                [
                    'section_name' => 'Google Map',
                    'component_type' => 'embed-map',
                    'order' => 2,
                    'data' => json_encode([
                        'lat' => -7.2575,
                        'lng' => 112.7521,
                        'zoom' => 15
                    ]),
                    'style_config' => json_encode(['height' => '400px', 'width' => '100%']),
                    'is_visible' => true,
                ]
            ];
            $this->seedSections($contactId, $sections);
        }
    }

    /**
     * Helper untuk memasukkan sections
     */
    private function seedSections($pageId, $sections)
    {
        foreach ($sections as $section) {
            DB::table('page_sections')->updateOrInsert(
                ['page_id' => $pageId, 'section_name' => $section['section_name']],
                array_merge($section, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
