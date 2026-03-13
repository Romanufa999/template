"use client";

import { useScrollReveal } from "@/hooks/useScrollReveal";
import { ArrowRight, PenTool, Layers, Cpu, Eye, Building2, Home } from "lucide-react";
import Image from "next/image";
import { S3_BASE } from "@/constants/site";
import PhoneInput, { isPhoneValid } from "@/components/ui/PhoneInput";
import { useState, useCallback, useEffect, useRef } from "react";
import { ymGoal } from "@/utils/ym";
import { emitFormSuccess } from "@/utils/webhook";

const stats = [
  { value: "350+", label: "проектов" },
  { value: "28", label: "специалистов" },
  { value: "15", label: "лет опыта" },
  { value: "98%", label: "довольных клиентов" },
];

export default function ProektHeroSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });
  const [phone, setPhone] = useState("");
  const [submitted, setSubmitted] = useState(false);
  const spotlightRef = useRef<HTMLDivElement>(null);

  const handleSubmit = useCallback(() => {
    if (!isPhoneValid(phone)) return;
    setSubmitted(true);
    ymGoal("proekt_hero_submit", phone, "proekt_hero_form");
    emitFormSuccess({ form_id: "proekt_hero_form", phone });
  }, [phone]);

  // Spotlight effect
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
    <section ref={sectionRef} className="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
      {/* Background image with parallax feel */}
      <div className="absolute inset-0 pointer-events-none">
        <Image
          src={`${S3_BASE}/2e8jbssy6h.webp`}
          alt=""
          fill
          className="object-cover opacity-[0.08]"
          priority
        />
        <div className="absolute inset-0 bg-gradient-to-b from-black via-black/90 to-black" />
      </div>

      {/* Animated orbs */}
      <div className="absolute top-20 right-20 w-[300px] h-[300px] bg-white/[0.02] rounded-full blur-[80px] pointer-events-none animate-[float-gentle_8s_ease-in-out_infinite]" />
      <div className="absolute bottom-10 left-10 w-[200px] h-[200px] bg-white/[0.015] rounded-full blur-[60px] pointer-events-none animate-[float-gentle_12s_ease-in-out_infinite_2s]" />

      {/* Noise */}
      <div className="absolute inset-0 z-0 pointer-events-none opacity-[0.04] mix-blend-overlay bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox=%220%200%20200%20200%22%20xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter%20id=%22noiseFilter%22%3E%3CfeTurbulence%20type=%22fractalNoise%22%20baseFrequency=%220.8%22%20numOctaves=%223%22%20stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect%20width=%22100%25%22%20height=%22100%25%22%20filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')]" />

      <div ref={spotlightRef} className="relative z-10 max-w-7xl mx-auto px-6" style={{ background: "radial-gradient(600px circle at var(--spot-x, 50%) var(--spot-y, 50%), rgba(255,255,255,0.015), transparent 60%)" }}>
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          {/* Left: Content */}
          <div className={`transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-6">
              <PenTool className="w-3.5 h-3.5 text-zinc-400" />
              <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
                Полный цикл проектирования
              </span>
            </div>

            <h1 className="text-[clamp(2.2rem,5vw,4rem)] font-bold text-white tracking-[-0.03em] leading-[1.05] mb-5">
              Проектирование{" "}
              <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
                загородных домов
              </span>
            </h1>

            <p className="text-lg text-zinc-400 font-light leading-relaxed mb-8 max-w-lg">
              От эскиза до BIM-модели и полного комплекта рабочей документации.
              Каменные и каркасные дома 95–700 м² для Москвы и Подмосковья.
            </p>

            {/* Quick features */}
            <div className="grid grid-cols-2 gap-3 mb-8">
              {[
                { icon: PenTool, text: "Индивидуальный эскиз" },
                { icon: Eye, text: "3D-визуализация" },
                { icon: Layers, text: "Рабочая документация" },
                { icon: Cpu, text: "BIM-модель" },
                { icon: Building2, text: "Каменные дома" },
                { icon: Home, text: "Каркасные дома" },
              ].map((f, i) => {
                const Icon = f.icon;
                return (
                  <div
                    key={i}
                    className={`flex items-center gap-2.5 text-sm text-zinc-300 transition-all duration-500 ${isVisible ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-4"}`}
                    style={{ transitionDelay: isVisible ? `${400 + i * 80}ms` : "0ms" }}
                  >
                    <div className="w-8 h-8 rounded-lg bg-white/[0.04] border border-white/[0.06] flex items-center justify-center shrink-0">
                      <Icon className="w-3.5 h-3.5 text-zinc-400" />
                    </div>
                    {f.text}
                  </div>
                );
              })}
            </div>

            {/* Stats with counter animation */}
            <div className="flex flex-wrap gap-6">
              {stats.map((s, i) => (
                <div
                  key={i}
                  className={`transition-all duration-700 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
                  style={{ transitionDelay: isVisible ? `${800 + i * 100}ms` : "0ms" }}
                >
                  <div className="text-2xl font-bold text-white tracking-[-0.03em]">{s.value}</div>
                  <div className="text-[10px] font-label tracking-[0.1em] uppercase text-zinc-500">{s.label}</div>
                </div>
              ))}
            </div>
          </div>

          {/* Right: Phone form */}
          <div className={`transition-all duration-1000 delay-300 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="relative rounded-[2rem] overflow-hidden group">
              <Image
                src={`${S3_BASE}/yg2bqs2m03.webp`}
                alt="Современный загородный дом с панорамными окнами"
                width={600}
                height={400}
                className="w-full aspect-[3/2] object-cover rounded-[2rem] group-hover:scale-[1.02] transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent rounded-[2rem]" />

              {/* Floating badges */}
              <div className="absolute top-4 left-4 flex gap-2">
                <span className="px-3 py-1.5 rounded-lg bg-black/50 backdrop-blur-md border border-white/[0.1] text-[10px] text-white font-label tracking-wider">
                  30+ ДОМОВ В ПОРТФОЛИО
                </span>
              </div>

              {/* Form overlay */}
              <div className="absolute bottom-0 left-0 right-0 p-6">
                {submitted ? (
                  <div className="text-center py-4">
                    <p className="text-lg font-semibold text-white mb-1">Заявка отправлена</p>
                    <p className="text-sm text-zinc-400">Архитектор перезвонит за 2 часа</p>
                  </div>
                ) : (
                  <div className="space-y-3">
                    <p className="text-sm text-zinc-300 font-medium">Бесплатная консультация архитектора</p>
                    <PhoneInput value={phone} onChange={setPhone} />
                    <button
                      onClick={handleSubmit}
                      disabled={!isPhoneValid(phone)}
                      className={`group/btn w-full inline-flex items-center justify-center gap-2.5 px-5 py-3.5 text-sm font-semibold rounded-full transition-all duration-300 ${
                        isPhoneValid(phone)
                          ? "bg-white text-black cursor-pointer hover:bg-zinc-100 hover:shadow-[0_0_30px_rgba(255,255,255,0.15)]"
                          : "bg-white/[0.06] text-zinc-600 cursor-not-allowed"
                      }`}
                    >
                      Получить консультацию
                      <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" />
                    </button>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
