import { Link } from '@inertiajs/react';
import { ShoppingCart, Star, Eye } from 'lucide-react';

interface ProductProps {
    product: {
        id: number;
        name: string;
        slug: string;
        price: number;
        category: string;
        image: string;
        rating: number;
        reviews_count: number;
        is_new?: boolean;
    };
}

export default function ProductCard({ product }: ProductProps) {
    return (
        <div className="group bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden hover:shadow-xl hover:shadow-[#cb9833]/10 hover:border-[#cb9833]/30 transition-all duration-300 flex flex-col h-full">
            {/* Image Container */}
            <div className="relative aspect-square overflow-hidden bg-neutral-100 dark:bg-neutral-900">
                <img
                    src={product.image}
                    alt={product.name}
                    className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />

                {/* Badges */}
                <div className="absolute top-3 left-3 flex flex-col gap-2">
                    {product.is_new && (
                        <span className="px-2 py-1 bg-[#cb9833] text-white text-[10px] font-bold uppercase tracking-wider rounded-md shadow-sm">
                            Baru
                        </span>
                    )}
                    <span className="px-2 py-1 bg-white/90 dark:bg-neutral-900/90 backdrop-blur-sm text-neutral-600 dark:text-neutral-300 text-[10px] font-bold uppercase tracking-wider rounded-md shadow-sm border border-neutral-200 dark:border-neutral-700">
                        {product.category}
                    </span>
                </div>

                {/* Overlay Actions */}
                <div className="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                    <Link
                        href={`/product/${product.slug}`}
                        className="p-3 bg-white text-neutral-800 rounded-full hover:bg-[#cb9833] hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300 shadow-lg"
                        title="Lihat Detail"
                    >
                        <Eye className="w-5 h-5" />
                    </Link>
                    <button
                        className="p-3 bg-white text-neutral-800 rounded-full hover:bg-[#cb9833] hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75 shadow-lg"
                        title="Tambah ke Keranjang"
                    >
                        <ShoppingCart className="w-5 h-5" />
                    </button>
                </div>
            </div>

            {/* Content */}
            <div className="p-4 flex flex-col flex-grow">
                <div className="flex items-center gap-1 mb-2">
                    <Star className="w-3.5 h-3.5 text-yellow-400 fill-current" />
                    <span className="text-xs font-medium text-neutral-600 dark:text-neutral-400">
                        {product.rating} <span className="text-neutral-300">({product.reviews_count})</span>
                    </span>
                </div>

                <Link href={`/product/${product.slug}`} className="block mb-2">
                    <h3 className="font-bold text-neutral-800 dark:text-white group-hover:text-[#cb9833] transition-colors line-clamp-2 min-h-[3rem]">
                        {product.name}
                    </h3>
                </Link>

                <div className="mt-auto pt-3 border-t border-neutral-100 dark:border-neutral-700 flex items-center justify-between">
                    <div className="flex flex-col">
                        <span className="text-xs text-neutral-400">Harga</span>
                        <span className="text-lg font-bold text-[#cb9833]">
                            Rp {product.price.toLocaleString('id-ID')}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
