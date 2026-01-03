import { Card, CardContent } from '@/components/ui/card';
import { Users, Wallet, Package, Activity, TrendingUp, HelpCircle, Calendar, FileText, ArrowUpRight, ArrowDownRight } from 'lucide-react';

const iconMap: Record<string, any> = {
    'users': Users,
    'wallet': Wallet,
    'package-alert': Package,
    'activity': Activity,
    'trending-up': TrendingUp,
    'calendar': Calendar,
    'file-text': FileText,
};

interface StatsCardProps {
    title: string;
    value: string | number;
    icon: string;
    color: string;
    description?: string;
    trend?: 'up' | 'down' | 'neutral'; // Optional trend indicator
}

export default function StatsCard({ title, value, icon, color, description, trend }: StatsCardProps) {
    const IconComponent = iconMap[icon] || HelpCircle;

    const colorConfigs: Record<string, string> = {
        blue: 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 border-blue-100 dark:border-blue-800',
        emerald: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
        orange: 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400 border-orange-100 dark:border-orange-800',
        purple: 'bg-purple-50 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400 border-purple-100 dark:border-purple-800',
        default: 'bg-neutral-50 text-neutral-600 dark:bg-neutral-800/50 dark:text-neutral-400 border-neutral-100 dark:border-neutral-700',
    };

    const activeConfig = colorConfigs[color] || colorConfigs.default;

    return (
        <Card className="hover:shadow-md transition-shadow duration-300 border-l-4 overflow-hidden relative" style={{ borderLeftColor: `var(--${color}-500)` }}>
            {/* Background Icon Decoration */}
            <div className="absolute right-[-10px] top-[-10px] opacity-[0.03] dark:opacity-[0.05] pointer-events-none transform rotate-12 scale-150">
                <IconComponent className="w-24 h-24" />
            </div>

            <CardContent className="p-6">
                <div className="flex items-start justify-between">
                    <div>
                        <p className="text-sm font-medium text-muted-foreground mb-1">{title}</p>
                        <h3 className="text-2xl font-bold tracking-tight text-foreground">{value}</h3>
                    </div>
                    <div className={`p-3 rounded-xl border ${activeConfig}`}>
                        <IconComponent className="h-5 w-5" />
                    </div>
                </div>

                {description && (
                    <div className="mt-4 flex items-center text-xs">
                        {/* Contoh indikator trend sederhana jika diperlukan nanti */}
                        {trend === 'up' && <ArrowUpRight className="w-3 h-3 text-emerald-500 mr-1" />}
                        {trend === 'down' && <ArrowDownRight className="w-3 h-3 text-red-500 mr-1" />}
                        <span className="text-muted-foreground">{description}</span>
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
