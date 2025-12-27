import { ReactNode } from 'react';
import NavbarLayout from './public/navbar-layout';
import FooterLayout from './public/footer-layout';
import { Head } from '@inertiajs/react';

interface PublicLayoutProps {
    children: ReactNode;
    topbarMenu: any[];
    navbarMenu: any[];
    footerMenu: any[];
    title?: string;
}

export default function PublicLayout({
    children,
    topbarMenu,
    navbarMenu,
    footerMenu,
    title = 'Welcome'
}: PublicLayoutProps) {
    return (
        <NavbarLayout topbarMenu={topbarMenu} navbarMenu={navbarMenu}>
            <Head title={title} />
            <FooterLayout footerMenu={footerMenu}>
                {children}
            </FooterLayout>
        </NavbarLayout>
    );
}
