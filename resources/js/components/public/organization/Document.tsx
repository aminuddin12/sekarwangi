/* eslint-disable @typescript-eslint/no-unused-vars */
import { useState } from 'react';
import { FileText, Download, Maximize2, Minimize2 } from 'lucide-react';

interface DocumentProps {
    title: string;
    description?: string;
    fileUrl: string;
    type?: 'pdf' | 'presentation';
}

export default function Document({ title, description, fileUrl, type = 'pdf' }: DocumentProps) {
    const [isMinimized, setIsMinimized] = useState(true);

    return (
        <div className="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden shadow-sm mb-6 transition-all hover:shadow-md">
            <div className="p-4 flex items-start justify-between gap-4 border-b border-neutral-100 dark:border-neutral-700">
                <div className="flex items-start gap-3">
                    <div className="p-2.5 bg-[#cb9833]/10 rounded-lg text-[#cb9833]">
                        <FileText className="w-6 h-6" />
                    </div>
                    <div>
                        <h4 className="font-bold text-neutral-800 dark:text-neutral-100">{title}</h4>
                        {description && <p className="text-sm text-neutral-500 dark:text-neutral-400 mt-1">{description}</p>}
                    </div>
                </div>
                <div className="flex items-center gap-2">
                    <a
                        href={fileUrl}
                        download
                        className="p-2 text-neutral-400 hover:text-[#cb9833] hover:bg-neutral-50 dark:hover:bg-neutral-700 rounded-lg transition-colors"
                        title="Download"
                    >
                        <Download className="w-4 h-4" />
                    </a>
                    <button
                        onClick={() => setIsMinimized(!isMinimized)}
                        className="p-2 text-neutral-400 hover:text-[#cb9833] hover:bg-neutral-50 dark:hover:bg-neutral-700 rounded-lg transition-colors"
                        title={isMinimized ? "Maximize" : "Minimize"}
                    >
                        {isMinimized ? <Maximize2 className="w-4 h-4" /> : <Minimize2 className="w-4 h-4" />}
                    </button>
                </div>
            </div>

            <div className={`transition-all duration-500 ease-in-out ${isMinimized ? 'h-0' : 'h-[600px]'} bg-neutral-100 dark:bg-neutral-900`}>
                {!isMinimized && (
                    <iframe
                        src={fileUrl}
                        className="w-full h-full"
                        title={title}
                    />
                )}
            </div>

            {isMinimized && (
                <div className="px-4 py-2 bg-neutral-50 dark:bg-neutral-900/50 text-xs text-center text-neutral-500 cursor-pointer hover:text-[#cb9833]" onClick={() => setIsMinimized(false)}>
                    Klik untuk melihat dokumen pratinjau
                </div>
            )}
        </div>
    );
}
