import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head } from '@inertiajs/react';
import { Icon } from '@iconify/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';

interface DashboardProps {
    stats: {
        total_users: number;
        new_users_today: number;
        system_errors: number;
        revenue_month: number;
    };
    recentLogs: any[];
}


export default function SuperDashboard({ stats, recentLogs }: DashboardProps) {
    // Helper format mata uang
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(amount);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/super/dashboard',
        },
    ];

    return (
        <AppSidebarLayout breadcrumbs={breadcrumbs}>
            <Head title="Super Admin Dashboard" />

            <div className="flex flex-col gap-6 p-6">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">Control Tower</h1>
                    <p className="text-muted-foreground">Ringkasan aktivitas sistem dan kesehatan aplikasi.</p>
                </div>

                {/* --- STATS GRID --- */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Total Pengguna</CardTitle>
                            <Icon icon="solar:users-group-rounded-bold" className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{stats.total_users}</div>
                            <p className="text-xs text-muted-foreground">
                                +{stats.new_users_today} hari ini
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Error Sistem</CardTitle>
                            <Icon icon="solar:danger-triangle-bold" className="h-4 w-4 text-red-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-red-500">{stats.system_errors}</div>
                            <p className="text-xs text-muted-foreground">
                                Kritis (Hari ini)
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Pendapatan (Bulan Ini)</CardTitle>
                            <Icon icon="solar:wallet-money-bold" className="h-4 w-4 text-green-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold">{formatCurrency(stats.revenue_month)}</div>
                            <p className="text-xs text-muted-foreground">
                                Transaksi masuk tercatat
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="text-sm font-medium">Status Server</CardTitle>
                            <Icon icon="solar:server-bold" className="h-4 w-4 text-blue-500" />
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-green-600">Online</div>
                            <p className="text-xs text-muted-foreground">
                                Database & Redis connected
                            </p>
                        </CardContent>
                    </Card>
                </div>

                {/* --- RECENT LOGS --- */}
                <Card>
                    <CardHeader>
                        <CardTitle>Aktivitas Sistem Terbaru</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-4">
                            {recentLogs.map((log: any) => (
                                <div key={log.id} className="flex items-start gap-4 border-b pb-4 last:border-0 last:pb-0">
                                    <div className={`mt-1 flex h-8 w-8 items-center justify-center rounded-full bg-muted`}>
                                        <Icon
                                            icon={
                                                log.severity === 'critical' ? 'solar:shield-warning-bold' :
                                                log.severity === 'danger' ? 'solar:close-circle-bold' :
                                                log.severity === 'warning' ? 'solar:info-circle-bold' :
                                                'solar:check-circle-bold'
                                            }
                                            className={`h-4 w-4 ${
                                                log.severity === 'critical' ? 'text-red-600' :
                                                log.severity === 'danger' ? 'text-orange-500' :
                                                log.severity === 'warning' ? 'text-yellow-500' :
                                                'text-green-500'
                                            }`}
                                        />
                                    </div>
                                    <div className="flex-1 space-y-1">
                                        <p className="text-sm font-medium leading-none">
                                            {log.description}
                                        </p>
                                        <div className="flex items-center gap-2 text-xs text-muted-foreground">
                                            <span className="font-semibold text-primary">
                                                {log.causer?.name || 'System'}
                                            </span>
                                            <span>&bull;</span>
                                            <span>{new Date(log.created_at).toLocaleString()}</span>
                                            <span>&bull;</span>
                                            <Badge variant="outline" className="text-[10px] py-0 h-5">
                                                {log.log_name}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            ))}
                            {recentLogs.length === 0 && (
                                <div className="text-center text-muted-foreground">Belum ada aktivitas.</div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppSidebarLayout>
    );
}
