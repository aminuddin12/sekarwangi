import { ReactNode } from 'react';

interface MainPageProps {
    children: ReactNode;
}

export default function MainPage({ children }: MainPageProps) {
    return (
        <div className="bg-white dark:bg-neutral-800 rounded-2xl p-6 md:p-10 shadow-sm border border-neutral-100 dark:border-neutral-700 min-h-[500px]">
            <article className="prose prose-neutral dark:prose-invert max-w-none prose-headings:text-neutral-800 dark:prose-headings:text-white prose-headings:font-serif prose-a:text-[#cb9833] hover:prose-a:text-[#b0842b] prose-img:rounded-xl">
                {children}
            </article>
        </div>
    );
}
