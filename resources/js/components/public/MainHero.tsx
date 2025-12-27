import { motion } from 'framer-motion';
import { ArrowRight, ChevronRight, Star } from 'lucide-react';
import { Link } from '@inertiajs/react';

export default function MainHero() {
    return (
        <div className="relative overflow-hidden bg-white dark:bg-neutral-950 pt-16 pb-20 lg:pt-24 lg:pb-32 font-sans">
            {/* Background Decorations - Golden Glows */}
            <div className="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div className="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-[#cb9833]/10 blur-[100px]" />
                <div className="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-[#fcd34d]/10 blur-[80px]" />
                {/* Pattern Overlay */}
                <div className="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style={{ backgroundImage: 'radial-gradient(#cb9833 1px, transparent 1px)', backgroundSize: '32px 32px' }}></div>
            </div>

            <div className="container mx-auto px-4 relative z-10">
                <div className="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

                    {/* Text Content */}
                    <motion.div
                        initial={{ opacity: 0, x: -30 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.8, ease: "easeOut" }}
                        className="flex-1 text-center lg:text-left"
                    >
                        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#cb9833]/10 text-[#cb9833] text-xs font-bold tracking-wider uppercase mb-8 border border-[#cb9833]/20">
                            <Star className="w-3 h-3 fill-current" />
                            Solusi Terintegrasi Terbaik
                        </div>

                        <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold text-neutral-900 dark:text-white leading-[1.1] mb-6 font-serif tracking-tight">
                            Membangun Masa Depan <br className="hidden lg:block" />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#cb9833] via-[#eab308] to-[#b45309]">
                                Bisnis Anda
                            </span>
                        </h1>

                        <p className="text-lg md:text-xl text-neutral-600 dark:text-neutral-300 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                            Sekar Wangi Enterprise menyediakan ekosistem layanan lengkap mulai dari produk berkualitas, jasa profesional, hingga solusi digital untuk mendukung pertumbuhan Anda.
                        </p>

                        <div className="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                            <Link
                                href="/product"
                                className="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-[#cb9833] to-[#b0842b] hover:from-[#b0842b] hover:to-[#8a6620] text-white font-semibold shadow-lg shadow-[#cb9833]/30 transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-2"
                            >
                                Jelajahi Produk
                                <ArrowRight className="w-4 h-4" />
                            </Link>
                            <Link
                                href="/contact"
                                className="w-full sm:w-auto px-8 py-4 rounded-full bg-white dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 border border-neutral-200 dark:border-neutral-700 font-semibold hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-all duration-300 flex items-center justify-center gap-2 hover:border-[#cb9833]/50"
                            >
                                Hubungi Kami
                            </Link>
                        </div>

                        <div className="mt-12 flex items-center justify-center lg:justify-start gap-8 text-neutral-500 dark:text-neutral-400 text-sm font-medium">
                            <div className="flex items-center gap-2 group">
                                <div className="p-1.5 bg-[#cb9833]/10 rounded-full group-hover:bg-[#cb9833] transition-colors duration-300">
                                    <ChevronRight className="w-3 h-3 text-[#cb9833] group-hover:text-white" />
                                </div>
                                <span>Terpercaya</span>
                            </div>
                            <div className="flex items-center gap-2 group">
                                <div className="p-1.5 bg-[#cb9833]/10 rounded-full group-hover:bg-[#cb9833] transition-colors duration-300">
                                    <ChevronRight className="w-3 h-3 text-[#cb9833] group-hover:text-white" />
                                </div>
                                <span>Profesional</span>
                            </div>
                            <div className="flex items-center gap-2 group">
                                <div className="p-1.5 bg-[#cb9833]/10 rounded-full group-hover:bg-[#cb9833] transition-colors duration-300">
                                    <ChevronRight className="w-3 h-3 text-[#cb9833] group-hover:text-white" />
                                </div>
                                <span>Inovatif</span>
                            </div>
                        </div>
                    </motion.div>

                    {/* Hero Visual - Golden Touch */}
                    <motion.div
                        initial={{ opacity: 0, scale: 0.95 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 1, delay: 0.2, ease: "easeOut" }}
                        className="flex-1 w-full max-w-lg lg:max-w-none relative"
                    >
                        <div className="relative rounded-2xl overflow-hidden shadow-2xl shadow-[#cb9833]/20 border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 aspect-[4/3] group">
                           {/* Placeholder Hero Image */}
                           <div className="absolute inset-0 bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 flex flex-col items-center justify-center text-neutral-400 dark:text-neutral-500 gap-4">
                                <div className="w-20 h-20 rounded-full bg-[#cb9833]/10 flex items-center justify-center">
                                     <Star className="w-10 h-10 text-[#cb9833] fill-current opacity-50" />
                                </div>
                                <span className="text-sm font-mono tracking-widest uppercase">Visual Area</span>
                           </div>

                           {/* Decorative Floating Cards */}
                           <motion.div
                                animate={{ y: [0, -12, 0] }}
                                transition={{ repeat: Infinity, duration: 6, ease: "easeInOut" }}
                                className="absolute top-10 right-8 p-4 bg-white/90 dark:bg-neutral-800/90 backdrop-blur-sm rounded-xl shadow-xl border border-neutral-100 dark:border-neutral-700 max-w-[180px] z-20"
                           >
                                <div className="flex items-center gap-3 mb-3">
                                    <div className="w-10 h-10 rounded-full bg-gradient-to-br from-[#cb9833] to-[#eab308] flex items-center justify-center text-white shadow-lg shadow-[#cb9833]/30">
                                        <ArrowRight className="w-5 h-5" />
                                    </div>
                                    <span className="text-sm font-bold text-neutral-800 dark:text-neutral-100">Pertumbuhan</span>
                                </div>
                                <div className="h-2 w-full bg-neutral-100 dark:bg-neutral-700 rounded-full overflow-hidden">
                                    <motion.div
                                        initial={{ width: "0%" }}
                                        animate={{ width: "85%" }}
                                        transition={{ duration: 1.5, delay: 1 }}
                                        className="h-full bg-[#cb9833] rounded-full"
                                    />
                                </div>
                           </motion.div>

                            <motion.div
                                animate={{ y: [0, 12, 0] }}
                                transition={{ repeat: Infinity, duration: 7, ease: "easeInOut", delay: 1 }}
                                className="absolute bottom-10 left-8 p-4 bg-white/90 dark:bg-neutral-800/90 backdrop-blur-sm rounded-xl shadow-xl border border-neutral-100 dark:border-neutral-700 max-w-[200px] z-20"
                           >
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 rounded-full bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center text-[#cb9833]">
                                        <Star className="w-6 h-6 fill-current" />
                                    </div>
                                    <div>
                                        <div className="text-sm font-bold text-neutral-800 dark:text-neutral-100">Kualitas Premium</div>
                                        <div className="text-xs text-neutral-500">Standar Tertinggi</div>
                                    </div>
                                </div>
                           </motion.div>
                        </div>
                    </motion.div>
                </div>
            </div>
        </div>
    );
}
