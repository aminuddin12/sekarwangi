import { Link, usePage } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

export default function RightBar() {
    const { url } = usePage();

    const menuItems = [
        { label: 'Profil Perusahaan', url: '/about' },
        { label: 'Visi & Misi', url: '/vision-mission' },
        { label: 'Struktur Organisasi', url: '/organization' },
        { label: 'Legalitas & Sertifikasi', url: '/legality' },
    ];

    return (
        <div className="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden mb-6">
            <div className="p-4 bg-[#cb9833] text-white">
                <h3 className="font-bold text-lg">Tentang Kami</h3>
            </div>
            <div className="p-2">
                {menuItems.map((item, index) => {
                    const isActive = url.startsWith(item.url);
                    return (
                        <Link
                            key={index}
                            href={item.url}
                            className={`flex items-center justify-between p-3 rounded-lg mb-1 last:mb-0 transition-all ${
                                isActive
                                    ? 'bg-[#cb9833]/10 text-[#cb9833] font-semibold'
                                    : 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 hover:text-[#cb9833]'
                            }`}
                        >
                            <span>{item.label}</span>
                            {isActive && <ChevronRight className="w-4 h-4" />}
                        </Link>
                    );
                })}
            </div>
        </div>
    );
}
