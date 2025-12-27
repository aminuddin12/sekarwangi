import { useState } from 'react';
import { Play } from 'lucide-react';

interface MediaItem {
    type: 'image' | 'video';
    src: string;
    thumbnail?: string;
    alt?: string;
}

interface MediaProps {
    items: MediaItem[];
}

export default function Media({ items }: MediaProps) {
    const [activeIndex, setActiveIndex] = useState(0);

    if (!items || items.length === 0) return null;

    return (
        <div className="space-y-4 mb-8">
            <div className="relative w-full aspect-video bg-neutral-100 dark:bg-neutral-800 rounded-xl overflow-hidden shadow-lg border border-neutral-200 dark:border-neutral-700">
                {items[activeIndex].type === 'image' ? (
                    <img
                        src={items[activeIndex].src}
                        alt={items[activeIndex].alt || 'Media content'}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="relative w-full h-full flex items-center justify-center bg-black">
                        <video
                            src={items[activeIndex].src}
                            controls
                            className="w-full h-full"
                        />
                    </div>
                )}
            </div>

            {items.length > 1 && (
                <div className="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    {items.map((item, index) => (
                        <button
                            key={index}
                            onClick={() => setActiveIndex(index)}
                            className={`relative flex-shrink-0 w-24 h-16 rounded-lg overflow-hidden border-2 transition-all ${
                                activeIndex === index
                                    ? 'border-[#cb9833] ring-2 ring-[#cb9833]/30'
                                    : 'border-transparent opacity-70 hover:opacity-100'
                            }`}
                        >
                            <img
                                src={item.type === 'video' ? (item.thumbnail || item.src) : item.src}
                                alt={`Thumbnail ${index + 1}`}
                                className="w-full h-full object-cover"
                            />
                            {item.type === 'video' && (
                                <div className="absolute inset-0 flex items-center justify-center bg-black/40">
                                    <Play className="w-6 h-6 text-white fill-current" />
                                </div>
                            )}
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}
