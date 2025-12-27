import { Search, Filter, X } from 'lucide-react';
import { useState } from 'react';

export default function ProductFilter() {
    const [priceRange, setPriceRange] = useState(5000000);

    return (
        <div className="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm p-5 sticky top-24">
            <div className="flex items-center justify-between mb-6">
                <h3 className="font-serif font-bold text-lg text-neutral-800 dark:text-white flex items-center gap-2">
                    <Filter className="w-5 h-5 text-[#cb9833]" />
                    Filter Produk
                </h3>
            </div>

            <div className="space-y-6">
                {/* Search */}
                <div>
                    <label className="text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2 block">Pencarian</label>
                    <div className="relative">
                        <input
                            type="text"
                            placeholder="Cari produk..."
                            className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 focus:border-[#cb9833] focus:ring-1 focus:ring-[#cb9833] text-sm transition-all"
                        />
                        <Search className="w-4 h-4 text-neutral-400 absolute left-3 top-3" />
                    </div>
                </div>

                {/* Categories */}
                <div>
                    <label className="text-xs font-bold text-neutral-500 uppercase tracking-wide mb-3 block">Kategori</label>
                    <div className="space-y-2">
                        {['Semua Kategori', 'Jasa Konsultasi', 'Peralatan', 'Merchandise', 'Sewa Aset'].map((cat, idx) => (
                            <label key={idx} className="flex items-center gap-3 cursor-pointer group">
                                <div className="relative flex items-center">
                                    <input type="checkbox" className="peer h-4 w-4 rounded border-neutral-300 text-[#cb9833] focus:ring-[#cb9833]" defaultChecked={idx === 0} />
                                </div>
                                <span className="text-sm text-neutral-600 dark:text-neutral-300 group-hover:text-[#cb9833] transition-colors">
                                    {cat}
                                </span>
                            </label>
                        ))}
                    </div>
                </div>

                {/* Price Range */}
                <div>
                    <label className="text-xs font-bold text-neutral-500 uppercase tracking-wide mb-3 block">Rentang Harga</label>
                    <input
                        type="range"
                        min="0"
                        max="10000000"
                        value={priceRange}
                        onChange={(e) => setPriceRange(Number(e.target.value))}
                        className="w-full h-2 bg-neutral-200 dark:bg-neutral-700 rounded-lg appearance-none cursor-pointer accent-[#cb9833]"
                    />
                    <div className="flex justify-between mt-2 text-xs font-medium text-neutral-500">
                        <span>Rp 0</span>
                        <span className="text-[#cb9833]">Rp {priceRange.toLocaleString('id-ID')}</span>
                    </div>
                </div>

                {/* Actions */}
                <div className="pt-4 border-t border-neutral-100 dark:border-neutral-700 flex gap-2">
                    <button className="flex-1 py-2 bg-[#cb9833] hover:bg-[#b0842b] text-white text-sm font-semibold rounded-lg transition-colors shadow-md shadow-[#cb9833]/20">
                        Terapkan
                    </button>
                    <button className="px-3 py-2 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 text-neutral-600 dark:text-neutral-300 rounded-lg transition-colors">
                        <X className="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    );
}
