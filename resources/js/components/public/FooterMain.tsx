import { Link } from '@inertiajs/react';
import * as Icons from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';

interface MenuItem {
    id: number;
    name: string;
    url: string;
    icon?: string;
    color?: string;
    description?: string;
    children?: MenuItem[];
    target?: string;
}

interface FooterProps {
    menu: MenuItem[];
}

export default function FooterMain({ menu }: FooterProps) {
    const renderIcon = (iconName?: string, className?: string) => {
        if (!iconName) return null;
        const name = iconName.replace('lucide:', '');
        const Icon = (Icons as any)[name];
        return Icon ? <Icon className={className} /> : null;
    };

    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-neutral-50 dark:bg-neutral-900 border-t-2 border-[#cb9833] pt-16 pb-8 font-sans">
            <div className="container mx-auto px-4 md:px-8">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    {/* Brand Info */}
                    <div className="space-y-6">
                         <div className="flex items-center gap-3">
                            <div className="w-10 h-10 flex items-center justify-center">
                                <AppLogoIcon className="w-full h-full" />
                            </div>
                            <div className="flex flex-col">
                                <span className="text-lg font-bold text-neutral-800 dark:text-white font-serif leading-none">
                                    Sekar Wangi
                                </span>
                                <span className="text-[10px] text-[#cb9833] tracking-widest uppercase font-semibold">
                                    Enterprise
                                </span>
                            </div>
                        </div>
                        <p className="text-neutral-500 dark:text-neutral-400 text-sm leading-relaxed">
                            Mitra terpercaya untuk solusi bisnis, produk berkualitas, dan layanan profesional yang terintegrasi untuk mendukung kesuksesan Anda.
                        </p>
                        <div className="flex gap-3">
                            {/* Social Icons - Golden Hover */}
                            {[1, 2, 3].map((i) => (
                                <div key={i} className="w-9 h-9 rounded-full bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 hover:border-[#cb9833] hover:bg-[#cb9833] hover:text-white transition-all duration-300 cursor-pointer flex items-center justify-center text-neutral-400">
                                    <div className="w-4 h-4 bg-current rounded-sm" />
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Navigasi Dinamis */}
                    <div className="col-span-1 md:col-span-1 lg:col-span-3 grid grid-cols-2 sm:grid-cols-3 gap-8">
                        <div>
                            <h3 className="font-bold text-neutral-800 dark:text-white mb-6 relative inline-block">
                                Navigasi
                                <span className="absolute -bottom-2 left-0 w-8 h-0.5 bg-[#cb9833]"></span>
                            </h3>
                            <ul className="space-y-3">
                                {menu.slice(0, 4).map((item) => (
                                    <li key={item.id}>
                                        <Link
                                            href={item.url}
                                            className="text-sm text-neutral-500 dark:text-neutral-400 hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors flex items-center gap-2 group"
                                        >
                                            <span className="w-1.5 h-1.5 rounded-full bg-neutral-300 dark:bg-neutral-600 group-hover:bg-[#cb9833] transition-colors"></span>
                                            {item.name}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div>
                            <h3 className="font-bold text-neutral-800 dark:text-white mb-6 relative inline-block">
                                Dukungan
                                <span className="absolute -bottom-2 left-0 w-8 h-0.5 bg-[#cb9833]"></span>
                            </h3>
                            <ul className="space-y-3">
                                {menu.slice(4, 8).map((item) => (
                                    <li key={item.id}>
                                        <Link
                                            href={item.url}
                                            className="text-sm text-neutral-500 dark:text-neutral-400 hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors flex items-center gap-2 group"
                                        >
                                             <span className="w-1.5 h-1.5 rounded-full bg-neutral-300 dark:bg-neutral-600 group-hover:bg-[#cb9833] transition-colors"></span>
                                            {item.name}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>

                         <div>
                            <h3 className="font-bold text-neutral-800 dark:text-white mb-6 relative inline-block">
                                Aplikasi
                                <span className="absolute -bottom-2 left-0 w-8 h-0.5 bg-[#cb9833]"></span>
                            </h3>
                            <ul className="space-y-3">
                                {menu.slice(8).map((item) => (
                                    <li key={item.id}>
                                         <a
                                            href={item.url}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex items-center gap-3 p-3 rounded-lg bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 hover:border-[#cb9833] dark:hover:border-[#cb9833] transition-all duration-300 group shadow-sm hover:shadow-md"
                                        >
                                            <div className="text-neutral-400 group-hover:text-[#cb9833] transition-colors">
                                                {renderIcon(item.icon, "w-6 h-6")}
                                            </div>
                                            <div className="flex flex-col">
                                                <span className="text-[10px] text-neutral-400 leading-none mb-0.5">{item.description}</span>
                                                <span className="text-xs font-bold text-neutral-700 dark:text-neutral-200 group-hover:text-[#cb9833] transition-colors">{item.name}</span>
                                            </div>
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>

                <div className="border-t border-neutral-200 dark:border-neutral-800 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p className="text-xs text-neutral-400 text-center md:text-left">
                        &copy; {currentYear} Sekar Wangi Enterprise. All rights reserved.
                    </p>
                    <div className="flex gap-6 text-xs text-neutral-400 font-medium">
                        <Link href="/privacy-policy" className="hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors">Privasi</Link>
                        <Link href="/terms-conditions" className="hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors">Syarat & Ketentuan</Link>
                    </div>
                </div>
            </div>
        </footer>
    );
}
