import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';
// import { confirm } from '@/routes/password'; // Remove this failing import
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit = (e: FormEvent) => {
        e.preventDefault();

        // Use manual path instead of generated helper
        post('/user/confirm-password', {
            onFinish: () => reset(),
        });
    };

    return (
        <AuthLayout
            title="Confirm Password"
            description="This is a secure area of the application. Please confirm your password before continuing."
        >
            <Head title="Confirm Password" />

            <div className="w-full max-w-sm mx-auto">
                <form onSubmit={submit} className="space-y-6">
                    <div className="space-y-2">
                        <Label htmlFor="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="block w-full"
                            autoFocus
                            onChange={(e) => setData('password', e.target.value)}
                        />
                        <InputError message={errors.password} />
                    </div>

                    <div className="flex items-center justify-end">
                        <Button className="w-full" disabled={processing}>
                            {processing && <Spinner className="mr-2" />}
                            Confirm
                        </Button>
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
