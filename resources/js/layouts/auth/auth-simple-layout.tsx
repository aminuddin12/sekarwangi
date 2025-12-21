import AppLogoIcon from "@/components/app-logo-icon";
import { home } from "@/routes";
import { Link } from "@inertiajs/react";
import { type PropsWithChildren } from "react";
import Artwork from "./artwork";

interface AuthLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: PropsWithChildren<AuthLayoutProps>) {
    return (
        <div className="min-h-svh w-full lg:grid lg:grid-cols-2">
            {/* Left Side - Form Area */}
            <div className="flex flex-col items-center justify-center p-6 md:p-10">
                <div className="w-full max-w-sm space-y-8">
                    {/* Mobile Header (Hanya muncul jika tidak di Login Page yang sudah punya header sendiri) */}
                    <div className="flex flex-col items-center gap-2 text-center lg:hidden">
                        <Link href={home()} className="flex items-center gap-2 font-medium">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                                <AppLogoIcon className="size-6 fill-current text-white" />
                            </div>
                        </Link>
                        {title && <h1 className="text-xl font-bold">{title}</h1>}
                        {description && (
                            <p className="text-sm text-muted-foreground">{description}</p>
                        )}
                    </div>

                    {/* Main Content */}
                    <div className="w-full">
                        {children}
                    </div>
                </div>
            </div>

            {/* Right Side - Artwork (Hidden on Mobile) */}
            <Artwork />
        </div>
    );
}
