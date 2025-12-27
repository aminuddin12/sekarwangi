import MainHero from '@/components/public/MainHero';
import PublicLayout from '@/layouts/public-layout';

interface HomeProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
}

export default function Home({ topbarMenu, navbarMenu, footerMenu }: HomeProps) {
    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Beranda"
        >
            {/* Hero Section */}
            <MainHero />

            {/* Content Section Placeholder */}
            <section className="py-24 bg-neutral-50 dark:bg-neutral-900 relative overflow-hidden">
                <div className="container mx-auto px-4 relative z-10">
                    <div className="text-center mb-16">
                        <span className="text-[#cb9833] font-bold tracking-widest uppercase text-xs mb-3 block">Mengapa Memilih Kami</span>
                        <h2 className="text-3xl md:text-4xl font-bold text-neutral-800 dark:text-white mb-4 font-serif">
                            Layanan Unggulan Kami
                        </h2>
                        <div className="w-16 h-1 bg-[#cb9833] mx-auto rounded-full mb-6" />
                        <p className="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto leading-relaxed">
                            Kami menghadirkan berbagai solusi untuk kebutuhan personal dan bisnis Anda dengan standar kualitas terbaik dan dedikasi penuh.
                        </p>
                    </div>

                    {/* Grid Placeholder */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[1, 2, 3].map((item) => (
                            <div key={item} className="bg-white dark:bg-neutral-800 p-8 rounded-2xl shadow-lg shadow-neutral-200/50 dark:shadow-black/20 border border-neutral-100 dark:border-neutral-700 hover:border-[#cb9833]/30 hover:-translate-y-1 transition-all duration-300 group">
                                <div className="w-14 h-14 bg-[#cb9833]/10 rounded-xl mb-6 flex items-center justify-center text-[#cb9833] group-hover:bg-[#cb9833] group-hover:text-white transition-colors duration-300">
                                    <span className="font-bold text-xl">{item}</span>
                                </div>
                                <h3 className="text-xl font-bold mb-3 text-neutral-800 dark:text-white group-hover:text-[#cb9833] transition-colors">Layanan Profesional {item}</h3>
                                <p className="text-neutral-500 dark:text-neutral-400 text-sm leading-relaxed mb-4">
                                    Memberikan solusi efektif dengan pendekatan personal untuk memastikan kepuasan dan keberhasilan setiap proyek yang kami tangani.
                                </p>
                                <button className="text-[#cb9833] text-sm font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                                    Selengkapnya <span aria-hidden="true">&rarr;</span>
                                </button>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
