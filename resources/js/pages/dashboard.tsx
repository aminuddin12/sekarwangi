import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import StatsCard from '@/components/widgets/StatsCard';
import RevenueChart from '@/components/widgets/RevenueChart';
import RecentLogs from '@/components/widgets/RecentLogs';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Widget {
    id: string;
    type: 'StatsCard' | 'RevenueChart' | 'RecentLogs';
    order: number;
    props: any;
}

interface DashboardProps {
    smallWidgets?: Widget[];
    largeWidgets?: Widget[];
    userPermissions?: string[];
}

export default function Dashboard({ smallWidgets = [], largeWidgets = [] }: DashboardProps) {

    const renderWidget = (widget: Widget) => {
        switch (widget.type) {
            case 'StatsCard':
                return <StatsCard {...widget.props} />;
            case 'RevenueChart':
                return <RevenueChart {...widget.props} />;
            case 'RecentLogs':
                return <RecentLogs {...widget.props} />;
            default:
                return null;
        }
    };

    // Container variants for staggered animation
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: { opacity: 1, y: 0 }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="flex h-full flex-1 flex-col gap-8 p-4 md:p-8 overflow-y-auto bg-neutral-50/50 dark:bg-neutral-950">
                {/* Header Welcome */}
                <div className="flex flex-col gap-1 mb-2">
                    <h1 className="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white font-serif">
                        Overview
                    </h1>
                    <p className="text-muted-foreground text-sm">
                        Pantau kinerja dan aktivitas sistem Anda secara real-time.
                    </p>
                </div>

                {/* Section 1: Small Widgets (Key Metrics) */}
                {smallWidgets.length > 0 && (
                    <motion.div
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                    >
                        {smallWidgets.map((widget) => (
                            <motion.div key={widget.id} variants={itemVariants}>
                                {renderWidget(widget)}
                            </motion.div>
                        ))}
                    </motion.div>
                )}

                {/* Section 2: Large Widgets (Charts & Logs) */}
                {largeWidgets.length > 0 && (
                    <motion.div
                        variants={containerVariants}
                        initial="hidden"
                        animate="visible"
                        className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 pb-10"
                    >
                        {largeWidgets.map((widget) => (
                            <motion.div
                                key={widget.id}
                                variants={itemVariants}
                                className={widget.type === 'RevenueChart' || widget.id === 'system_health' ? 'lg:col-span-2' : 'col-span-1'}
                            >
                                {renderWidget(widget)}
                            </motion.div>
                        ))}
                    </motion.div>
                )}

                {/* Empty State */}
                {smallWidgets.length === 0 && largeWidgets.length === 0 && (
                    <div className="flex flex-col items-center justify-center h-[50vh] border-2 border-dashed border-neutral-200 dark:border-neutral-800 rounded-xl bg-white/50 dark:bg-neutral-900/50">
                        <div className="p-4 bg-neutral-100 dark:bg-neutral-800 rounded-full mb-4">
                            <span className="text-4xl">👋</span>
                        </div>
                        <h3 className="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Selamat Datang</h3>
                        <p className="text-neutral-500 dark:text-neutral-400 max-w-md text-center mt-2">
                            Dashboard Anda saat ini kosong. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                        </p>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
