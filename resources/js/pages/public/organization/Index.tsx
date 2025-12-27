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

export default function Organization({ topbarMenu, navbarMenu, footerMenu }: PageProps) {
    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Struktur Organisasi"
        >
            <Hero
                title="Struktur Organisasi"
                subtitle="Kerangka kerja tim manajemen profesional kami yang berdedikasi untuk memberikan layanan terbaik."
            />

            <div className="container mx-auto px-4 py-12">
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="flex-1">
                        <Breadcrumbs items={[{ label: 'Tentang Kami', url: '/about' }, { label: 'Struktur Organisasi' }]} />

                        <MainPage>
                            <h2 className="text-[#cb9833]">Dewan Direksi & Manajemen</h2>
                            <p>
                                Struktur organisasi Sekar Wangi Enterprise dirancang untuk memastikan pengambilan keputusan yang cepat, operasional yang efisien, dan tata kelola perusahaan yang baik (Good Corporate Governance).
                            </p>

                            <div className="my-8">
                                <img
                                    src="https://placehold.co/1200x600/f5f5f5/cb9833?text=Bagan+Struktur+Organisasi"
                                    alt="Bagan Struktur Organisasi"
                                    className="w-full rounded-xl shadow-md border border-neutral-200 dark:border-neutral-700"
                                />
                                <p className="text-center text-sm text-neutral-500 mt-2">Bagan Struktur Organisasi Terbaru (2025)</p>
                            </div>

                            <h3>Tim Kepemimpinan</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 not-prose">
                                <div className="flex items-center gap-4 p-4 bg-neutral-50 dark:bg-neutral-900 rounded-xl border border-neutral-100 dark:border-neutral-800">
                                    <div className="w-16 h-16 rounded-full bg-[#cb9833]/20 flex items-center justify-center text-[#cb9833] font-bold text-xl">
                                        CEO
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-neutral-800 dark:text-white">Nama CEO</h4>
                                        <p className="text-sm text-neutral-500">Chief Executive Officer</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4 p-4 bg-neutral-50 dark:bg-neutral-900 rounded-xl border border-neutral-100 dark:border-neutral-800">
                                    <div className="w-16 h-16 rounded-full bg-[#cb9833]/20 flex items-center justify-center text-[#cb9833] font-bold text-xl">
                                        COO
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-neutral-800 dark:text-white">Nama COO</h4>
                                        <p className="text-sm text-neutral-500">Chief Operating Officer</p>
                                    </div>
                                </div>
                            </div>

                            <h3 className="mt-8">Dokumen Terkait</h3>
                            <Document
                                title="Surat Keputusan Direksi - Struktur 2025"
                                description="Dokumen resmi penetapan struktur organisasi terbaru."
                                fileUrl="#"
                            />
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
