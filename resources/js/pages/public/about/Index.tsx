/* eslint-disable @typescript-eslint/no-explicit-any */
import PublicLayout from '@/layouts/public-layout';
import Hero from '@/components/public/organization/Hero';
import Breadcrumbs from '@/components/public/organization/Breadcrumbs';
import MainPage from '@/components/public/organization/MainPage';
import RightBar from '@/components/public/organization/RightBar';
import RightAdSlot from '@/components/public/organization/RightAdSlot';
import CTAform from '@/components/public/organization/CTAform';
import Media from '@/components/public/organization/Media';

interface PageProps {
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
}

export default function About({ topbarMenu, navbarMenu, footerMenu }: PageProps) {
    return (
        <PublicLayout
            topbarMenu={topbarMenu}
            navbarMenu={navbarMenu}
            footerMenu={footerMenu}
            title="Tentang Kami"
        >
            <Hero
                title="Tentang Sekar Wangi"
                subtitle="Membangun ekosistem bisnis terintegrasi yang berkelanjutan dan memberikan nilai tambah bagi seluruh pemangku kepentingan."
            />

            <div className="container mx-auto px-4 py-12">
                <div className="flex flex-col lg:flex-row gap-8">
                    <div className="flex-1">
                        <Breadcrumbs items={[{ label: 'Tentang Kami' }]} />

                        <MainPage>
                            <h2 className="text-[#cb9833]">Sekilas Perusahaan</h2>
                            <p>
                                Sekar Wangi Enterprise adalah perusahaan yang bergerak di bidang penyediaan solusi bisnis terpadu. Berdiri sejak tahun 2010, kami telah berkembang menjadi salah satu pemain utama dalam industri jasa dan perdagangan, melayani ribuan klien dari berbagai sektor.
                            </p>

                            <Media
                                items={[
                                    { type: 'image', src: 'https://placehold.co/800x450/neutral/white?text=Office+Building', alt: 'Gedung Kantor' },
                                    { type: 'image', src: 'https://placehold.co/800x450/cb9833/white?text=Team+Meeting', alt: 'Pertemuan Tim' }
                                ]}
                            />

                            <h3>Komitmen Kami</h3>
                            <p>
                                Kami berdedikasi untuk memberikan layanan prima dengan mengedepankan kualitas, integritas, dan inovasi. Setiap langkah yang kami ambil didasarkan pada keinginan untuk memberikan dampak positif bagi masyarakat dan lingkungan sekitar.
                            </p>

                            <h3>Jangkauan Layanan</h3>
                            <p>
                                Saat ini, Sekar Wangi Enterprise mencakup berbagai lini bisnis mulai dari perdagangan umum, jasa konsultasi, penyewaan aset, hingga penyelenggaraan acara (Event Organizer). Integrasi antar layanan ini memungkinkan kami menjadi one-stop solution bagi mitra bisnis kami.
                            </p>
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
