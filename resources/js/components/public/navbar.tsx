import { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import * as Icons from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { Menu, X, ChevronDown } from 'lucide-react';

interface MenuItem {
    id: number;
    name: string;
    url: string;
    icon?: string;
    color?: string;
    description?: string;
    children?: MenuItem[];
}

interface NavbarProps {
    menu: MenuItem[];
}

export default function Navbar({ menu }: NavbarProps) {
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [activeDropdown, setActiveDropdown] = useState<number | null>(null);

    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 20);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const renderIcon = (iconName?: string, className?: string) => {
        if (!iconName) return null;
        const name = iconName.replace('lucide:', '');
        const Icon = (Icons as any)[name];
        return Icon ? <Icon className={className} /> : null;
    };

    const dropdownVariants = {
        hidden: { opacity: 0, y: 10, scale: 0.98 },
        visible: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.2, ease: "easeOut" } },
        exit: { opacity: 0, y: 10, scale: 0.98, transition: { duration: 0.15, ease: "easeIn" } },
    };

    return (
        <nav
            className={`sticky top-0 z-40 w-full transition-all duration-300 font-sans ${
                scrolled
                    ? 'bg-white/95 dark:bg-neutral-900/95 backdrop-blur-md shadow-lg shadow-neutral-200/20 dark:shadow-black/40 py-3'
                    : 'bg-white dark:bg-neutral-900 py-5 border-b border-neutral-100 dark:border-neutral-800'
            }`}
        >
            <div className="container mx-auto px-4 md:px-8 flex justify-between items-center">
                {/* Logo Section */}
                <Link href="/" className="flex items-center gap-3 group">
                    <div className="transition-transform duration-300 group-hover:scale-105">
                        {/* Menggunakan AppLogoIcon langsung tanpa background biru */}
                         <div className="w-10 h-10 flex items-center justify-center">
                            <AppLogoIcon className="w-full h-full" />
                        </div>
                    </div>
                    <div className="flex flex-col">
                        <span className="text-xl font-bold tracking-tight text-neutral-800 dark:text-[#cb9833] leading-none font-serif">
                            Sekar Wangi
                        </span>
                        <span className="text-[10px] font-semibold text-[#cb9833] dark:text-neutral-400 uppercase tracking-[0.2em] mt-1">
                            Enterprise
                        </span>
                    </div>
                </Link>

                {/* Desktop Menu */}
                <div className="hidden lg:flex items-center gap-2">
                    {menu.map((item) => (
                        <div
                            key={item.id}
                            className="relative px-1"
                            onMouseEnter={() => setActiveDropdown(item.id)}
                            onMouseLeave={() => setActiveDropdown(null)}
                        >
                            <Link
                                href={item.url === '#' ? '#' : item.url}
                                className={`flex items-center gap-1.5 px-3 py-2 rounded-md text-sm font-medium transition-all duration-300 ${
                                    activeDropdown === item.id
                                        ? 'text-[#cb9833] bg-[#cb9833]/10'
                                        : 'text-neutral-600 dark:text-neutral-300 hover:text-[#cb9833] dark:hover:text-[#cb9833] hover:bg-neutral-50 dark:hover:bg-neutral-800'
                                }`}
                            >
                                {renderIcon(item.icon, "w-4 h-4 opacity-70")}
                                {item.name}
                                {item.children && item.children.length > 0 && (
                                    <ChevronDown className={`w-3.5 h-3.5 transition-transform duration-300 ${activeDropdown === item.id ? 'rotate-180 text-[#cb9833]' : ''}`} />
                                )}
                            </Link>

                            <AnimatePresence>
                                {activeDropdown === item.id && item.children && item.children.length > 0 && (
                                    <motion.div
                                        initial="hidden"
                                        animate="visible"
                                        exit="exit"
                                        variants={dropdownVariants}
                                        className="absolute top-full left-0 mt-2 w-80 bg-white dark:bg-neutral-900 rounded-lg shadow-xl border-t-4 border-[#cb9833] overflow-hidden z-50 ring-1 ring-black/5 dark:ring-white/10"
                                    >
                                        <div className="p-3 space-y-1">
                                            {item.children.map((child) => (
                                                <Link
                                                    key={child.id}
                                                    href={child.url}
                                                    className="flex items-start gap-4 p-3 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors group"
                                                >
                                                    <div className={`mt-1 p-2 rounded-full bg-[#cb9833]/10 text-[#cb9833] group-hover:bg-[#cb9833] group-hover:text-white transition-colors duration-300`}>
                                                        {renderIcon(child.icon, "w-5 h-5")}
                                                    </div>
                                                    <div>
                                                        <div className="text-sm font-bold text-neutral-800 dark:text-neutral-200 group-hover:text-[#cb9833] transition-colors">
                                                            {child.name}
                                                        </div>
                                                        {child.description && (
                                                            <div className="text-xs text-neutral-500 dark:text-neutral-400 mt-1 line-clamp-2 font-light">
                                                                {child.description}
                                                            </div>
                                                        )}
                                                    </div>
                                                </Link>
                                            ))}
                                        </div>
                                    </motion.div>
                                )}
                            </AnimatePresence>
                        </div>
                    ))}

                    <div className="ml-6 pl-6 border-l border-neutral-200 dark:border-neutral-700">
                         <Link
                            href="/login"
                            className="px-6 py-2.5 text-sm font-semibold text-white bg-[#cb9833] hover:bg-[#b0842b] rounded-full shadow-lg shadow-[#cb9833]/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[#cb9833]/40"
                        >
                            Masuk
                        </Link>
                    </div>
                </div>

                {/* Mobile Menu Button */}
                <button
                    onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                    className="lg:hidden p-2 text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-md transition-colors"
                >
                    {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
                </button>
            </div>

            {/* Mobile Menu Overlay */}
            <AnimatePresence>
                {mobileMenuOpen && (
                    <motion.div
                        initial={{ height: 0, opacity: 0 }}
                        animate={{ height: 'auto', opacity: 1 }}
                        exit={{ height: 0, opacity: 0 }}
                        transition={{ duration: 0.3 }}
                        className="lg:hidden border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden"
                    >
                        <div className="p-4 space-y-2">
                            {menu.map((item) => (
                                <div key={item.id} className="border-b border-neutral-100 dark:border-neutral-800 last:border-0 pb-2 last:pb-0">
                                    <div className="flex items-center justify-between py-2 text-neutral-700 dark:text-neutral-200 font-medium">
                                        <div className="flex items-center gap-2">
                                            {renderIcon(item.icon, "w-4 h-4 text-[#cb9833]")}
                                            {item.name}
                                        </div>
                                    </div>
                                    {item.children && (
                                        <div className="pl-6 space-y-2 mt-1 mb-2 border-l-2 border-neutral-100 dark:border-neutral-800 ml-2">
                                            {item.children.map((child) => (
                                                <Link
                                                    key={child.id}
                                                    href={child.url}
                                                    className="flex items-center gap-2 py-2 text-sm text-neutral-500 dark:text-neutral-400 hover:text-[#cb9833] dark:hover:text-[#cb9833] transition-colors"
                                                >
                                                     {renderIcon(child.icon, "w-3.5 h-3.5 opacity-70")}
                                                    {child.name}
                                                </Link>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            ))}
                            <div className="pt-6 mt-4">
                                <Link
                                    href="/login"
                                    className="block w-full text-center py-3 bg-[#cb9833] hover:bg-[#b0842b] text-white font-semibold rounded-lg shadow-md transition-colors"
                                >
                                    Masuk Akun
                                </Link>
                            </div>
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
        </nav>
    );
}
