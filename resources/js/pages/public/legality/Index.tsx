/* eslint-disable @typescript-eslint/no-explicit-any */
import PublicLayout from '@/layouts/public-layout';
import Hero from '@/components/public/organization/Hero';
import Breadcrumbs from '@/components/public/organization/Breadcrumbs';
import MainPage from '@/components/public/organization/MainPage';
import RightBar from '@/components/public/organization/RightBar';
import RightAdSlot from '@/components/public/organization/RightAdSlot';
import CTAform from '@/components/public/organization/CTAform';
import Document from '@/components/public/organization/Document';

interface PageProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
}

export default function Legality({ topbarMenu, navbarMenu, footerMenu }: PageProps) {
    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Legalitas & Sertifikasi"
        >
            <Hero
                title="Legalitas & Sertifikasi"
                subtitle="Bukti kepatuhan hukum dan standar kualitas yang kami miliki sebagai jaminan keamanan bagi mitra bisnis."
            />

            <div className="container mx-auto px-4 py-12">
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="flex-1">
                        <Breadcrumbs items={[{ label: 'Tentang Kami', url: '/about' }, { label: 'Legalitas' }]} />

                        <MainPage>
                            <h2 className="text-[#cb9833]">Legalitas Perusahaan</h2>
                            <p>
                                Sekar Wangi Enterprise beroperasi di bawah payung hukum yang sah dan mematuhi segala regulasi yang berlaku di Negara Kesatuan Republik Indonesia.
                            </p>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 my-6 not-prose">
                                <div className="p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                    <span className="text-xs text-neutral-500 uppercase tracking-wide">Nomor Induk Berusaha (NIB)</span>
                                    <p className="font-mono font-bold text-lg text-neutral-800 dark:text-white">1234567890123</p>
                                </div>
                                <div className="p-4 border border-neutral-200 dark:border-neutral-700 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                    <span className="text-xs text-neutral-500 uppercase tracking-wide">NPWP Perusahaan</span>
                                    <p className="font-mono font-bold text-lg text-neutral-800 dark:text-white">01.234.567.8-901.000</p>
                                </div>
                            </div>

                            <h3>Dokumen Resmi</h3>
                            <div className="space-y-4 not-prose">
                                <Document
                                    title="Akta Pendirian Perusahaan"
                                    description="Akta Notaris No. XX Tanggal XX Bulan XXXX"
                                    fileUrl="#"
                                />
                                <Document
                                    title="Surat Izin Usaha Perdagangan (SIUP)"
                                    description="Izin operasional kegiatan usaha perdagangan."
                                    fileUrl="#"
                                />
                            </div>

                            <h3>Sertifikasi & Penghargaan</h3>
                            <p>
                                Selain legalitas dasar, kami juga telah mengantongi berbagai sertifikasi yang membuktikan komitmen kami terhadap kualitas layanan.
                            </p>

                            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 not-prose mt-6">
                                {[1, 2, 3, 4].map((i) => (
                                    <div key={i} className="aspect-square bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl flex flex-col items-center justify-center p-4 shadow-sm hover:shadow-md transition-shadow text-center">
                                        <div className="w-12 h-12 bg-[#cb9833]/10 rounded-full flex items-center justify-center text-[#cb9833] mb-2 font-bold">
                                            ISO
                                        </div>
                                        <span className="text-xs font-semibold text-neutral-700 dark:text-neutral-300">ISO 9001:2015</span>
                                    </div>
                                ))}
                            </div>
                        </MainPage>
                    </div>

                    <div className="w-full lg:w-80 flex-shrink-0">
                        <div className="sticky top-24 space-y-6">
                            <RightBar />
                            <RightAdSlot />
                            <CTAform />
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
