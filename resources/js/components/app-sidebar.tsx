/* eslint-disable @typescript-eslint/no-explicit-any */
import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Icon } from '@iconify/react';
import { Link, usePage } from '@inertiajs/react';
import { BookOpen, ChevronRight, Folder, LayoutGrid } from 'lucide-react';
import { ComponentProps } from 'react';
import AppLogo from './app-logo';

// Interface Data Backend
interface BackendGroup {
    name: string;
    order: number;
}

interface BackendMenuItem {
    id: number;
    name: string;
    url: string;
    icon?: string;
    children?: BackendMenuItem[];
    group?: BackendGroup;
}

// Helper URL
function resolveUrl(url: string) {
    if (!url || url === '#') return '#';
    if (url.startsWith('http') || url.startsWith('/')) return url;
    return url;
}

export function AppSidebar({ ...props }: ComponentProps<typeof Sidebar>) {
    const { auth } = usePage<any>().props;
    const backendMenu: BackendMenuItem[] = auth?.sidebar_menu || [];

    // Grouping Logic: Mengelompokkan menu berdasarkan group name
    const groupedMenu = backendMenu.reduce((acc, item) => {
        // Fallback ke 'Menu Utama' jika group tidak terdefinisi
        const groupName = item.group?.name || 'Menu Utama';
        const groupOrder = item.group?.order || 0;

        if (!acc[groupName]) {
            acc[groupName] = { order: groupOrder, items: [] };
        }
        acc[groupName].items.push(item);
        return acc;
    }, {} as Record<string, { order: number; items: BackendMenuItem[] }>);

    // Sorting Groups: Urutkan grup berdasarkan order
    const sortedGroups = Object.entries(groupedMenu).sort(([, a], [, b]) => a.order - b.order);

    // Cek apakah ada menu
    const hasMenu = sortedGroups.length > 0;

    const footerNavItems: NavItem[] = [
        { title: 'Repository', href: 'https://github.com/aminuddin12/sekarwangi', icon: Folder },
        { title: 'Documentation', href: 'https://laravel.com/docs/starter-kits#react', icon: BookOpen },
    ];

    return (
        <Sidebar collapsible="icon" variant="inset" {...props}>
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
                {hasMenu ? (
                    sortedGroups.map(([groupName, { items }]) => (
                        <SidebarGroup key={groupName}>
                            <SidebarGroupLabel>{groupName}</SidebarGroupLabel>
                            <SidebarGroupContent>
                                <SidebarMenu>
                                    {items.map((item) => {
                                        const hasChildren = item.children && item.children.length > 0;
                                        const url = resolveUrl(item.url);
                                        const isActive = false; // Logika active state bisa ditambahkan di sini

                                        // RENDER PARENT WITH CHILDREN (COLLAPSIBLE)
                                        if (hasChildren) {
                                            return (
                                                <Collapsible
                                                    key={item.id}
                                                    asChild
                                                    defaultOpen={isActive}
                                                    className="group/collapsible"
                                                >
                                                    <SidebarMenuItem>
                                                        <CollapsibleTrigger asChild>
                                                            <SidebarMenuButton tooltip={item.name}>
                                                                {item.icon && <Icon icon={item.icon} className="size-4" />}
                                                                <span>{item.name}</span>
                                                                <ChevronRight className="ml-auto size-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                                            </SidebarMenuButton>
                                                        </CollapsibleTrigger>
                                                        <CollapsibleContent>
                                                            <SidebarMenuSub>
                                                                {item.children?.map((child) => (
                                                                    <SidebarMenuSubItem key={child.id}>
                                                                        <SidebarMenuSubButton asChild>
                                                                            <Link href={resolveUrl(child.url)}>
                                                                                {child.icon && <Icon icon={child.icon} className="size-4 mr-2" />}
                                                                                <span>{child.name}</span>
                                                                            </Link>
                                                                        </SidebarMenuSubButton>
                                                                    </SidebarMenuSubItem>
                                                                ))}
                                                            </SidebarMenuSub>
                                                        </CollapsibleContent>
                                                    </SidebarMenuItem>
                                                </Collapsible>
                                            );
                                        }

                                        // RENDER SINGLE ITEM
                                        return (
                                            <SidebarMenuItem key={item.id}>
                                                <SidebarMenuButton asChild tooltip={item.name} isActive={isActive}>
                                                    <Link href={url}>
                                                        {item.icon && <Icon icon={item.icon} className="size-4" />}
                                                        <span>{item.name}</span>
                                                    </Link>
                                                </SidebarMenuButton>
                                            </SidebarMenuItem>
                                        );
                                    })}
                                </SidebarMenu>
                            </SidebarGroupContent>
                        </SidebarGroup>
                    ))
                ) : (
                    // Fallback jika tidak ada menu dari backend
                    <SidebarGroup>
                         <SidebarGroupLabel>Menu Utama</SidebarGroupLabel>
                         <SidebarGroupContent>
                            <SidebarMenu>
                                <SidebarMenuItem>
                                    <SidebarMenuButton asChild tooltip="Dashboard">
                                        <Link href="/dashboard">
                                            <LayoutGrid className="size-4" />
                                            <span>Dashboard</span>
                                        </Link>
                                    </SidebarMenuButton>
                                </SidebarMenuItem>
                            </SidebarMenu>
                         </SidebarGroupContent>
                    </SidebarGroup>
                )}
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>

            <SidebarRail />
        </Sidebar>
    );
}
