import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, Link, router } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Icon } from '@iconify/react';
import { useState } from 'react';

interface Log {
    id: number;
    log_name: string;
    description: string;
    severity: string;
    causer: { name: string } | null;
    created_at: string;
    ip_address: string;
}

interface LogsProps {
    logs: {
        data: Log[];
        links: any[];
    };
    filters: any;
}

export default function SystemLogs({ logs, filters }: LogsProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [severity, setSeverity] = useState(filters.severity || 'all');

    const handleSearch = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter') {
            router.get(route('super.logs.index'), { search, severity }, { preserveState: true });
        }
    };

    const handleSeverityChange = (val: string) => {
        setSeverity(val);
        router.get(route('super.logs.index'), { search, severity: val === 'all' ? '' : val }, { preserveState: true });
    };

    const getSeverityColor = (severity: string) => {
        switch (severity) {
            case 'critical': return 'destructive';
            case 'danger': return 'destructive';
            case 'warning': return 'warning'; // Custom variant if exists, or use default
            default: return 'outline';
        }
    };

    return (
        <AppSidebarLayout>
            <Head title="System Logs" />

            <div className="flex flex-col gap-6 p-6">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">Audit Trail</h1>
                    <p className="text-muted-foreground">Riwayat aktivitas dan error sistem.</p>
                </div>

                <div className="flex flex-col sm:flex-row gap-4">
                    <div className="relative flex-1">
                        <Icon icon="solar:magnifer-linear" className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input
                            placeholder="Cari deskripsi, user, atau IP..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            onKeyDown={handleSearch}
                            className="pl-9"
                        />
                    </div>
                    <Select value={severity} onValueChange={handleSeverityChange}>
                        <SelectTrigger className="w-[180px]">
                            <SelectValue placeholder="Filter Severity" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">Semua Level</SelectItem>
                            <SelectItem value="info">Info</SelectItem>
                            <SelectItem value="warning">Warning</SelectItem>
                            <SelectItem value="danger">Danger</SelectItem>
                            <SelectItem value="critical">Critical</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <Card>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Waktu</TableHead>
                                    <TableHead>Level</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>User</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead>IP</TableHead>
                                    <TableHead></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {logs.data.map((log) => (
                                    <TableRow key={log.id}>
                                        <TableCell className="whitespace-nowrap text-xs text-muted-foreground">
                                            {new Date(log.created_at).toLocaleString()}
                                        </TableCell>
                                        <TableCell>
                                            <Badge variant={getSeverityColor(log.severity) as any}>
                                                {log.severity}
                                            </Badge>
                                        </TableCell>
                                        <TableCell className="font-mono text-xs">{log.log_name}</TableCell>
                                        <TableCell>{log.causer?.name || 'System'}</TableCell>
                                        <TableCell className="max-w-md truncate" title={log.description}>
                                            {log.description}
                                        </TableCell>
                                        <TableCell className="font-mono text-xs">{log.ip_address}</TableCell>
                                        <TableCell>
                                            <Button variant="ghost" size="icon" asChild>
                                                <Link href={route('super.logs.show', log.id)}>
                                                    <Icon icon="solar:eye-bold" className="h-4 w-4" />
                                                </Link>
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {logs.data.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={7} className="text-center h-24 text-muted-foreground">
                                            Tidak ada log yang ditemukan.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                {/* Pagination Sederhana */}
                <div className="flex justify-end gap-2">
                    {logs.links.map((link: any, i: number) => (
                        <Button
                            key={i}
                            variant={link.active ? 'default' : 'outline'}
                            size="sm"
                            disabled={!link.url}
                            asChild={!!link.url}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        >
                            {link.url ? <Link href={link.url} /> : null}
                        </Button>
                    ))}
                </div>
            </div>
        </AppSidebarLayout>
    );
}
