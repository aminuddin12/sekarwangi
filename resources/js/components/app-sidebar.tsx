import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Icon } from '@iconify/react';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid } from 'lucide-react';
import AppLogo from './app-logo';

// Tipe data untuk struktur menu dari backend
interface BackendMenuItem {
    id: number;
    name: string;
    url: string;
    icon?: string;
    children?: BackendMenuItem[];
}

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<any>().props;

    // Ambil menu dari props auth
    const backendMenu: BackendMenuItem[] = auth?.sidebar_menu || [];

    // Transformasi data backend ke format NavItem
    const dynamicNavItems: NavItem[] = backendMenu.map((item) => ({
        title: item.name,
        // URL sudah di-resolve oleh backend, jadi aman langsung dipakai
        href: item.url,
        // @ts-ignore
        icon: (props) => <Icon icon={item.icon || 'lucide:circle'} {...props} className={props.className} />,
        isActive: false,
        items: item.children?.map((child) => ({
            title: child.name,
            href: child.url,
        })),
    }));

    // Gunakan dynamicNavItems jika ada, jika tidak kosongkan (atau tampilkan default dashboard)
    const mainNavItems: NavItem[] = dynamicNavItems.length > 0 ? dynamicNavItems : [
        {
            title: 'Dashboard',
            href: '/dashboard',
            icon: LayoutGrid,
        },
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
