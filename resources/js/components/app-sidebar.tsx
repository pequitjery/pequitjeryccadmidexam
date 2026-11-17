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
    SidebarSeparator,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Box, LayoutGrid, Settings, Thermometer, Truck, UsersRound, Warehouse } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Trucks',
        url: '/trucks',
        icon: Truck,
    },
    {
        title: 'Drivers',
        url: '/drivers',
        icon: UsersRound,
    },
    {
        title: 'Cargo Types',
        url: '/cargo-types',
        icon: Box,
    },
    {
        title: 'Hubs',
        url: '/hubs',
        icon: Warehouse,
    },
    {
        title: 'Cold Trips',
        url: '/cold-trips',
        icon: Thermometer,
    },
];

const settingsNavItem: NavItem = {
    title: 'Settings',
    url: '/settings',
    icon: Settings,
};

export function AppSidebar() {
    const page = usePage();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            asChild
                            className="text-sidebar-foreground hover:text-sidebar-foreground h-10 gap-3 px-1 hover:bg-transparent"
                        >
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarSeparator className="bg-sidebar-border mx-4 w-auto" />

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <SidebarSeparator className="bg-sidebar-border mx-2 w-auto" />
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            asChild
                            isActive={page.url.startsWith(settingsNavItem.url)}
                            className="text-sidebar-foreground hover:text-sidebar-foreground data-[active=true]:text-sidebar-accent-foreground h-12 px-2 text-base [&>svg]:size-5"
                        >
                            <Link href={settingsNavItem.url} prefetch>
                                <Settings />
                                <span>{settingsNavItem.title}</span>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
