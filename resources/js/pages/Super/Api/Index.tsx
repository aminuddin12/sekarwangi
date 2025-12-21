import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Icon } from '@iconify/react';
import { useState } from 'react';
import InputError from '@/components/input-error';

interface ApiAccount {
    id: number;
    name: string;
    provider: string;
    api_key: string;
    is_active: boolean;
    created_at: string;
}

export default function ApiIndex({ accounts }: { accounts: ApiAccount[] }) {
    const [isOpen, setIsOpen] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        provider: '',
    });

    const createApi = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('super.api-accounts.store'), {
            onSuccess: () => {
                setIsOpen(false);
                reset();
            }
        });
    };

    const rotateKey = (id: number) => {
        if (confirm('Apakah Anda yakin? Kunci lama tidak akan berfungsi lagi.')) {
            // Gunakan router.post manual atau helper form
            // Di sini kita simulasi
            (window as any).location.href = route('super.api-accounts.index');
        }
    };

    return (
        <AppSidebarLayout>
            <Head title="Manajemen API" />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">Integrasi API</h1>
                        <p className="text-muted-foreground">Kelola kunci akses untuk pihak ketiga.</p>
                    </div>

                    <Dialog open={isOpen} onOpenChange={setIsOpen}>
                        <DialogTrigger asChild>
                            <Button>
                                <Icon icon="solar:add-circle-bold" className="mr-2 h-4 w-4" />
                                Buat Akun API
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Buat Kunci API Baru</DialogTitle>
                                <DialogDescription>
                                    Kunci ini digunakan untuk autentikasi sistem eksternal.
                                </DialogDescription>
                            </DialogHeader>
                            <form onSubmit={createApi} className="space-y-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name">Nama Aplikasi / Klien</Label>
                                    <Input
                                        id="name"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        placeholder="Misal: Mobile App Vendor"
                                    />
                                    <InputError message={errors.name} />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="provider">Provider / Vendor</Label>
                                    <Input
                                        id="provider"
                                        value={data.provider}
                                        onChange={e => setData('provider', e.target.value)}
                                        placeholder="Misal: google, midtrans, internal"
                                    />
                                    <InputError message={errors.provider} />
                                </div>
                                <DialogFooter>
                                    <Button type="submit" disabled={processing}>Simpan</Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>

                <Card>
                    <CardContent className="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama Klien</TableHead>
                                    <TableHead>Provider</TableHead>
                                    <TableHead>Public Key</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Dibuat</TableHead>
                                    <TableHead className="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {accounts.map((acc) => (
                                    <TableRow key={acc.id}>
                                        <TableCell className="font-medium">{acc.name}</TableCell>
                                        <TableCell>{acc.provider}</TableCell>
                                        <TableCell className="font-mono text-xs">{acc.api_key}</TableCell>
                                        <TableCell>
                                            <span className={`inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${acc.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                                                {acc.is_active ? 'Active' : 'Revoked'}
                                            </span>
                                        </TableCell>
                                        <TableCell className="text-xs text-muted-foreground">{new Date(acc.created_at).toLocaleDateString()}</TableCell>
                                        <TableCell className="text-right">
                                            <Button variant="ghost" size="sm" onClick={() => rotateKey(acc.id)}>
                                                <Icon icon="solar:restart-bold" className="h-4 w-4" />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {accounts.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={6} className="text-center h-24 text-muted-foreground">
                                            Belum ada akun API.
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AppSidebarLayout>
    );
}
