<?php

namespace App\Http\Controllers\Public;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class ProductController extends PublicController
{
    public function index(Request $request): Response
    {
        // Simulasi data 12 produk
        $products = collect(range(1, 12))->map(function ($i) {
            return [
                'id' => $i,
                'name' => 'Produk Unggulan Seri ' . $i,
                'slug' => 'produk-unggulan-seri-' . $i,
                'price' => rand(150000, 5000000),
                'category' => $i % 3 == 0 ? 'Jasa Konsultasi' : ($i % 2 == 0 ? 'Peralatan' : 'Merchandise'),
                'image' => 'https://placehold.co/400x400/f5f5f5/cb9833?text=Produk+' . $i,
                'rating' => 4.5,
                'reviews_count' => rand(10, 100),
                'is_new' => $i < 4,
                'description' => 'Deskripsi singkat untuk produk unggulan nomor ' . $i . ', kualitas terjamin dan bergaransi.',
            ];
        });

        return Inertia::render('public/product/Index', array_merge($this->getSharedData(), [
            'products' => $products,
            'filters' => $request->only(['search', 'category', 'min_price', 'max_price', 'sort']),
        ]));
    }

    public function show($slug): Response
    {
        // Simulasi data detail produk
        $product = [
            'id' => 1,
            'name' => 'Paket Konsultasi Bisnis Premium',
            'slug' => $slug,
            'price' => 2500000,
            'category' => 'Jasa Konsultasi',
            'description' => 'Paket konsultasi bisnis komprehensif yang dirancang untuk membantu perusahaan Anda mencapai pertumbuhan optimal. Mencakup analisis pasar, strategi pemasaran, dan efisiensi operasional dengan standar ISO 9001:2015.',
            'features' => [
                'Sesi Konsultasi 1-on-1 (5 Jam)',
                'Laporan Analisis Mendalam',
                'Akses Template Dokumen Bisnis',
                'Garansi Kepuasan 30 Hari',
                'Prioritas Support 24/7'
            ],
            'images' => [
                'https://placehold.co/600x600/f5f5f5/cb9833?text=Produk+Utama',
                'https://placehold.co/600x600/f5f5f5/cb9833?text=Tampak+Samping',
                'https://placehold.co/600x600/f5f5f5/cb9833?text=Detail+Fitur',
                'https://placehold.co/600x600/f5f5f5/cb9833?text=Penggunaan',
            ],
            'rating' => 4.9,
            'reviews_count' => 128,
            'stock' => 15,
            'sku' => 'SW-SRV-2025-001',
            'tags' => ['Bisnis', 'Konsultasi', 'Premium']
        ];

        // Simulasi produk terkait
        $relatedProducts = collect(range(1, 4))->map(function ($i) {
             return [
                'id' => $i,
                'name' => 'Produk Terkait ' . $i,
                'slug' => 'produk-terkait-' . $i,
                'price' => rand(100000, 1000000),
                'category' => 'Jasa',
                'image' => 'https://placehold.co/400x400/f5f5f5/cb9833?text=Related+' . $i,
                'rating' => 4.2,
                'reviews_count' => rand(5, 50),
            ];
        });

        return Inertia::render('public/product/Show', array_merge($this->getSharedData(), [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]));
    }
}
