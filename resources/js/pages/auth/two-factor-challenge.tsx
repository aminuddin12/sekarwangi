import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
import { useForm } from '@inertiajs/react'; // Use standard Inertia useForm
import { FormEvent, useRef, useState } from 'react';

// We will use manual route string to avoid import errors with generated files
// import { login } from '@/routes/two-factor/login';

export default function TwoFactorChallenge() {
    const [recovery, setRecovery] = useState(false);

    // Use manual Inertia form
    const { data, setData, post, processing, errors } = useForm({
        code: '',
        recovery_code: '',
    });

    const recoveryCodeInput = useRef<HTMLInputElement>(null);
    const codeInput = useRef<HTMLInputElement>(null);

    const toggleRecovery = async () => {
        setRecovery(!recovery);

        setTimeout(() => {
            if (recovery) {
                codeInput.current?.focus();
                setData('recovery_code', '');
            } else {
                recoveryCodeInput.current?.focus();
                setData('code', '');
            }
        }, 100);
    };

    const submit = (e: FormEvent) => {
        e.preventDefault();
        // Use hardcoded path to ensure stability
        post('/two-factor-challenge');
    };

    return (
        <AuthLayout
            title="Two-factor Confirmation"
            description="Please confirm access to your account by entering the authentication code provided by your authenticator application."
        >
            <div className="w-full max-w-sm mx-auto">
                <form onSubmit={submit} className="space-y-6">
                    {recovery ? (
                        <div className="space-y-2">
                            <Label htmlFor="recovery_code">Recovery Code</Label>
                            <Input
                                id="recovery_code"
                                ref={recoveryCodeInput}
                                value={data.recovery_code}
                                className="block w-full"
                                autoComplete="one-time-code"
                                onChange={(e) => setData('recovery_code', e.target.value)}
                            />
                            <InputError message={errors.recovery_code} />
                        </div>
                    ) : (
                        <div className="space-y-2">
                            <Label htmlFor="code">Code</Label>
                            <Input
                                id="code"
                                ref={codeInput}
                                value={data.code}
                                className="block w-full"
                                inputMode="numeric"
                                autoComplete="one-time-code"
                                onChange={(e) => setData('code', e.target.value)}
                            />
                            <InputError message={errors.code} />
                        </div>
                    )}

                    <div className="flex items-center justify-end gap-4">
                        <button
                            type="button"
                            className="text-sm text-muted-foreground underline cursor-pointer hover:text-primary"
                            onClick={toggleRecovery}
                        >
                            {recovery
                                ? 'Use an authentication code'
                                : 'Use a recovery code'}
                        </button>

                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner className="mr-2" />}
                            Log in
                        </Button>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
