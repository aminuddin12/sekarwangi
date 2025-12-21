import { Icon } from "@iconify/react";
import { motion } from "framer-motion";
import "./artwork.css";

export default function Artwork() {
    return (
        <div className="artwork-bg relative hidden h-full w-full flex-col justify-between p-10 text-white lg:flex">
            {/* Background Animations */}
            <div className="absolute inset-0 z-0">
                <div className="artwork-grid" />

                {/* Animated Orb 1 */}
                <motion.div
                    animate={{
                        scale: [1, 1.2, 1],
                        opacity: [0.3, 0.5, 0.3],
                        rotate: [0, 90, 0],
                    }}
                    transition={{
                        duration: 15,
                        repeat: Infinity,
                        ease: "linear",
                    }}
                    className="absolute -right-20 -top-20 h-[600px] w-[600px] rounded-full bg-primary/20 blur-[120px]"
                />

                {/* Animated Orb 2 */}
                <motion.div
                    animate={{
                        x: [0, 50, 0],
                        y: [0, -50, 0],
                        scale: [1, 1.1, 1],
                    }}
                    transition={{
                        duration: 20,
                        repeat: Infinity,
                        ease: "easeInOut",
                    }}
                    className="absolute bottom-0 left-0 h-[500px] w-[500px] rounded-full bg-blue-600/10 blur-[100px]"
                />
            </div>

            {/* Content Overlay */}
            <div className="relative z-10 flex h-full flex-col justify-between">
                <motion.div
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.2 }}
                    className="flex items-center gap-3"
                >
                    <div className="glass-panel flex size-12 items-center justify-center rounded-xl">
                        <Icon icon="solar:shield-bold" className="size-6 text-white" />
                    </div>
                    <span className="text-xl font-bold tracking-tight">Sekarwangi Enterprise</span>
                </motion.div>

                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.4 }}
                    className="glass-panel max-w-md space-y-4 rounded-2xl p-8"
                >
                    <blockquote className="space-y-2">
                        <p className="text-lg font-medium leading-relaxed">
                            &ldquo;Mengintegrasikan efisiensi, keamanan, dan teknologi modern untuk memajukan organisasi Anda ke level berikutnya.&rdquo;
                        </p>
                    </blockquote>
                    <div className="flex items-center gap-4 pt-4">
                        <div className="flex -space-x-2">
                            {[1, 2, 3].map((i) => (
                                <div key={i} className="size-8 rounded-full border-2 border-zinc-900 bg-zinc-800" />
                            ))}
                        </div>
                        <div className="text-sm text-zinc-400">
                            <span className="text-white font-semibold">1k+</span> Anggota Aktif
                        </div>
                    </div>
                </motion.div>
            </div>
        </div>
    );
}
