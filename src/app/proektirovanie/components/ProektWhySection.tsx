"use client";

import { useScrollReveal } from "@/hooks/useScrollReveal";
import { Check, X, MapPin, LayoutDashboard, Wallet, Scale, TreePine, Cpu, Sparkles } from "lucide-react";
import type { LucideIcon } from "lucide-react";
import { useEffect, useRef } from "react";

const comparison = [
  { feature: "Адаптация под участок", typical: false, individual: true },
  { feature: "Оптимальные планировки", typical: false, individual: true },
  { feature: "Экономия на стройке до 15%", typical: false, individual: true },
  { feature: "Юридическая чистота", typical: false, individual: true },
  { feature: "BIM-модель в комплекте", typical: false, individual: true },
];

interface Benefit {
  icon: LucideIcon;
  title: string;
  description: string;
}

const benefits: Benefit[] = [
  { icon: MapPin, title: "Под ваш участок", description: "Учитываем рельеф, ориентацию, подъезды, инженерные сети и соседей" },
  { icon: LayoutDashboard, title: "Идеальные планировки", description: "Каждый метр работает на вас — без лишних коридоров и пустот" },
  { icon: Wallet, title: "Экономия на стройке", description: "Точные расчёты = меньше отходов и переделок. Экономия до 15% бюджета" },
  { icon: Scale, title: "Юридическая чистота", description: "Проект полностью соответствует СНиП, пожарным и градостроительным нормам" },
  { icon: TreePine, title: "Интеграция с ландшафтом", description: "Дом органично вписывается в окружение — как будто всегда тут стоял" },
  { icon: Cpu, title: "BIM-модель", description: "Цифровой двойник дома — точная стройка без коллизий и накладок" },
];

export default function ProektWhySection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.08 });
  const spotlightRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = spotlightRef.current;
    if (!el) return;
    const handler = (e: MouseEvent) => {
      const rect = el.getBoundingClientRect();
      el.style.setProperty("--spot-x", `${e.clientX - rect.left}px`);
      el.style.setProperty("--spot-y", `${e.clientY - rect.top}px`);
    };
    el.addEventListener("mousemove", handler);
    return () => el.removeEventListener("mousemove", handler);
  }, []);

  return (
    <section ref={sectionRef} id="why" data-nav-label="Преимущества" className="relative py-24 lg:py-32 overflow-hidden">
      {/* Decorative glow */}
      <div className="absolute top-1/3 -left-40 w-[500px] h-[500px] bg-white/[0.01] rounded-full blur-[100px] pointer-events-none" />

      {/* Noise */}
      <div className="absolute inset-0 z-0 pointer-events-none opacity-[0.04] mix-blend-overlay bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox=%220%200%20200%20200%22%20xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter%20id=%22noiseFilter%22%3E%3CfeTurbulence%20type=%22fractalNoise%22%20baseFrequency=%220.8%22%20numOctaves=%223%22%20stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect%20width=%22100%25%22%20height=%22100%25%22%20filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')]" />

      <div className="relative z-10 max-w-7xl mx-auto px-6">
        {/* Header */}
        <div className={`max-w-2xl mb-16 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-5">
            <Sparkles className="w-3.5 h-3.5 text-zinc-400" />
            <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
              Преимущества индивидуального проекта
            </span>
          </div>
          <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
            Типовой проект — компромисс.{" "}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-zinc-400 to-zinc-600">Всегда.</span>
          </h2>
          <p className="text-base text-zinc-400 font-light leading-relaxed">
            Индивидуальный проект учитывает всё: ваш участок, бюджет, образ жизни и планы на будущее.
          </p>
        </div>

        {/* Split layout: comparison + benefits */}
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.2fr] gap-12 items-start">
          {/* Left: Comparison table */}
          <div className={`transition-all duration-1000 delay-200 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden hover:border-white/[0.1] transition-colors duration-500">
              <div className="grid grid-cols-[1fr_auto_auto] gap-0">
                {/* Header */}
                <div className="px-5 py-4 border-b border-white/[0.06]" />
                <div className="px-5 py-4 border-b border-white/[0.06] text-center">
                  <span className="text-[10px] font-label tracking-[0.1em] uppercase text-zinc-600">Типовой</span>
                </div>
                <div className="px-5 py-4 border-b border-white/[0.06] text-center">
                  <span className="text-[10px] font-label tracking-[0.1em] uppercase text-white">Индивид.</span>
                </div>

                {/* Rows */}
                {comparison.map((row, i) => (
                  <div key={i} className="contents">
                    <div
                      className={`px-5 py-3.5 border-b border-white/[0.04] text-sm text-zinc-300 transition-all duration-500 ${isVisible ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-4"}`}
                      style={{ transitionDelay: isVisible ? `${300 + i * 80}ms` : "0ms" }}
                    >
                      {row.feature}
                    </div>
                    <div className={`px-5 py-3.5 border-b border-white/[0.04] flex items-center justify-center transition-all duration-500 ${isVisible ? "opacity-100" : "opacity-0"}`}
                      style={{ transitionDelay: isVisible ? `${400 + i * 80}ms` : "0ms" }}
                    >
                      <X className="w-4 h-4 text-red-500/60" />
                    </div>
                    <div className={`px-5 py-3.5 border-b border-white/[0.04] flex items-center justify-center transition-all duration-500 ${isVisible ? "opacity-100 scale-100" : "opacity-0 scale-50"}`}
                      style={{ transitionDelay: isVisible ? `${500 + i * 80}ms` : "0ms" }}
                    >
                      <Check className="w-4 h-4 text-green-400" />
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Right: Benefits grid */}
          <div ref={spotlightRef} className="grid grid-cols-1 sm:grid-cols-2 gap-4" style={{ background: "radial-gradient(400px circle at var(--spot-x, 50%) var(--spot-y, 50%), rgba(255,255,255,0.01), transparent 60%)" }}>
            {benefits.map((b, i) => {
              const Icon = b.icon;
              return (
                <div
                  key={i}
                  className={`group p-5 bg-white/[0.02] border border-white/[0.06] rounded-2xl hover:bg-white/[0.04] hover:border-white/[0.12] hover:shadow-[0_0_30px_rgba(255,255,255,0.03)] transition-all duration-500 ${
                    isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
                  }`}
                  style={{ transitionDelay: isVisible ? `${300 + i * 80}ms` : "0ms" }}
                >
                  <div className="w-10 h-10 rounded-xl bg-white/[0.04] border border-white/[0.08] flex items-center justify-center mb-3 group-hover:bg-white/[0.08] group-hover:scale-110 transition-all duration-300">
                    <Icon className="w-5 h-5 text-zinc-400 group-hover:text-zinc-200 transition-colors" />
                  </div>
                  <h3 className="text-sm font-semibold text-white mb-1">{b.title}</h3>
                  <p className="text-xs text-zinc-500 leading-relaxed">{b.description}</p>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
}
