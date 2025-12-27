/* eslint-disable @typescript-eslint/no-explicit-any */
import PublicLayout from '@/layouts/public-layout';
import Hero from '@/components/public/organization/Hero';
import Breadcrumbs from '@/components/public/organization/Breadcrumbs';
import MainPage from '@/components/public/organization/MainPage';
import RightBar from '@/components/public/organization/RightBar';
import RightAdSlot from '@/components/public/organization/RightAdSlot';
import CTAform from '@/components/public/organization/CTAform';

interface PageProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
}

export default function VisionMission({ topbarMenu, navbarMenu, footerMenu }: PageProps) {
    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Visi & Misi"
        >
            <Hero
                title="Visi & Misi"
                subtitle="Arah tujuan dan nilai-nilai fundamental yang menjadi landasan setiap langkah operasional kami."
            />

            <div className="container mx-auto px-4 py-12">
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="flex-1">
                        <Breadcrumbs items={[{ label: 'Tentang Kami', url: '/about' }, { label: 'Visi & Misi' }]} />

                        <MainPage>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                                <div className="bg-neutral-50 dark:bg-neutral-900 p-8 rounded-xl border-t-4 border-[#cb9833] shadow-sm">
                                    <h2 className="mt-0 text-[#cb9833] text-2xl">Visi</h2>
                                    <p className="italic text-lg">
                                        "Menjadi perusahaan terintegrasi terkemuka yang menjadi katalis pertumbuhan ekonomi melalui inovasi layanan dan pemberdayaan sumber daya manusia."
                                    </p>
                                </div>
                                <div className="bg-neutral-50 dark:bg-neutral-900 p-8 rounded-xl border-t-4 border-[#cb9833] shadow-sm">
                                    <h2 className="mt-0 text-[#cb9833] text-2xl">Misi</h2>
                                    <ul className="list-disc pl-4 space-y-2">
                                        <li>Menyediakan produk dan layanan berkualitas tinggi yang melampaui ekspektasi pelanggan.</li>
                                        <li>Membangun ekosistem bisnis yang berkelanjutan dan saling menguntungkan.</li>
                                        <li>Mengembangkan kompetensi SDM yang profesional dan berintegritas.</li>
                                        <li>Menerapkan teknologi terkini untuk efisiensi dan efektivitas operasional.</li>
                                    </ul>
                                </div>
                            </div>

                            <h3 className="text-center">Nilai-Nilai Inti (Core Values)</h3>
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center mt-6">
                                <div className="p-4 rounded-lg bg-[#cb9833]/5">
                                    <h4 className="text-[#cb9833] mt-2">Integritas</h4>
                                    <p className="text-sm">Menjunjung tinggi kejujuran dan etika bisnis.</p>
                                </div>
                                <div className="p-4 rounded-lg bg-[#cb9833]/5">
                                    <h4 className="text-[#cb9833] mt-2">Inovasi</h4>
                                    <p className="text-sm">Terus beradaptasi dan menciptakan solusi baru.</p>
                                </div>
                                <div className="p-4 rounded-lg bg-[#cb9833]/5">
                                    <h4 className="text-[#cb9833] mt-2">Kolaborasi</h4>
                                    <p className="text-sm">Membangun sinergi kuat dengan mitra.</p>
                                </div>
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
