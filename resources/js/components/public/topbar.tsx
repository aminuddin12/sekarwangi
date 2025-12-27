import { Link } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';
import * as Icons from 'lucide-react';

interface MenuItem {
    id: number;
    name: string;
    url: string;
    icon?: string;
    color?: string;
    children?: MenuItem[];
}

interface TopbarProps {
    menu: MenuItem[];
}

export default function Topbar({ menu }: TopbarProps) {
    const renderIcon = (iconName?: string, className?: string) => {
        if (!iconName) return null;
        const name = iconName.replace('lucide:', '');
        const Icon = (Icons as any)[name];
        return Icon ? <Icon className={className} /> : null;
    };

    return (
        // Menggunakan bg-neutral-900 untuk tampilan premium, border emas tipis di bawah
        <div className="bg-neutral-900 text-white text-xs py-2 px-4 md:px-8 border-b border-[#cb9833]/30 hidden md:block font-sans">
            <div className="container mx-auto flex justify-between items-center">
                <div className="flex items-center space-x-4">
                   <span className="opacity-80 tracking-wide">Selamat datang di Sekar Wangi Enterprise</span>
                </div>
                <div className="flex items-center space-x-6">
                    {menu.map((item) => (
                        <div key={item.id} className="group relative">
                            {item.url === '#' ? (
                                <button className="flex items-center gap-1.5 hover:text-[#cb9833] transition-colors duration-300 ease-in-out">
                                    {renderIcon(item.icon, `w-3.5 h-3.5 ${item.color?.replace('text-', 'text-')}`)}
                                    <span>{item.name}</span>
                                </button>
                            ) : (
                                <Link
                                    href={item.url}
                                    className="flex items-center gap-1.5 hover:text-[#cb9833] transition-colors duration-300 ease-in-out"
                                >
                                    {renderIcon(item.icon, `w-3.5 h-3.5 ${item.color?.replace('text-', 'text-')}`)}
                                    <span>{item.name}</span>
                                </Link>
                            )}

                            {/* Dropdown */}
                            {item.children && item.children.length > 0 && (
                                <div className="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-neutral-800 rounded-sm shadow-xl py-1 z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 transform origin-top-right border-t-2 border-[#cb9833]">
                                    {item.children.map((child) => (
                                        <Link
                                            key={child.id}
                                            href={child.url}
                                            className="block px-4 py-2 text-neutral-700 dark:text-neutral-200 hover:bg-[#cb9833]/10 dark:hover:bg-[#cb9833]/20 hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors"
                                        >
                                            <div className="flex items-center gap-2">
                                                {renderIcon(child.icon, "w-3 h-3")}
                                                {child.name}
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
