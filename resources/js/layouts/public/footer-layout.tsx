import { ReactNode } from 'react';
import FooterMain from '@/components/public/FooterMain';

interface FooterLayoutProps {
    children: ReactNode;
    footerMenu: any[];
}

export default function FooterLayout({ children, footerMenu }: FooterLayoutProps) {
    return (
        <>
            {children}
            <FooterMain menu={footerMenu} />
        </>
    );
}
