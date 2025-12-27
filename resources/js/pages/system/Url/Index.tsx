import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import { DragDropContext, Draggable, Droppable, DropResult } from '@hello-pangea/dnd';
import { Icon } from '@iconify/react';
import { Head, router, useForm } from '@inertiajs/react';
import { Fragment, useEffect, useState } from 'react';
import { toast } from 'sonner';
import PublicLink from './PublicLink';
import RouteApi from './RouteApi';

interface UrlItem {
    id: number;
    group_id: number;
    parent_id?: number | null;
    name: string;
    url: string;
    route?: string;
    icon?: string;
    permission_id?: number;
    is_active: boolean;
    order: number;
    children?: UrlItem[];
    method?: string;
}

interface UrlGroup {
    id: number;
    name: string;
    slug: string;
    section: string;
    authenticated_menus: UrlItem[];
    public_links: UrlItem[];
    apis: UrlItem[];
    order: number;
}

interface Permission {
    id: number;
    name: string;
}

interface Props {
    groups: UrlGroup[];
    permissions: Permission[];
}

export default function UrlIndex({ groups: initialGroups, permissions }: Props) {
    const [activeTab, setActiveTab] = useState('authenticated');
    const [groups, setGroups] = useState<UrlGroup[]>(initialGroups);
    const [editItem, setEditItem] = useState<UrlItem | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [createType, setCreateType] = useState('authenticated');

    // State khusus untuk menampung flat list menu authenticated agar DnD mudah
    const [flatAuthMenus, setFlatAuthMenus] = useState<Record<number, UrlItem[]>>({});
    const [unsavedChanges, setUnsavedChanges] = useState(false);
    const [isReorderMode, setIsReorderMode] = useState(false);

    // Helper untuk meratakan tree menjadi flat list untuk keperluan DnD
    const flattenMenus = (items: UrlItem[]): UrlItem[] => {
        let flat: UrlItem[] = [];
        items.forEach(item => {
            flat.push(item);
            if (item.children && item.children.length > 0) {
                flat = [...flat, ...flattenMenus(item.children)];
            }
        });
        return flat;
    };

    useEffect(() => {
        setGroups(initialGroups);

        // Inisialisasi flat list per grup untuk DnD
        const initialFlatMenus: Record<number, UrlItem[]> = {};
        initialGroups.forEach(g => {
            if (g.authenticated_menus) {
                initialFlatMenus[g.id] = flattenMenus(g.authenticated_menus);
            }
        });
        setFlatAuthMenus(initialFlatMenus);
        setUnsavedChanges(false);
    }, [initialGroups]);

    const getBaseUrl = () => window.location.pathname;

    const {
        data,
        setData,
        post,
        put,
        delete: destroy,
        processing,
        errors,
        reset,
    } = useForm({
        type: 'authenticated',
        group_id: '',
        parent_id: '',
        name: '',
        url: '',
        route: '',
        icon: '',
        permission_id: '',
        method: 'GET',
        order: 0,
        is_active: true,
    });

    const openCreate = (type: string, groupId?: number) => {
        reset();
        setCreateType(type);
        // Cerdas memilih default group:
        // 1. Jika groupId diberikan (dari tombol + di grup tertentu), pakai itu.
        // 2. Jika tidak, cari grup pertama yang sesuai 'section'.
        // 3. Fallback: Grup pertama apa saja.
        let defaultGroupId = groupId?.toString() || '';

        if (!defaultGroupId) {
            const targetSection = type === 'api' ? 'system' : type;
            const foundGroup = groups.find(g => g.section === targetSection);
            defaultGroupId = foundGroup ? foundGroup.id.toString() : (groups[0]?.id.toString() || '');
        }

        setData((prev) => ({
            ...prev,
            type: type,
            group_id: defaultGroupId,
        }));
        setEditItem(null);
        setIsDialogOpen(true);
    };

    const openEdit = (item: UrlItem, type: string) => {
        setEditItem(item);
        setCreateType(type);
        setData({
            type: type,
            group_id: item.group_id.toString(),
            parent_id: item.parent_id?.toString() || '',
            name: item.name,
            url: item.url,
            route: item.route || '',
            icon: item.icon || '',
            permission_id: item.permission_id?.toString() || '',
            method: item.method || 'GET',
            order: item.order || 0,
            is_active: item.is_active,
        });
        setIsDialogOpen(true);
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        const baseUrl = getBaseUrl();
        if (editItem) {
            put(`${baseUrl}/${editItem.id}`, {
                onSuccess: () => setIsDialogOpen(false),
            });
        } else {
            post(baseUrl, {
                onSuccess: () => setIsDialogOpen(false),
            });
        }
    };

    const handleDelete = (id: number, type: string) => {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            destroy(`${getBaseUrl()}/${id}`, {
                data: { type },
            });
        }
    };

    const onDragEnd = (result: DropResult) => {
        if (!result.destination) return;

        const { source, destination, type } = result;

        if (source.droppableId === destination.droppableId && source.index === destination.index) {
            return;
        }

        if (type === 'GROUP') {
            const newGroups = Array.from(groups);
            const [movedGroup] = newGroups.splice(source.index, 1);
            newGroups.splice(destination.index, 0, movedGroup);
            newGroups.forEach((g, index) => g.order = index + 1);
            setGroups(newGroups);
            setUnsavedChanges(true);
            return;
        }

        if (type === 'MENU_ITEM') {
            const sourceGroupId = parseInt(source.droppableId);
            const destGroupId = parseInt(destination.droppableId);

            const sourceList = Array.from(flatAuthMenus[sourceGroupId] || []);
            const destList = sourceGroupId === destGroupId ? sourceList : Array.from(flatAuthMenus[destGroupId] || []);

            const [movedItem] = sourceList.splice(source.index, 1);

            if (sourceGroupId !== destGroupId) {
                movedItem.group_id = destGroupId;
                movedItem.parent_id = null; // Reset parent jika pindah grup
            }

            destList.splice(destination.index, 0, movedItem);

            // Re-order visual
            if (sourceGroupId === destGroupId) {
                 sourceList.forEach((item, idx) => item.order = idx + 1);
            } else {
                 sourceList.forEach((item, idx) => item.order = idx + 1);
                 destList.forEach((item, idx) => item.order = idx + 1);
            }

            setFlatAuthMenus({
                ...flatAuthMenus,
                [sourceGroupId]: sourceList,
                [destGroupId]: destList,
            });
            setUnsavedChanges(true);
        }
    };

    const toggleIndent = (groupId: number, itemIndex: number, direction: 'in' | 'out') => {
        const currentList = Array.from(flatAuthMenus[groupId]);
        const currentItem = currentList[itemIndex];
        const prevItem = currentList[itemIndex - 1];

        if (direction === 'in' && prevItem) {
            if (prevItem.id === currentItem.id) return;
            currentItem.parent_id = prevItem.id;
            setFlatAuthMenus({ ...flatAuthMenus, [groupId]: currentList });
            setUnsavedChanges(true);
            toast.success(`Item "${currentItem.name}" dijadikan sub-menu`);
        } else if (direction === 'out') {
            currentItem.parent_id = null;
            setFlatAuthMenus({ ...flatAuthMenus, [groupId]: currentList });
            setUnsavedChanges(true);
            toast.success(`Item "${currentItem.name}" dijadikan menu utama`);
        }
    };

    const saveOrder = () => {
        const payloadGroups = groups.map(g => {
            const items = flatAuthMenus[g.id] || [];
            return {
                id: g.id,
                order: g.order,
                items: items.map((m, idx) => ({
                    id: m.id,
                    order: idx + 1,
                    group_id: g.id,
                    parent_id: m.parent_id
                }))
            };
        });

        router.post(`${getBaseUrl()}/reorder`, { groups: payloadGroups }, {
            onSuccess: () => {
                setUnsavedChanges(false);
                setIsReorderMode(false);
                toast.success('Struktur menu berhasil diperbarui!');
            }
        });
    };

    // Filter Logic untuk Tab
    const authGroups = groups.filter(g => g.section === 'authenticated');
    // Public: Ambil group section 'public' ATAU yang punya item public_links
    const publicGroups = groups.filter(g => g.section === 'public' || (g.public_links && g.public_links.length > 0));
    // API: Ambil group section 'system' ATAU yang punya item apis
    const apiGroups = groups.filter(g => g.section === 'system' || (g.apis && g.apis.length > 0));


    // --- RENDERERS ---

    const renderAuthenticatedBuilder = () => (
        <div className="space-y-6">
            <div className="flex items-center justify-between bg-yellow-500/10 p-4 rounded-lg border border-yellow-500/20">
                <div className="flex items-center gap-3">
                    <div className="p-2 bg-yellow-500 rounded-full text-white">
                        <Icon icon="solar:sort-vertical-bold" className="size-5" />
                    </div>
                    <div>
                        <h3 className="font-semibold text-yellow-600 dark:text-yellow-400">Mode Pengaturan Menu</h3>
                        <p className="text-xs text-muted-foreground">Drag & Drop untuk mengatur urutan. Gunakan tombol panah untuk membuat sub-menu.</p>
                    </div>
                </div>
                <div className="flex gap-2">
                    <Button variant="outline" size="sm" onClick={() => { setIsReorderMode(false); setGroups(initialGroups); setUnsavedChanges(false); }}>
                        Batal
                    </Button>
                    <Button size="sm" onClick={saveOrder} disabled={!unsavedChanges}>
                        Simpan Struktur
                    </Button>
                </div>
            </div>

            <DragDropContext onDragEnd={onDragEnd}>
                <Droppable droppableId="groups-list" type="GROUP">
                    {(provided) => (
                        <div {...provided.droppableProps} ref={provided.innerRef} className="space-y-4">
                            {authGroups.map((group, index) => (
                                <Draggable key={group.id} draggableId={`group-${group.id}`} index={index}>
                                    {(provided) => (
                                        <div
                                            ref={provided.innerRef}
                                            {...provided.draggableProps}
                                            className="border rounded-lg bg-card text-card-foreground shadow-sm overflow-hidden"
                                        >
                                            <div className="p-3 bg-muted/40 border-b flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div {...provided.dragHandleProps} className="cursor-grab hover:text-primary p-1 rounded hover:bg-background">
                                                        <Icon icon="solar:hamburger-menu-linear" className="size-5 text-muted-foreground" />
                                                    </div>
                                                    <div className="flex items-center gap-2">
                                                        <span className="font-semibold text-sm">{group.name}</span>
                                                        <Badge variant="outline" className="text-[10px] h-5">{(flatAuthMenus[group.id] || []).length} items</Badge>
                                                    </div>
                                                </div>
                                                <div className="flex gap-1">
                                                    <Button variant="ghost" size="icon" className="h-7 w-7" onClick={() => openCreate('authenticated', group.id)}>
                                                        <Icon icon="solar:add-circle-linear" className="size-4" />
                                                    </Button>
                                                </div>
                                            </div>

                                            <Droppable droppableId={group.id.toString()} type="MENU_ITEM">
                                                {(provided) => (
                                                    <div
                                                        ref={provided.innerRef}
                                                        {...provided.droppableProps}
                                                        className="p-2 space-y-2 min-h-[50px]"
                                                    >
                                                        {(flatAuthMenus[group.id] || []).map((item, itemIndex) => (
                                                            <Draggable key={item.id} draggableId={item.id.toString()} index={itemIndex}>
                                                                {(provided, snapshot) => (
                                                                    <div
                                                                        ref={provided.innerRef}
                                                                        {...provided.draggableProps}
                                                                        className={`
                                                                            flex items-center gap-3 p-3 rounded-md border text-sm transition-all bg-background
                                                                            ${snapshot.isDragging ? 'bg-primary/5 border-primary shadow-lg z-50 scale-105' : 'hover:bg-muted/50'}
                                                                            ${item.parent_id ? 'ml-8 border-l-4 border-l-primary/20' : ''}
                                                                        `}
                                                                    >
                                                                        <div {...provided.dragHandleProps} className="cursor-grab text-muted-foreground hover:text-foreground p-1">
                                                                            <Icon icon="solar:reorder-bold" className="size-4" />
                                                                        </div>

                                                                        <div className="flex items-center gap-3 flex-1 min-w-0">
                                                                            <div className={`flex items-center justify-center size-8 rounded ${item.icon ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'}`}>
                                                                                <Icon icon={item.icon || 'lucide:circle'} className="size-4" />
                                                                            </div>
                                                                            <div className="flex flex-col min-w-0">
                                                                                <div className="flex items-center gap-2">
                                                                                    <span className="font-medium truncate">{item.name}</span>
                                                                                    {!item.is_active && <Badge variant="destructive" className="text-[10px] h-4 px-1">Nonaktif</Badge>}
                                                                                </div>
                                                                                <span className="text-[10px] text-muted-foreground font-mono truncate">{item.url}</span>
                                                                            </div>
                                                                        </div>

                                                                        <div className="flex items-center gap-1">
                                                                            <TooltipProvider>
                                                                                <Tooltip>
                                                                                    <TooltipTrigger asChild>
                                                                                        <Button
                                                                                            variant="ghost" size="icon" className="h-7 w-7"
                                                                                            onClick={() => toggleIndent(group.id, itemIndex, 'out')}
                                                                                            disabled={!item.parent_id}
                                                                                        >
                                                                                            <Icon icon="solar:arrow-left-linear" className="size-4" />
                                                                                        </Button>
                                                                                    </TooltipTrigger>
                                                                                    <TooltipContent><p>Jadikan Menu Utama</p></TooltipContent>
                                                                                </Tooltip>

                                                                                <Tooltip>
                                                                                    <TooltipTrigger asChild>
                                                                                        <Button
                                                                                            variant="ghost" size="icon" className="h-7 w-7"
                                                                                            onClick={() => toggleIndent(group.id, itemIndex, 'in')}
                                                                                            disabled={itemIndex === 0 || !!item.parent_id}
                                                                                        >
                                                                                            <Icon icon="solar:arrow-right-linear" className="size-4" />
                                                                                        </Button>
                                                                                    </TooltipTrigger>
                                                                                    <TooltipContent><p>Jadikan Sub-menu</p></TooltipContent>
                                                                                </Tooltip>
                                                                            </TooltipProvider>

                                                                            <div className="w-px h-4 bg-border mx-1"></div>

                                                                            <Button variant="ghost" size="icon" className="h-7 w-7" onClick={() => openEdit(item, 'authenticated')}>
                                                                                <Icon icon="solar:pen-linear" className="size-4" />
                                                                            </Button>
                                                                            <Button variant="ghost" size="icon" className="h-7 w-7 text-destructive hover:text-destructive" onClick={() => handleDelete(item.id, 'authenticated')}>
                                                                                <Icon icon="solar:trash-bin-trash-linear" className="size-4" />
                                                                            </Button>
                                                                        </div>
                                                                    </div>
                                                                )}
                                                            </Draggable>
                                                        ))}
                                                        {provided.placeholder}
                                                    </div>
                                                )}
                                            </Droppable>
                                        </div>
                                    )}
                                </Draggable>
                            ))}
                            {provided.placeholder}
                        </div>
                    )}
                </Droppable>
            </DragDropContext>
        </div>
    );

    const renderTable = (items: UrlItem[], type: string) => (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Nama</TableHead>
                    <TableHead>URL / Route</TableHead>
                    <TableHead>Icon</TableHead>
                    <TableHead>Permission</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="text-right">Aksi</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {items.length === 0 ? (
                    <TableRow>
                        <TableCell colSpan={6} className="text-center text-muted-foreground h-24">Data Kosong</TableCell>
                    </TableRow>
                ) : (
                    items.map((item) => (
                        <Fragment key={item.id}>
                            <TableRow className={item.parent_id ? 'bg-muted/30' : ''}>
                                <TableCell>
                                    <div className="flex items-center gap-2">
                                        {item.parent_id && <Icon icon="lucide:corner-down-right" className="size-4 text-muted-foreground" />}
                                        <span className="font-medium">{item.name}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div className="flex flex-col">
                                        <span className="text-xs font-mono bg-muted px-1 rounded">{item.url}</span>
                                        {item.route && <span className="text-[10px] text-muted-foreground">{item.route}</span>}
                                        {item.method && <Badge variant="outline" className="w-fit text-[10px] mt-1">{item.method}</Badge>}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {item.icon && <Icon icon={item.icon} className="size-5 text-muted-foreground" />}
                                </TableCell>
                                <TableCell>
                                    {item.permission_id ? (
                                        <Badge variant="secondary" className="text-[10px]">
                                            {permissions.find(p => p.id === Number(item.permission_id))?.name || item.permission_id}
                                        </Badge>
                                    ) : '-'}
                                </TableCell>
                                <TableCell>
                                    <Badge variant={item.is_active ? 'default' : 'destructive'} className="text-[10px]">
                                        {item.is_active ? 'Aktif' : 'Nonaktif'}
                                    </Badge>
                                </TableCell>
                                <TableCell className="text-right">
                                    <div className="flex justify-end gap-2">
                                        <Button variant="ghost" size="icon" onClick={() => openEdit(item, type)}>
                                            <Icon icon="lucide:pencil" className="size-4" />
                                        </Button>
                                        <Button variant="ghost" size="icon" onClick={() => handleDelete(item.id, type)}>
                                            <Icon icon="lucide:trash" className="size-4 text-destructive" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            {item.children && item.children.map(child => (
                                <TableRow key={child.id} className="bg-muted/50">
                                    <TableCell className="pl-8">
                                        <div className="flex items-center gap-2">
                                            <Icon icon="lucide:corner-down-right" className="size-4 text-muted-foreground" />
                                            <span>{child.name}</span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div className="flex flex-col">
                                            <span className="text-xs font-mono">{child.url}</span>
                                            {child.route && <span className="text-[10px] text-muted-foreground">{child.route}</span>}
                                        </div>
                                    </TableCell>
                                    <TableCell>{child.icon && <Icon icon={child.icon} className="size-4" />}</TableCell>
                                    <TableCell>
                                        {child.permission_id && (
                                            <Badge variant="outline" className="text-[10px]">
                                                {permissions.find(p => p.id === Number(child.permission_id))?.name}
                                            </Badge>
                                        )}
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant={child.is_active ? 'default' : 'destructive'} className="text-[10px]">
                                            {child.is_active ? 'Aktif' : 'Nonaktif'}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <div className="flex justify-end gap-2">
                                            <Button variant="ghost" size="icon" onClick={() => openEdit(child, type)}>
                                                <Icon icon="lucide:pencil" className="size-4" />
                                            </Button>
                                            <Button variant="ghost" size="icon" onClick={() => handleDelete(child.id, type)}>
                                                <Icon icon="lucide:trash" className="size-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </Fragment>
                    ))
                )}
            </TableBody>
        </Table>
    );

    return (
        <AppSidebarLayout>
            <Head title="Manajemen URL" />

            <div className="flex flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">Manajemen URL</h1>
                        <p className="text-muted-foreground">Kelola menu sidebar, link publik, dan endpoint API.</p>
                    </div>

                    {!isReorderMode && activeTab === 'authenticated' && (
                        <div className="flex items-center gap-3">
                            <Button onClick={() => setIsReorderMode(true)} variant="secondary">
                                <Icon icon="solar:sort-vertical-bold" className="mr-2 size-4" />
                                Atur Struktur Menu
                            </Button>

                            <Button onClick={() => openCreate(activeTab)}>
                                <Icon icon="lucide:plus" className="mr-2 size-4" />
                                Tambah {activeTab === 'api' ? 'Endpoint' : 'Menu'}
                            </Button>
                        </div>
                    )}

                    {!isReorderMode && activeTab !== 'authenticated' && (
                        <div className="flex items-center gap-3">
                            <Button onClick={() => openCreate(activeTab)}>
                                <Icon icon="lucide:plus" className="mr-2 size-4" />
                                Tambah {activeTab === 'api' ? 'Endpoint' : 'Menu'}
                            </Button>
                        </div>
                    )}
                </div>

                <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                    <TabsList className="grid w-full grid-cols-3 lg:w-[400px]">
                        <TabsTrigger value="authenticated">Menu Sidebar</TabsTrigger>
                        <TabsTrigger value="public">Link Publik</TabsTrigger>
                        <TabsTrigger value="api">Route API</TabsTrigger>
                    </TabsList>

                    <TabsContent value="authenticated" className="space-y-4 mt-4">
                        {isReorderMode ? (
                            renderAuthenticatedBuilder()
                        ) : (
                            authGroups.length > 0 ? (
                                authGroups.map(group => (
                                    <Card key={group.id}>
                                        <CardHeader className="py-4">
                                            <CardTitle className="text-lg flex items-center gap-2">
                                                <Icon icon="lucide:folder" className="size-5 text-primary" />
                                                {group.name}
                                            </CardTitle>
                                        </CardHeader>
                                        <CardContent className="p-0">
                                            {renderTable(group.authenticated_menus, 'authenticated')}
                                        </CardContent>
                                    </Card>
                                ))
                            ) : (
                                <Card>
                                    <CardHeader><CardTitle>Menu Sidebar</CardTitle></CardHeader>
                                    <CardContent className="p-0 text-center py-8 text-muted-foreground">
                                        Belum ada menu yang dibuat.
                                    </CardContent>
                                </Card>
                            )
                        )}
                    </TabsContent>

                    <TabsContent value="public" className="space-y-4 mt-4">
                        {publicGroups.length > 0 ? (
                            publicGroups.map(group => (
                                <Card key={group.id}>
                                    <CardHeader className="py-4">
                                        <CardTitle className="text-lg">{group.name}</CardTitle>
                                    </CardHeader>
                                    <CardContent className="p-0">
                                        <PublicLink
                                            items={group.public_links}
                                            onEdit={(item) => openEdit(item, 'public')}
                                            onDelete={(id) => handleDelete(id, 'public')}
                                        />
                                    </CardContent>
                                </Card>
                            ))
                        ) : (
                            <Card>
                                <CardHeader><CardTitle>Link Publik</CardTitle></CardHeader>
                                <CardContent className="p-0">
                                    <PublicLink items={[]} onEdit={() => {}} onDelete={() => {}} />
                                </CardContent>
                            </Card>
                        )}
                    </TabsContent>

                    <TabsContent value="api" className="space-y-4 mt-4">
                        {apiGroups.length > 0 ? (
                            apiGroups.map(group => (
                                <Card key={group.id}>
                                    <CardHeader className="py-4">
                                        <CardTitle className="text-lg">{group.name}</CardTitle>
                                    </CardHeader>
                                    <CardContent className="p-0">
                                        <RouteApi
                                            items={group.apis}
                                            permissions={permissions}
                                            onEdit={(item) => openEdit(item, 'api')}
                                            onDelete={(id) => handleDelete(id, 'api')}
                                        />
                                    </CardContent>
                                </Card>
                            ))
                        ) : (
                            <Card>
                                <CardHeader><CardTitle>Route API</CardTitle></CardHeader>
                                <CardContent className="p-0">
                                    <RouteApi items={[]} permissions={permissions} onEdit={() => {}} onDelete={() => {}} />
                                </CardContent>
                            </Card>
                        )}
                    </TabsContent>
                </Tabs>

                <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                    <DialogContent className="sm:max-w-[500px]">
                        <DialogHeader>
                            <DialogTitle>{editItem ? 'Edit URL' : 'Tambah URL Baru'}</DialogTitle>
                        </DialogHeader>
                        <form onSubmit={submit} className="space-y-4 py-4">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>Group</Label>
                                    <Select
                                        value={data.group_id}
                                        onValueChange={(val) => setData('group_id', val)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih Group" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {/* Tampilkan semua group jika mode API/Public, agar fleksibel */}
                                            {groups.map(g => (
                                                <SelectItem key={g.id} value={g.id.toString()}>{g.name}</SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.group_id} />
                                </div>
                                <div className="space-y-2">
                                    <Label>Urutan</Label>
                                    <Input
                                        type="number"
                                        value={data.order}
                                        onChange={e => setData('order', parseInt(e.target.value))}
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label>Nama Menu / Endpoint</Label>
                                <Input
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    placeholder="Contoh: Dashboard"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label>URL Path</Label>
                                    <Input
                                        value={data.url}
                                        onChange={e => setData('url', e.target.value)}
                                        placeholder="/dashboard"
                                    />
                                    <InputError message={errors.url} />
                                </div>
                                {createType !== 'api' && (
                                    <div className="space-y-2">
                                        <Label>Route Name</Label>
                                        <Input
                                            value={data.route}
                                            onChange={e => setData('route', e.target.value)}
                                            placeholder="dashboard.index"
                                        />
                                    </div>
                                )}
                                {createType === 'api' && (
                                    <div className="space-y-2">
                                        <Label>Method</Label>
                                        <Select value={data.method} onValueChange={val => setData('method', val)}>
                                            <SelectTrigger><SelectValue /></SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="GET">GET</SelectItem>
                                                <SelectItem value="POST">POST</SelectItem>
                                                <SelectItem value="PUT">PUT</SelectItem>
                                                <SelectItem value="DELETE">DELETE</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                )}
                            </div>

                            {createType !== 'api' && (
                                <div className="space-y-2">
                                    <Label>Icon (Iconify)</Label>
                                    <div className="flex gap-2">
                                        <div className="flex items-center justify-center size-10 border rounded bg-muted">
                                            {data.icon ? <Icon icon={data.icon} className="size-6" /> : <Icon icon="lucide:image" />}
                                        </div>
                                        <Input
                                            value={data.icon}
                                            onChange={e => setData('icon', e.target.value)}
                                            placeholder="lucide:home"
                                            className="flex-1"
                                        />
                                    </div>
                                </div>
                            )}

                            <div className="space-y-2">
                                <Label>Permission (Opsional)</Label>
                                <Select
                                    value={data.permission_id}
                                    onValueChange={(val) => setData('permission_id', val)}
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Permission" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="0">Tidak ada (Public)</SelectItem>
                                        {permissions.map(p => (
                                            <SelectItem key={p.id} value={p.id.toString()}>{p.name}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <DialogFooter>
                                <Button type="button" variant="outline" onClick={() => setIsDialogOpen(false)}>Batal</Button>
                                <Button type="submit" disabled={processing}>{editItem ? 'Simpan Perubahan' : 'Buat Baru'}</Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </AppSidebarLayout>
    );
}
