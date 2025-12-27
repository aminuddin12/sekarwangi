export default function RightAdSlot() {
    return (
        <div className="bg-neutral-100 dark:bg-neutral-800 rounded-xl border border-dashed border-neutral-300 dark:border-neutral-600 p-6 flex flex-col items-center justify-center text-center min-h-[250px] mb-6">
            <span className="text-xs font-bold text-neutral-400 uppercase tracking-widest mb-2">Advertisement</span>
            <div className="w-full h-full flex items-center justify-center bg-neutral-200 dark:bg-neutral-700/50 rounded-lg">
                <p className="text-neutral-500 text-sm p-4">
                    Hubungi kami untuk penempatan iklan atau kerja sama.
                </p>
            </div>
        </div>
    );
}
