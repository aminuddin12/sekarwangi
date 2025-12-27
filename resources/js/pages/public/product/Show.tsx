import PublicLayout from '@/layouts/public-layout';
import Breadcrumbs from '@/components/public/organization/Breadcrumbs';
import ProductCard from '@/components/public/product/ProductCard';
import { Star, ShoppingCart, Heart, Share2, ShieldCheck, Truck, Clock } from 'lucide-react';
import { useState } from 'react';

interface ProductShowProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
    product: any;
    relatedProducts: any[];
}

export default function ProductShow({ topbarMenu, navbarMenu, footerMenu, product, relatedProducts }: ProductShowProps) {
    const [activeImage, setActiveImage] = useState(0);
    const [qty, setQty] = useState(1);

    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title={product.name}
        >
            <div className="container mx-auto px-4 py-8">
                <Breadcrumbs items={[
                    { label: 'Produk', url: '/product' },
                    { label: product.category, url: '/product' },
                    { label: product.name }
                ]} />

                {/* Main Content */}
                <div className="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 md:p-8 mt-6">
                    <div className="flex flex-col lg:flex-row gap-10">
                        {/* Gallery Section */}
                        <div className="w-full lg:w-5/12 space-y-4">
                            <div className="aspect-square bg-neutral-100 dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200 dark:border-neutral-700 relative">
                                <img
                                    src={product.images[activeImage]}
                                    alt={product.name}
                                    className="w-full h-full object-cover"
                                />
                                <div className="absolute top-4 left-4">
                                     <span className="px-3 py-1 bg-[#cb9833] text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md">
                                        {product.category}
                                    </span>
                                </div>
                            </div>
                            <div className="grid grid-cols-4 gap-4">
                                {product.images.map((img: string, idx: number) => (
                                    <button
                                        key={idx}
                                        onClick={() => setActiveImage(idx)}
                                        className={`aspect-square rounded-lg overflow-hidden border-2 transition-all ${activeImage === idx ? 'border-[#cb9833] ring-2 ring-[#cb9833]/30' : 'border-transparent opacity-70 hover:opacity-100'}`}
                                    >
                                        <img src={img} alt={`Thumbnail ${idx}`} className="w-full h-full object-cover" />
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Product Info Section */}
                        <div className="flex-1">
                            <h1 className="text-3xl font-bold text-neutral-800 dark:text-white font-serif mb-2">
                                {product.name}
                            </h1>

                            <div className="flex items-center gap-4 mb-6">
                                <div className="flex items-center gap-1">
                                    <Star className="w-4 h-4 text-yellow-400 fill-current" />
                                    <span className="font-bold text-neutral-800 dark:text-white">{product.rating}</span>
                                    <span className="text-neutral-400 text-sm">({product.reviews_count} Ulasan)</span>
                                </div>
                                <div className="w-px h-4 bg-neutral-300 dark:bg-neutral-600"></div>
                                <span className="text-sm text-green-600 dark:text-green-400 font-medium">Stok Tersedia ({product.stock})</span>
                            </div>

                            <div className="text-3xl font-bold text-[#cb9833] mb-8">
                                Rp {product.price.toLocaleString('id-ID')}
                            </div>

                            <p className="text-neutral-600 dark:text-neutral-300 leading-relaxed mb-8">
                                {product.description}
                            </p>

                            {/* Features */}
                            <div className="mb-8">
                                <h3 className="font-bold text-neutral-800 dark:text-white mb-3 text-sm uppercase tracking-wide">Fitur Unggulan</h3>
                                <ul className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    {product.features.map((feature: string, idx: number) => (
                                        <li key={idx} className="flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                                            <ShieldCheck className="w-4 h-4 text-[#cb9833]" />
                                            {feature}
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            {/* Actions */}
                            <div className="flex flex-col sm:flex-row gap-4 border-t border-neutral-100 dark:border-neutral-700 pt-8">
                                <div className="flex items-center border border-neutral-200 dark:border-neutral-600 rounded-lg">
                                    <button
                                        onClick={() => qty > 1 && setQty(qty - 1)}
                                        className="px-4 py-3 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-600 dark:text-neutral-300 transition-colors"
                                    >
                                        -
                                    </button>
                                    <input
                                        type="number"
                                        value={qty}
                                        readOnly
                                        className="w-16 text-center border-none focus:ring-0 bg-transparent text-neutral-800 dark:text-white font-bold"
                                    />
                                    <button
                                        onClick={() => setQty(qty + 1)}
                                        className="px-4 py-3 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-600 dark:text-neutral-300 transition-colors"
                                    >
                                        +
                                    </button>
                                </div>

                                <button className="flex-1 py-3 bg-[#cb9833] hover:bg-[#b0842b] text-white font-bold rounded-lg shadow-lg shadow-[#cb9833]/20 flex items-center justify-center gap-2 transition-all transform hover:-translate-y-0.5">
                                    <ShoppingCart className="w-5 h-5" />
                                    Tambah ke Keranjang
                                </button>

                                <button className="p-3 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:border-[#cb9833] hover:text-[#cb9833] transition-colors text-neutral-400">
                                    <Heart className="w-5 h-5" />
                                </button>
                                <button className="p-3 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:border-[#cb9833] hover:text-[#cb9833] transition-colors text-neutral-400">
                                    <Share2 className="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Additional Info Tabs */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-12">
                     <div className="lg:col-span-2 space-y-8">
                        {/* Description Tab Content */}
                         <div className="bg-white dark:bg-neutral-800 rounded-xl p-8 border border-neutral-200 dark:border-neutral-700">
                            <h3 className="text-xl font-bold text-neutral-800 dark:text-white mb-4">Detail Spesifikasi</h3>
                            <div className="prose prose-neutral dark:prose-invert max-w-none">
                                <p>
                                    Produk ini dibuat dengan standar kualitas tertinggi untuk memastikan kepuasan pelanggan.
                                    Setiap detail telah diperhatikan dengan seksama, mulai dari pemilihan material hingga proses finishing.
                                </p>
                                <ul>
                                    <li>Kualitas Premium Standar Internasional</li>
                                    <li>Layanan purna jual yang responsif</li>
                                    <li>Tersedia dalam berbagai varian sesuai kebutuhan</li>
                                    <li>Pengiriman aman dan berasuransi</li>
                                </ul>
                            </div>
                        </div>
                     </div>

                     {/* Sidebar Info */}
                     <div className="space-y-6">
                        <div className="bg-neutral-50 dark:bg-neutral-900 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700">
                            <h4 className="font-bold text-neutral-800 dark:text-white mb-4">Pengiriman & Garansi</h4>
                            <div className="space-y-4">
                                <div className="flex gap-3">
                                    <div className="p-2 bg-[#cb9833]/10 rounded-lg text-[#cb9833]">
                                        <Truck className="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h5 className="font-bold text-sm text-neutral-800 dark:text-white">Pengiriman Cepat</h5>
                                        <p className="text-xs text-neutral-500">Estimasi 2-3 hari kerja untuk Jabodetabek</p>
                                    </div>
                                </div>
                                <div className="flex gap-3">
                                    <div className="p-2 bg-[#cb9833]/10 rounded-lg text-[#cb9833]">
                                        <ShieldCheck className="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h5 className="font-bold text-sm text-neutral-800 dark:text-white">Garansi Resmi</h5>
                                        <p className="text-xs text-neutral-500">Perlindungan produk hingga 12 bulan</p>
                                    </div>
                                </div>
                                <div className="flex gap-3">
                                    <div className="p-2 bg-[#cb9833]/10 rounded-lg text-[#cb9833]">
                                        <Clock className="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h5 className="font-bold text-sm text-neutral-800 dark:text-white">Support 24/7</h5>
                                        <p className="text-xs text-neutral-500">Bantuan pelanggan siap sedia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                </div>

                {/* Related Products */}
                <div className="mt-16">
                    <h2 className="text-2xl font-bold text-neutral-800 dark:text-white mb-8 font-serif">Produk Terkait</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        {relatedProducts.map((prod: any) => (
                            <ProductCard key={prod.id} product={prod} />
                        ))}
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
