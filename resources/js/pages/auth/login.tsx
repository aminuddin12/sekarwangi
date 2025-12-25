/* eslint-disable react-hooks/set-state-in-effect */
/* eslint-disable @typescript-eslint/no-explicit-any */
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { Icon } from '@iconify/react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { FormEvent, useEffect, useState } from 'react';

// Definisi Tipe Login untuk Logic Frontend
type LoginType = 'email' | 'username' | 'member_id' | 'whatsapp' | 'unknown';

interface LoginProps {
    status?: string;
    canResetPassword?: boolean;
    canRegister?: boolean;
}

export default function Login({
    status,
    canResetPassword,
    canRegister,
}: LoginProps) {
    // Mengambil Shared Props (Settings) dari Middleware Inertia
    const { settings } = usePage<any>().props;
    const companyCode = settings?.company_code || 'SKW'; // Default prefix Member ID

    // State untuk Logika UI
    const [loginType, setLoginType] = useState<LoginType>('unknown');
    const [showOtpInput, setShowOtpInput] = useState(false);

    // Menggunakan useForm Inertia
    const { data, setData, post, processing, errors, reset } = useForm({
        identity: '',
        password: '',
        otp: '',
        remember: false,
        login_type: 'email',
    });

    // --- LOGIC: DETEKSI TIPE INPUT SECARA REAL-TIME ---
    useEffect(() => {
        const input = data.identity.trim();
        let detectedType: LoginType = 'unknown'; // Gunakan variabel lokal agar sinkron

        if (!input) {
            detectedType = 'unknown';
        }
        // 1. Deteksi WhatsApp (Awalan 08, 62, atau +62)
        else if (/^(\+62|62|08)/.test(input)) {
            detectedType = 'whatsapp';
        }
        // 2. Deteksi Username (Awalan @)
        else if (input.startsWith('@')) {
            detectedType = 'username';
        }
        // 3. Deteksi Member ID (Awalan Kode Perusahaan)
        else if (input.toUpperCase().startsWith(companyCode.toUpperCase())) {
            detectedType = 'member_id';
        }
        // 4. Deteksi Email (Regex Standar)
        else if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input)) {
            detectedType = 'email';
        }
        // Fallback
        else {
            detectedType = 'unknown';
        }

        // Update State UI
        setLoginType(detectedType);

        // Update Logic OTP UI
        setShowOtpInput(detectedType === 'whatsapp');

        // Update login_type untuk dikirim ke backend
        // PENTING: Gunakan detectedType (variabel lokal) bukan loginType (state async)
        setData((prev) => ({
            ...prev,
            login_type: detectedType === 'unknown' ? 'email' : detectedType,
        }));
    }, [data.identity, companyCode]); // Dependensi useEffect

    // Handler Submit Form
    const submit = (e: FormEvent) => {
        e.preventDefault();

        // Skenario: Login WhatsApp via OTP (Simulasi)
        if (loginType === 'whatsapp' && !data.otp) {
            alert('Fitur Request OTP akan dipanggil di sini. Silakan isi OTP dummy.');
            return;
        }

        // Submit ke route login default
        post('/login', {
            onFinish: () => reset('password', 'otp'),
        });
    };

    // Helper: Mendapatkan Icon Input Identity (Menggunakan Solar Icons)
    const getIdentityIcon = () => {
        switch (loginType) {
            case 'whatsapp':
                return <Icon icon="solar:phone-bold" className="size-5 text-green-500" />;
            case 'username':
                return <Icon icon="solar:user-circle-bold" className="size-5 text-purple-500" />;
            case 'member_id':
                return <Icon icon="solar:qr-code-bold" className="size-5 text-orange-500" />;
            case 'email':
                return <Icon icon="solar:letter-bold" className="size-5 text-blue-500" />;
            default:
                return <Icon icon="solar:user-bold" className="size-5 text-muted-foreground" />;
        }
    };

    // Helper: Label Dinamis
    const getIdentityLabel = () => {
        switch (loginType) {
            case 'whatsapp': return 'Nomor WhatsApp';
            case 'username': return 'Username (@)';
            case 'member_id': return 'ID Anggota';
            case 'email': return 'Alamat Email';
            default: return 'Email / ID / Username / WhatsApp';
        }
    };

    return (
        <AuthLayout
            title="Masuk ke Sistem"
            description="Silakan masuk untuk mengakses dashboard organisasi."
        >
            <Head title="Masuk ke Sistem" />

            <div className="w-full max-w-sm mx-auto">
                <motion.div
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.5 }}
                >
                    {status && (
                        <div className="mb-6 rounded-md bg-green-500/10 p-4 text-sm font-medium text-green-600">
                            {status}
                        </div>
                    )}

                    <form onSubmit={submit} className="space-y-5">
                        {/* --- 1. INPUT IDENTITY (MULTI-TYPE) --- */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="identity"
                                className="flex items-center justify-between"
                            >
                                <span className="transition-all">
                                    {getIdentityLabel()}
                                </span>
                                {/* Badge Tipe Login */}
                                {loginType !== 'unknown' && (
                                    <span className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground bg-muted px-2 py-0.5 rounded-full">
                                        {loginType.replace('_', ' ')}
                                    </span>
                                )}
                            </Label>

                            <div className="relative group">
                                {/* Icon Kiri */}
                                <div className="absolute left-3 top-2.5 transition-colors group-focus-within:text-primary">
                                    {getIdentityIcon()}
                                </div>

                                {/* Prefix +62 (Hanya muncul jika WhatsApp detected) */}
                                {loginType === 'whatsapp' && (
                                    <div className="absolute left-9 top-1.5 flex h-6 items-center rounded bg-muted px-2 text-xs font-bold text-foreground">
                                        +62
                                    </div>
                                )}

                                <Input
                                    id="identity"
                                    name="identity"
                                    type="text"
                                    value={data.identity}
                                    onChange={(e) =>
                                        setData('identity', e.target.value)
                                    }
                                    className={`pl-10 transition-all ${
                                        loginType === 'whatsapp'
                                            ? 'pl-[4.5rem]'
                                            : ''
                                    }`}
                                    placeholder={
                                        loginType === 'unknown'
                                            ? 'Email, @username, SKW-..., atau 08...'
                                            : ''
                                    }
                                    autoFocus
                                    required
                                />
                            </div>
                            <InputError message={errors.identity} />
                        </div>

                        {/* --- 2. INPUT PASSWORD ATAU OTP (Dynamic Switch) --- */}
                        <AnimatePresence mode="wait">
                            {showOtpInput ? (
                                /* --- OTP INPUT MODE --- */
                                <motion.div
                                    key="otp-field"
                                    initial={{ opacity: 0, height: 0 }}
                                    animate={{ opacity: 1, height: 'auto' }}
                                    exit={{ opacity: 0, height: 0 }}
                                    className="space-y-2"
                                >
                                    <Label htmlFor="otp">
                                        Kode OTP WhatsApp
                                    </Label>
                                    <div className="relative">
                                        <Icon icon="solar:hashtag-square-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                                        <Input
                                            id="otp"
                                            name="otp"
                                            type="text"
                                            value={data.otp}
                                            onChange={(e) =>
                                                setData('otp', e.target.value)
                                            }
                                            className="pl-10 text-center font-mono text-lg tracking-[0.5em] font-bold"
                                            placeholder="••••••"
                                            maxLength={6}
                                        />
                                    </div>
                                    <InputError message={(errors as any).otp} />
                                    <p className="text-xs text-muted-foreground">
                                        Masukkan kode 6 digit yang dikirim ke nomor Anda.
                                    </p>
                                </motion.div>
                            ) : (
                                /* --- PASSWORD INPUT MODE --- */
                                <motion.div
                                    key="password-field"
                                    initial={{ opacity: 0, height: 0 }}
                                    animate={{ opacity: 1, height: 'auto' }}
                                    exit={{ opacity: 0, height: 0 }}
                                    className="space-y-2"
                                >
                                    <div className="flex items-center justify-between">
                                        <Label htmlFor="password">
                                            Kata Sandi
                                        </Label>
                                        {canResetPassword && (
                                            <TextLink
                                                href={'/forgot-password'}
                                                tabIndex={5}
                                                className="text-xs font-medium text-primary hover:underline"
                                            >
                                                Lupa password?
                                            </TextLink>
                                        )}
                                    </div>
                                    <div className="relative">
                                        <Icon icon="solar:lock-password-bold" className="absolute left-3 top-2.5 size-5 text-muted-foreground" />
                                        <Input
                                            id="password"
                                            name="password"
                                            type="password"
                                            value={data.password}
                                            onChange={(e) =>
                                                setData(
                                                    'password',
                                                    e.target.value,
                                                )
                                            }
                                            className="pl-10"
                                            autoComplete="current-password"
                                        />
                                    </div>
                                    <InputError message={errors.password} />
                                </motion.div>
                            )}
                        </AnimatePresence>

                        {/* --- REMEMBER ME --- */}
                        <div className="flex items-center space-x-2">
                            <Checkbox
                                id="remember"
                                checked={data.remember}
                                onCheckedChange={(checked) =>
                                    setData('remember', !!checked)
                                }
                            />
                            <Label
                                htmlFor="remember"
                                className="text-sm font-medium leading-none cursor-pointer"
                            >
                                Ingat saya di perangkat ini
                            </Label>
                        </div>

                        {/* --- SUBMIT BUTTON --- */}
                        <Button
                            type="submit"
                            className="w-full h-11 text-base shadow-lg shadow-primary/25 transition-all hover:shadow-primary/40 group"
                            disabled={processing}
                        >
                            {processing ? (
                                <Spinner className="mr-2" />
                            ) : (
                                <>
                                    {showOtpInput
                                        ? 'Verifikasi & Masuk'
                                        : 'Masuk Sekarang'}
                                    <Icon icon="solar:arrow-right-linear" className="ml-2 size-5 transition-transform group-hover:translate-x-1" />
                                </>
                            )}
                        </Button>

                        {/* --- SOCIAL LOGIN DIVIDER --- */}
                        <div className="relative my-6">
                            <div className="absolute inset-0 flex items-center">
                                <span className="w-full border-t border-border" />
                            </div>
                            <div className="relative flex justify-center text-xs uppercase">
                                <span className="bg-background px-2 text-muted-foreground">
                                    Atau lanjutkan dengan
                                </span>
                            </div>
                        </div>

                        <Button
                            variant="outline"
                            type="button"
                            className="w-full h-10 gap-2"
                            asChild
                        >
                            {/* Route ke Socialite Login */}
                            <a href="/auth/google">
                                <Icon icon="logos:google-icon" className="size-5" />
                                <span>Google Account</span>
                            </a>
                        </Button>

                        {canRegister && (
                            <div className="mt-4 text-center text-sm">
                                Belum punya akun?{' '}
                                <TextLink href={'/register'} className="font-medium text-primary hover:underline">
                                    Daftar sekarang
                                </TextLink>
                            </div>
                        )}
                    </form>
                </motion.div>
            </div>
        </AuthLayout>
    );
}
