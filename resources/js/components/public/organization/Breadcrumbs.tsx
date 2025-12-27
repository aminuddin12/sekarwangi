import { Link } from '@inertiajs/react';
import { ChevronRight, Home } from 'lucide-react';

interface BreadcrumbItem {
    label: string;
    url?: string;
}

interface BreadcrumbsProps {
    items: BreadcrumbItem[];
}

export default function Breadcrumbs({ items }: BreadcrumbsProps) {
    return (
        <nav className="flex mb-6" aria-label="Breadcrumb">
            <ol className="inline-flex items-center space-x-1 md:space-x-3">
                <li className="inline-flex items-center">
                    <Link href="/" className="inline-flex items-center text-sm font-medium text-neutral-500 hover:text-[#cb9833] dark:text-neutral-400 dark:hover:text-white">
                        <Home className="w-4 h-4 mr-2" />
                        Beranda
                    </Link>
                </li>
                {items.map((item, index) => (
                    <li key={index}>
                        <div className="flex items-center">
                            <ChevronRight className="w-4 h-4 text-neutral-400" />
                            {item.url ? (
                                <Link href={item.url} className="ml-1 text-sm font-medium text-neutral-700 hover:text-[#cb9833] md:ml-2 dark:text-neutral-300 dark:hover:text-white">
                                    {item.label}
                                </Link>
                            ) : (
                                <span className="ml-1 text-sm font-medium text-[#cb9833] md:ml-2">
                                    {item.label}
                                </span>
                            )}
                        </div>
                    </li>
                ))}
            </ol>
        </nav>
    );
}
