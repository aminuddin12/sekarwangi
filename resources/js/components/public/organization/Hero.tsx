interface HeroProps {
    title: string;
    subtitle?: string;
    backgroundImage?: string;
}

export default function Hero({ title, subtitle, backgroundImage }: HeroProps) {
    return (
        <div className="relative w-full h-[300px] md:h-[400px] overflow-hidden bg-neutral-900 flex items-center justify-center">
            {backgroundImage && (
                <div
                    className="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-40"
                    style={{ backgroundImage: `url(${backgroundImage})` }}
                />
            )}
            <div className="absolute inset-0 bg-gradient-to-t from-neutral-900 via-neutral-900/50 to-transparent" />

            <div className="relative z-10 container mx-auto px-4 text-center">
                <h1 className="text-4xl md:text-5xl font-bold text-white mb-4 font-serif tracking-tight">
                    {title}
                </h1>
                {subtitle && (
                    <p className="text-lg text-neutral-300 max-w-2xl mx-auto font-light">
                        {subtitle}
                    </p>
                )}
                <div className="w-24 h-1 bg-[#cb9833] mx-auto mt-6 rounded-full" />
            </div>
        </div>
    );
}
