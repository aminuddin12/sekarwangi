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
import { Fragment } from 'react';

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

interface Props {
    items: UrlItem[];
    onEdit: (item: UrlItem) => void;
    onDelete: (id: number) => void;
}

export default function PublicLink({ items, onEdit, onDelete }: Props) {
    const renderRows = (nodes: UrlItem[], depth = 0) => {
        return nodes.map((item) => (
            <Fragment key={item.id}>
                <TableRow className={depth > 0 ? 'bg-muted/30' : ''}>
                    <TableCell className={depth > 0 ? 'pl-8' : ''}>
                        <div className="flex items-center gap-2">
                            {depth > 0 && <Icon icon="lucide:corner-down-right" className="size-4 text-muted-foreground" />}
                            <span className="font-medium">{item.name}</span>
                        </div>
                    </TableCell>
                    <TableCell>
                        <div className="flex flex-col">
                            <span className="text-xs font-mono bg-muted px-1 rounded w-fit">{item.url}</span>
                            {item.route && <span className="text-[10px] text-muted-foreground">{item.route}</span>}
                        </div>
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
                {item.children && item.children.length > 0 && renderRows(item.children, depth + 1)}
            </Fragment>
        ));
    };

    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Nama</TableHead>
                    <TableHead>URL / Route</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="text-right">Aksi</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {items.length === 0 ? (
                    <TableRow>
                        <TableCell colSpan={4} className="text-center text-muted-foreground h-24">Data Kosong</TableCell>
                    </TableRow>
                ) : (
                    renderRows(items)
                )}
            </TableBody>
        </Table>
    );
}
