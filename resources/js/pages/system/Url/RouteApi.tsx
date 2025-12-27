import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Icon } from '@iconify/react';

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

interface Permission {
    id: number;
    name: string;
}

interface Props {
    items: UrlItem[];
    permissions: Permission[];
    onEdit: (item: UrlItem) => void;
    onDelete: (id: number) => void;
}

export default function RouteApi({ items, permissions, onEdit, onDelete }: Props) {
    const getMethodColor = (method?: string) => {
        switch (method) {
            case 'GET': return 'bg-blue-100 text-blue-700 hover:bg-blue-100/80';
            case 'POST': return 'bg-green-100 text-green-700 hover:bg-green-100/80';
            case 'PUT': return 'bg-orange-100 text-orange-700 hover:bg-orange-100/80';
            case 'DELETE': return 'bg-red-100 text-red-700 hover:bg-red-100/80';
            default: return 'bg-gray-100 text-gray-700';
        }
    };

    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Endpoint</TableHead>
                    <TableHead>Method</TableHead>
                    <TableHead>Route Name</TableHead>
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
                        <TableRow key={item.id}>
                            <TableCell>
                                <div className="flex flex-col">
                                    <span className="font-medium text-sm">{item.name}</span>
                                    <span className="text-xs font-mono bg-muted px-1 rounded w-fit mt-1">{item.url}</span>
                                </div>
                            </TableCell>
                            <TableCell>
                                <Badge className={getMethodColor(item.method) + " border-none"}>
                                    {item.method}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <span className="text-xs text-muted-foreground">{item.route || '-'}</span>
                            </TableCell>
                            <TableCell>
                                {item.permission_id ? (
                                    <Badge variant="secondary" className="text-[10px]">
                                        {permissions.find(p => p.id === Number(item.permission_id))?.name || item.permission_id}
                                    </Badge>
                                ) : <span className="text-xs text-muted-foreground italic">Public</span>}
                            </TableCell>
                            <TableCell>
                                <Badge variant={item.is_active ? 'default' : 'destructive'} className="text-[10px]">
                                    {item.is_active ? 'Aktif' : 'Nonaktif'}
                                </Badge>
                            </TableCell>
                            <TableCell className="text-right">
                                <div className="flex justify-end gap-2">
                                    <Button variant="ghost" size="icon" onClick={() => onEdit(item)}>
                                        <Icon icon="lucide:pencil" className="size-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" onClick={() => onDelete(item.id)}>
                                        <Icon icon="lucide:trash" className="size-4 text-destructive" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    ))
                )}
            </TableBody>
        </Table>
    );
}
