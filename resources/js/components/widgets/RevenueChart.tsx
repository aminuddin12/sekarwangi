import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { BarChart3, MoreHorizontal, Download } from 'lucide-react';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"

interface RevenueChartProps {
    title: string;
    data: number[];
    labels: string[];
}

export default function RevenueChart({ title, data, labels }: RevenueChartProps) {
    const maxVal = Math.max(...data, 1);

    return (
        <Card className="h-full flex flex-col shadow-sm hover:shadow-md transition-shadow duration-300">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="flex items-center gap-2 text-lg font-bold text-neutral-800 dark:text-neutral-100">
                    <div className="p-1.5 bg-neutral-100 dark:bg-neutral-800 rounded-lg">
                        <BarChart3 className="h-4 w-4 text-[#cb9833]" />
                    </div>
                    {title}
                </CardTitle>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon" className="h-8 w-8 text-muted-foreground hover:text-foreground">
                            <MoreHorizontal className="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Opsi</DropdownMenuLabel>
                        <DropdownMenuItem>
                            <Download className="mr-2 h-4 w-4" /> Export Data
                        </DropdownMenuItem>
                        <DropdownMenuItem>Lihat Detail</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </CardHeader>
            <CardContent className="flex-1 flex flex-col justify-end pt-6 pb-2">
                <div className="flex items-end justify-between gap-3 h-52 w-full px-2">
                    {data.map((value, index) => (
                        <div key={index} className="flex flex-col items-center gap-2 flex-1 group cursor-pointer">
                            <div className="relative w-full flex justify-end flex-col items-center h-full">
                                {/* Bar */}
                                <div
                                    className="w-full max-w-[40px] bg-neutral-200 dark:bg-neutral-800 rounded-t-sm relative overflow-hidden group-hover:bg-[#cb9833]/20 transition-colors"
                                    style={{ height: '100%' }}
                                >
                                    <div
                                        className="absolute bottom-0 left-0 w-full bg-[#cb9833] rounded-t-sm transition-all duration-700 ease-out group-hover:bg-[#b0842b]"
                                        style={{ height: `${(value / maxVal) * 100}%` }}
                                    ></div>
                                </div>

                                {/* Tooltip */}
                                <div className="absolute -top-12 left-1/2 -translate-x-1/2 bg-neutral-900 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0 pointer-events-none z-10 shadow-lg whitespace-nowrap">
                                    {value}
                                    <div className="absolute bottom-[-4px] left-1/2 -translate-x-1/2 w-2 h-2 bg-neutral-900 rotate-45"></div>
                                </div>
                            </div>
                            <span className="text-[10px] uppercase font-bold text-muted-foreground group-hover:text-[#cb9833] transition-colors">{labels[index]}</span>
                        </div>
                    ))}
                </div>
            </CardContent>
        </Card>
    );
}
