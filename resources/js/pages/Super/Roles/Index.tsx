import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Icon } from '@iconify/react';
import { Badge } from '@/components/ui/badge';

interface Role {
    id: number;
    name: string;
    users_count: number;
    permissions: { id: number; name: string }[];
}

interface Props {
    roles: Role[];
    allPermissions: Record<string, { id: number; name: string }[]>;
}

export default function RoleIndex({ roles, allPermissions }: Props) {
    return (
        <AppSidebarLayout>
            <Head title="Manajemen Role" />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">Role & Hak Akses</h1>
                        <p className="text-muted-foreground">Atur kewenangan pengguna dalam sistem.</p>
                    </div>
                    <Button>
                        <Icon icon="solar:add-circle-bold" className="mr-2 h-4 w-4" />
                        Tambah Role
                    </Button>
                </div>

                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {roles.map((role) => (
                        <Card key={role.id} className="flex flex-col h-full">
                            <CardHeader>
                                <div className="flex justify-between items-start">
                                    <div>
                                        <CardTitle className="capitalize">{role.name.replace('-', ' ')}</CardTitle>
                                        <CardDescription>{role.users_count} Pengguna aktif</CardDescription>
                                    </div>
                                    <Icon icon="solar:shield-user-bold" className="h-8 w-8 text-primary/20" />
                                </div>
                            </CardHeader>
                            <CardContent className="flex-1">
                                <div className="space-y-4">
                                    <div className="text-sm font-medium text-muted-foreground">Akses Utama:</div>
                                    <div className="flex flex-wrap gap-2">
                                        {role.permissions.slice(0, 5).map(perm => (
                                            <Badge key={perm.id} variant="secondary" className="text-[10px]">
                                                {perm.name}
                                            </Badge>
                                        ))}
                                        {role.permissions.length > 5 && (
                                            <Badge variant="outline" className="text-[10px]">
                                                +{role.permissions.length - 5} lainnya
                                            </Badge>
                                        )}
                                        {role.permissions.length === 0 && (
                                            <span className="text-xs text-muted-foreground italic">Tidak ada permission khusus</span>
                                        )}
                                    </div>
                                </div>
                            </CardContent>
                            <div className="p-6 pt-0 mt-auto">
                                <Button variant="outline" className="w-full">
                                    <Icon icon="solar:settings-bold" className="mr-2 h-4 w-4" />
                                    Konfigurasi
                                </Button>
                            </div>
                        </Card>
                    ))}
                </div>
            </div>
        </AppSidebarLayout>
    );
}
