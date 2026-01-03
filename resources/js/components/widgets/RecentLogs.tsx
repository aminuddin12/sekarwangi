import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollText, AlertCircle, Info, AlertTriangle, CheckCircle2 } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

interface LogItem {
    id: number;
    action: string;
    description?: string;
    user: string;
    time: string;
    severity: 'info' | 'warning' | 'error' | 'success';
}

interface RecentLogsProps {
    title: string;
    logs: LogItem[];
}

export default function RecentLogs({ title, logs }: RecentLogsProps) {
    const getSeverityConfig = (severity: string) => {
        switch (severity) {
            case 'error':
                return { icon: AlertCircle, color: 'text-red-500', bg: 'bg-red-50 dark:bg-red-900/20' };
            case 'warning':
                return { icon: AlertTriangle, color: 'text-orange-500', bg: 'bg-orange-50 dark:bg-orange-900/20' };
            case 'success':
                return { icon: CheckCircle2, color: 'text-emerald-500', bg: 'bg-emerald-50 dark:bg-emerald-900/20' };
            default:
                return { icon: Info, color: 'text-blue-500', bg: 'bg-blue-50 dark:bg-blue-900/20' };
        }
    };

    return (
        <Card className="h-full shadow-sm hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between pb-4 border-b border-neutral-100 dark:border-neutral-800">
                <CardTitle className="flex items-center gap-2 text-lg font-bold text-neutral-800 dark:text-neutral-100">
                    <ScrollText className="h-5 w-5 text-neutral-500" />
                    {title}
                </CardTitle>
                <Button variant="ghost" size="sm" className="text-xs h-8">
                    Lihat Semua
                </Button>
            </CardHeader>
            <CardContent className="p-0">
                {logs.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-12 text-muted-foreground">
                        <Info className="w-10 h-10 mb-3 opacity-20" />
                        <p className="text-sm">Tidak ada data untuk ditampilkan.</p>
                    </div>
                ) : (
                    <div className="divide-y divide-neutral-100 dark:divide-neutral-800">
                        {logs.map((log) => {
                            const config = getSeverityConfig(log.severity);
                            const Icon = config.icon;

                            return (
                                <div key={log.id} className="flex items-center gap-4 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors group">
                                    <div className={`p-2 rounded-full flex-shrink-0 ${config.bg}`}>
                                        <Icon className={`w-4 h-4 ${config.color}`} />
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="flex items-center justify-between mb-0.5">
                                            <p className="text-sm font-semibold text-neutral-800 dark:text-neutral-200 truncate">
                                                {log.action}
                                            </p>
                                            <span className="text-[10px] text-muted-foreground whitespace-nowrap ml-2 bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 rounded-full">
                                                {log.time}
                                            </span>
                                        </div>
                                        <div className="flex items-center justify-between">
                                            <p className="text-xs text-muted-foreground truncate max-w-[80%]">
                                                {log.description || 'Tidak ada detail tambahan'}
                                            </p>
                                            <span className="text-[10px] font-medium text-neutral-500 dark:text-neutral-400 group-hover:text-[#cb9833] transition-colors">
                                                {log.user}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </CardContent>
        </Card>
    );
}
