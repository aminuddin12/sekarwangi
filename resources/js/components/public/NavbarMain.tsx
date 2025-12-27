import Topbar from './topbar';
import Navbar from './navbar';

interface HeaderProps {
    topbarMenu: any[];
    navbarMenu: any[];
}

export default function NavbarMain({ topbarMenu, navbarMenu }: HeaderProps) {
    return (
        <header className="flex flex-col w-full z-50">
            <Topbar menu={topbarMenu} />
            <Navbar menu={navbarMenu} />
        </header>
    );
}
