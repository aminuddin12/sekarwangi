import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { Icon } from '@iconify/react';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();

        post('/register', {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Daftar Akun Baru"
            description="Buat akun untuk mengakses layanan Sekarwangi"
        >
            <Head title="Register" />

            <div className="w-full max-w-sm mx-auto">
                <form onSubmit={submit} className="space-y-5">
                    <div className="space-y-2">
                        <Label htmlFor="name">Nama Lengkap</Label>
                        <div className="relative">
                            <Icon icon="solar:user-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                            <Input
                                id="name"
                                name="name"
                                value={data.name}
                                className="pl-10"
                                autoComplete="name"
                                autoFocus
                                onChange={(e) => setData('name', e.target.value)}
                                required
                            />
                        </div>
                        <InputError message={errors.name} />
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="email">Email</Label>
                        <div className="relative">
                            <Icon icon="solar:letter-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="pl-10"
                                autoComplete="username"
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />
                        </div>
                        <InputError message={errors.email} />
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="password">Password</Label>
                        <div className="relative">
                            <Icon icon="solar:lock-password-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="pl-10"
                                autoComplete="new-password"
                                onChange={(e) => setData('password', e.target.value)}
                                required
                            />
                        </div>
                        <InputError message={errors.password} />
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="password_confirmation">Konfirmasi Password</Label>
                        <div className="relative">
                            <Icon icon="solar:lock-password-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                            <Input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                value={data.password_confirmation}
                                className="pl-10"
                                autoComplete="new-password"
                                onChange={(e) =>
                                    setData('password_confirmation', e.target.value)
                                }
                                required
                            />
                        </div>
                        <InputError message={errors.password_confirmation} />
                    </div>

                    <Button
                        type="submit"
                        className="w-full"
                        disabled={processing}
                    >
                        {processing && <Spinner className="mr-2" />}
                        Daftar
                    </Button>

                    <div className="text-center text-sm">
                        Sudah punya akun?{' '}
                        <TextLink href="/login" className="font-medium text-primary hover:underline">
                            Masuk
                        </TextLink>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
