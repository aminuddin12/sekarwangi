import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/react';
import { Icon } from '@iconify/react';
import AuthLayout from '@/layouts/auth-layout';

interface ErrorProps {
    code: number;
    message: string;
    backUrl: string;
}

export default function ErrorPage({ code, message, backUrl }: ErrorProps) {
    return (
        <AuthLayout title={`Error ${code}`} description="Terjadi kesalahan sistem">
            <Head title={`Error ${code}`} />

            <div className="flex flex-col items-center justify-center text-center space-y-6">
                <div className="relative">
                    <div className="absolute inset-0 bg-red-500/20 blur-3xl rounded-full"></div>
                    <Icon
                        icon="solar:shield-warning-bold-duotone"
                        className="relative size-24 text-destructive"
                    />
                </div>

                <div className="space-y-2">
                    <h1 className="text-4xl font-bold tracking-tight text-foreground">{code}</h1>
                    <p className="text-muted-foreground max-w-xs mx-auto">
                        {message}
                    </p>
                </div>

                <Button asChild size="lg" className="gap-2">
                    <Link href={backUrl}>
                        <Icon icon="solar:arrow-left-linear" className="size-5" />
                        Kembali ke Halaman Sebelumnya
                    </Link>
                </Button>
            </div>
        </AuthLayout>
    );
}
