import PublicLayout from '@/layouts/public-layout';
import Hero from '@/components/public/organization/Hero';
import ProductFilter from '@/components/public/product/ProductFilter';
import ProductCard from '@/components/public/product/ProductCard';
import { LayoutGrid, List } from 'lucide-react';
import { useState } from 'react';

interface ProductIndexProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
    products: any[];
}

export default function ProductIndex({ topbarMenu, navbarMenu, footerMenu, products }: ProductIndexProps) {
    const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');

    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Katalog Produk & Layanan"
        >
            <Hero
                title="Produk & Layanan Kami"
                subtitle="Jelajahi berbagai solusi bisnis, produk berkualitas, dan layanan profesional terbaik dari Sekar Wangi Enterprise."
            />

            <div className="container mx-auto px-4 py-12">
                <div className="flex flex-col lg:flex-row gap-8">
                    {/* Sidebar Filter */}
                    <div className="w-full lg:w-72 flex-shrink-0">
                        <ProductFilter />
                    </div>

                    {/* Product Grid */}
                    <div className="flex-1">
                        {/* Toolbar */}
                        <div className="bg-white dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4">
                            <span className="text-sm text-neutral-500 dark:text-neutral-400">
                                Menampilkan <span className="font-bold text-neutral-800 dark:text-white">{products.length}</span> produk
                            </span>

                            <div className="flex items-center gap-4">
                                <select className="bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 text-sm rounded-lg p-2 focus:ring-[#cb9833] focus:border-[#cb9833]">
                                    <option>Terbaru</option>
                                    <option>Terpopuler</option>
                                    <option>Harga Terendah</option>
                                    <option>Harga Tertinggi</option>
                                </select>

                                <div className="flex bg-neutral-100 dark:bg-neutral-700 rounded-lg p-1">
                                    <button
                                        onClick={() => setViewMode('grid')}
                                        className={`p-1.5 rounded-md transition-all ${viewMode === 'grid' ? 'bg-white dark:bg-neutral-600 shadow text-[#cb9833]' : 'text-neutral-400'}`}
                                    >
                                        <LayoutGrid className="w-4 h-4" />
                                    </button>
                                    <button
                                        onClick={() => setViewMode('list')}
                                        className={`p-1.5 rounded-md transition-all ${viewMode === 'list' ? 'bg-white dark:bg-neutral-600 shadow text-[#cb9833]' : 'text-neutral-400'}`}
                                    >
                                        <List className="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Grid */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {products.map((product) => (
                                <ProductCard key={product.id} product={product} />
                            ))}
                        </div>

                        {/* Pagination Placeholder */}
                        <div className="mt-12 flex justify-center gap-2">
                            <button className="px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-[#cb9833] hover:text-[#cb9833] transition-colors">1</button>
                            <button className="px-4 py-2 rounded-lg bg-[#cb9833] text-white shadow-md">2</button>
                            <button className="px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-[#cb9833] hover:text-[#cb9833] transition-colors">3</button>
                            <span className="px-4 py-2 text-neutral-400">...</span>
                            <button className="px-4 py-2 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-[#cb9833] hover:text-[#cb9833] transition-colors">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
