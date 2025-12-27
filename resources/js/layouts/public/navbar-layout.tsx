import { ReactNode } from 'react';
import NavbarMain from '../../components/public/NavbarMain';

interface NavbarLayoutProps {
    children: ReactNode;
    topbarMenu: any[];
    navbarMenu: any[];
}

export default function NavbarLayout({ children, topbarMenu, navbarMenu }: NavbarLayoutProps) {
    return (
        <div className="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col">
            {/* Header Area */}
            <NavbarMain topbarMenu={topbarMenu} navbarMenu={navbarMenu} />

            {/* Main Content Area */}
            <main className="flex-grow">
                {children}
            </main>
        </div>
    );
}
