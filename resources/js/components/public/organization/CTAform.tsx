import { Send } from 'lucide-react';

export default function CTAform() {
    return (
        <div className="bg-gradient-to-br from-neutral-800 to-neutral-900 rounded-xl p-6 text-white shadow-lg shadow-neutral-900/20">
            <h3 className="text-xl font-bold mb-2 text-[#cb9833]">Tetap Terhubung</h3>
            <p className="text-sm text-neutral-300 mb-4">
                Dapatkan informasi terbaru dan penawaran eksklusif langsung ke WhatsApp atau Email Anda.
            </p>
            <form className="space-y-3" onSubmit={(e) => e.preventDefault()}>
                <div>
                    <input
                        type="email"
                        placeholder="Alamat Email Anda"
                        className="w-full px-4 py-2.5 rounded-lg bg-white/10 border border-white/20 focus:border-[#cb9833] focus:ring-1 focus:ring-[#cb9833] placeholder-neutral-400 text-sm transition-all text-white"
                    />
                </div>
                <div>
                    <input
                        type="text"
                        placeholder="Nomor WhatsApp"
                        className="w-full px-4 py-2.5 rounded-lg bg-white/10 border border-white/20 focus:border-[#cb9833] focus:ring-1 focus:ring-[#cb9833] placeholder-neutral-400 text-sm transition-all text-white"
                    />
                </div>
                <button
                    type="submit"
                    className="w-full py-2.5 bg-[#cb9833] hover:bg-[#b0842b] text-white font-semibold rounded-lg flex items-center justify-center gap-2 transition-colors shadow-md shadow-black/20"
                >
                    <Send className="w-4 h-4" />
                    Kirim Informasi
                </button>
            </form>
        </div>
    );
}
