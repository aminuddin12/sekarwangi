import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Icon } from '@iconify/react';
import { useState } from 'react';
import InputError from '@/components/input-error';

interface SettingItem {
    id: number;
    key: string;
    value: any;
    type: string;
    description: string;
}

interface SettingsProps {
    groupedSettings: Record<string, SettingItem[]>;
}

export default function SiteSettings({ groupedSettings }: SettingsProps) {
    // Flatten settings untuk initial form data
    const initialData = Object.values(groupedSettings)
        .flat()
        .reduce((acc: any, item) => {
            acc[item.key] = item.value;
            return acc;
        }, {});

    const { data, setData, put, processing, errors } = useForm(initialData);
    const [activeTab, setActiveTab] = useState(Object.keys(groupedSettings)[0] || 'site');

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('super.settings.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AppSidebarLayout>
            <Head title="Pengaturan Sistem" />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">Pengaturan Sistem</h1>
                        <p className="text-muted-foreground">Kelola konfigurasi global aplikasi.</p>
                    </div>
                    <Button onClick={submit} disabled={processing}>
                        {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                    </Button>
                </div>

                <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                    <TabsList className="w-full justify-start overflow-x-auto">
                        {Object.keys(groupedSettings).map((group) => (
                            <TabsTrigger key={group} value={group} className="capitalize">
                                {group}
                            </TabsTrigger>
                        ))}
                    </TabsList>

                    {Object.entries(groupedSettings).map(([group, items]) => (
                        <TabsContent key={group} value={group}>
                            <Card>
                                <CardHeader>
                                    <CardTitle className="capitalize">Konfigurasi {group}</CardTitle>
                                    <CardDescription>
                                        Sesuaikan parameter untuk modul {group}.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-6">
                                    {items.map((setting) => (
                                        <div key={setting.id} className="grid gap-2">
                                            <Label htmlFor={setting.key} className="flex items-center gap-2">
                                                {setting.description || setting.key}
                                                <span className="text-xs text-muted-foreground font-normal">({setting.key})</span>
                                            </Label>

                                            {setting.type === 'boolean' ? (
                                                <div className="flex items-center gap-2">
                                                    <input
                                                        type="checkbox"
                                                        id={setting.key}
                                                        checked={data[setting.key] == '1' || data[setting.key] === true}
                                                        onChange={(e) => setData(setting.key, e.target.checked)}
                                                        className="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                                    />
                                                    <span className="text-sm text-muted-foreground">Aktifkan fitur ini</span>
                                                </div>
                                            ) : setting.type === 'textarea' ? (
                                                <textarea
                                                    id={setting.key}
                                                    value={data[setting.key] || ''}
                                                    onChange={(e) => setData(setting.key, e.target.value)}
                                                    className="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                />
                                            ) : (
                                                <Input
                                                    id={setting.key}
                                                    type={setting.type === 'integer' ? 'number' : 'text'}
                                                    value={data[setting.key] || ''}
                                                    onChange={(e) => setData(setting.key, e.target.value)}
                                                />
                                            )}

                                            {errors[setting.key] && (
                                                <InputError message={errors[setting.key]} />
                                            )}
                                        </div>
                                    ))}
                                </CardContent>
                            </Card>
                        </TabsContent>
                    ))}
                </Tabs>
            </div>
        </AppSidebarLayout>
    );
}
