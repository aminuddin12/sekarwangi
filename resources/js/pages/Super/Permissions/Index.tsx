import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Icon } from '@iconify/react';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

interface Permission {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
}

interface Role {
    id: number;
    name: string;
}

interface Props {
    permissions: {
        data: Permission[];
        links: any[];
        meta?: {
            from: number;
            to: number;
            total: number;
        };
    };
    roles: Role[];
}

export default function PermissionIndex({ permissions, roles }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const [searchTerm, setSearchTerm] = useState('');

    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('super.permissions.store'), {
            onSuccess: () => {
                setIsOpen(false);
                reset();
            },
        });
    };

    // Simple Client-side filtering untuk visualisasi cepat
    // (Idealnya search dilakukan di backend jika data sangat banyak)
    const filteredPermissions = permissions.data.filter((p) =>
        p.name.toLowerCase().includes(searchTerm.toLowerCase()),
    );

    return (
        <AppSidebarLayout>
            <Head title="Manajemen Permission" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header Section */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">
                            Permission
                        </h1>
                        <p className="text-muted-foreground">
                            Daftar hak akses granular dalam sistem.
                        </p>
                    </div>

                    <div className="flex items-center gap-2">
                        {/* Search Input */}
                        <div className="relative">
                            <Icon
                                icon="solar:magnifer-linear"
                                className="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground"
                            />
                            <Input
                                placeholder="Cari permission..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full pl-9 md:w-[250px]"
                            />
                        </div>

                        {/* Create Button Dialog */}
                        <Dialog open={isOpen} onOpenChange={setIsOpen}>
                            <DialogTrigger asChild>
                                <Button>
                                    <Icon
                                        icon="solar:add-circle-bold"
                                        className="mr-2 h-4 w-4"
                                    />
                                    Tambah
                                </Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>
                                        Buat Permission Baru
                                    </DialogTitle>
                                    <DialogDescription>
                                        Tambahkan identifier permission baru.
                                        Gunakan format <code>verb-noun</code>{' '}
                                        (contoh: <code>create-posts</code>).
                                    </DialogDescription>
                                </DialogHeader>
                                <form onSubmit={submit} className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">
                                            Nama Permission
                                        </Label>
                                        <Input
                                            id="name"
                                            value={data.name}
                                            onChange={(e) =>
                                                setData('name', e.target.value)
                                            }
                                            placeholder="misal: view-dashboard"
                                            autoFocus
                                        />
                                        <InputError message={errors.name} />
                                    </div>
                                    <DialogFooter>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => setIsOpen(false)}
                                        >
                                            Batal
                                        </Button>
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            {processing
                                                ? 'Menyimpan...'
                                                : 'Simpan'}
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                {/* Main Content: Table */}
                <Card>
                    <CardHeader className="pb-3">
                        <div className="flex items-center justify-between">
                            <CardTitle className="text-base">
                                Daftar Permission ({permissions.data.length})
                            </CardTitle>
                            <div className="flex gap-1">
                                {roles.slice(0, 5).map((role) => (
                                    <Badge
                                        key={role.id}
                                        variant="outline"
                                        className="text-[10px]"
                                    >
                                        {role.name}
                                    </Badge>
                                ))}
                                {roles.length > 5 && (
                                    <Badge
                                        variant="outline"
                                        className="text-[10px]"
                                    >
                                        +{roles.length - 5} roles
                                    </Badge>
                                )}
                            </div>
                        </div>
                        <CardDescription>
                            Permission digunakan untuk membatasi akses pada level
                            Controller dan Policy.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="w-[50px]">
                                        ID
                                    </TableHead>
                                    <TableHead>Nama Permission</TableHead>
                                    <TableHead>Guard</TableHead>
                                    <TableHead>Dibuat Pada</TableHead>
                                    <TableHead className="text-right">
                                        Aksi
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {filteredPermissions.length > 0 ? (
                                    filteredPermissions.map((perm) => (
                                        <TableRow key={perm.id}>
                                            <TableCell className="font-mono text-xs text-muted-foreground">
                                                {perm.id}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Icon
                                                        icon="solar:key-minimalistic-square-linear"
                                                        className="h-4 w-4 text-primary/50"
                                                    />
                                                    <span className="font-medium">
                                                        {perm.name}
                                                    </span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <Badge
                                                    variant="secondary"
                                                    className="font-mono text-xs"
                                                >
                                                    {perm.guard_name}
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-xs text-muted-foreground">
                                                {new Date(
                                                    perm.created_at,
                                                ).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric',
                                                })}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    disabled
                                                    title="Edit feature coming soon"
                                                >
                                                    <Icon
                                                        icon="solar:pen-bold"
                                                        className="h-4 w-4 text-muted-foreground/50"
                                                    />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                ) : (
                                    <TableRow>
                                        <TableCell
                                            colSpan={5}
                                            className="h-24 text-center text-muted-foreground"
                                        >
                                            {searchTerm
                                                ? 'Tidak ada permission yang cocok dengan pencarian.'
                                                : 'Belum ada permission.'}
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                {/* Pagination (Jika backend mengirim link pagination) */}
                {permissions.links && permissions.links.length > 3 && (
                    <div className="flex justify-center gap-1">
                        {permissions.links.map((link, i) => (
                            <Button
                                key={i}
                                variant={link.active ? 'default' : 'outline'}
                                size="sm"
                                className="h-8 w-8 p-0"
                                disabled={!link.url}
                                asChild={!!link.url}
                            >
                                {link.url ? (
                                    <Link
                                        href={link.url}
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    />
                                ) : (
                                    <span
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                    />
                                )}
                            </Button>
                        ))}
                    </div>
                )}
            </div>
        </AppSidebarLayout>
    );
}
