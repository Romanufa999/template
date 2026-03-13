"use client";

import { useScrollReveal } from "@/hooks/useScrollReveal";
import { MessageSquare, PenTool, Monitor, FileText, Cpu, Send, Clock } from "lucide-react";
import type { LucideIcon } from "lucide-react";
import Image from "next/image";
import { S3_BASE } from "@/constants/site";
import { useState } from "react";

interface Step {
  icon: LucideIcon;
  title: string;
  duration: string;
  description: string;
  result: string;
}

const steps: Step[] = [
  {
    icon: MessageSquare,
    title: "Бриф и знакомство",
    duration: "1–2 дня",
    description: "Обсуждаем ваши пожелания, изучаем участок, согласовываем бюджет и сроки",
    result: "Техническое задание",
  },
  {
    icon: PenTool,
    title: "Эскизный проект",
    duration: "5–7 дней",
    description: "2–3 варианта планировок и фасадов. Выбираем лучший, дорабатываем",
    result: "Эскизы и планировки",
  },
  {
    icon: Monitor,
    title: "3D-визуализация",
    duration: "5–7 дней",
    description: "Фотореалистичные рендеры дома и интерьеров. VR-тур по запросу",
    result: "Рендеры и VR-тур",
  },
  {
    icon: FileText,
    title: "Рабочая документация",
    duration: "10–14 дней",
    description: "Полный комплект чертежей: АР, КР, инженерные сети, спецификации",
    result: "Чертежи и сметы",
  },
  {
    icon: Cpu,
    title: "BIM-модель",
    duration: "3–5 дней",
    description: "Цифровой двойник дома. Автоматическая проверка коллизий",
    result: "BIM-файл (IFC/RVT)",
  },
  {
    icon: Send,
    title: "Передача проекта",
    duration: "1 день",
    description: "Печатный и электронный комплект. Консультации на весь период стройки",
    result: "Готовый проект",
  },
];

export default function ProektProcessSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });
  const [hoveredStep, setHoveredStep] = useState<number | null>(null);

  return (
    <section ref={sectionRef} id="process" data-nav-label="Процесс" className="relative py-24 lg:py-32 overflow-hidden">
      {/* Noise */}
      <div className="absolute inset-0 z-0 pointer-events-none opacity-[0.04] mix-blend-overlay bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20viewBox=%220%200%20200%20200%22%20xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter%20id=%22noiseFilter%22%3E%3CfeTurbulence%20type=%22fractalNoise%22%20baseFrequency=%220.8%22%20numOctaves=%223%22%20stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect%20width=%22100%25%22%20height=%22100%25%22%20filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')]" />

      <div className="relative z-10 max-w-7xl mx-auto px-6">
        {/* Split header with image */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16 items-center">
          <div className={`transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-5">
              <Clock className="w-3.5 h-3.5 text-zinc-400" />
              <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
                Этапы работы
              </span>
            </div>
            <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
              Как мы{" "}
              <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
                работаем
              </span>
            </h2>
            <p className="text-base text-zinc-400 font-light leading-relaxed">
              6 этапов от первой встречи до полного комплекта документации. Средний срок — 30–45 дней.
            </p>
            <div className="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08]">
              <span className="text-xs text-zinc-300 font-medium">Общий срок: 30–45 дней</span>
            </div>
          </div>
          <div className={`transition-all duration-1000 delay-300 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="relative rounded-2xl overflow-hidden aspect-[16/9] bg-zinc-900 border border-white/[0.06] group">
              <Image
                src={`${S3_BASE}/lcka0pt39o.webp`}
                alt="3D-визуализация архитектурного проекта загородного дома"
                fill
                className="object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent" />
            </div>
          </div>
        </div>

        {/* Steps */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {steps.map((step, i) => {
            const Icon = step.icon;
            return (
              <div
                key={i}
                onMouseEnter={() => setHoveredStep(i)}
                onMouseLeave={() => setHoveredStep(null)}
                className={`relative p-6 bg-white/[0.02] border border-white/[0.06] rounded-2xl hover:bg-white/[0.04] hover:border-white/[0.12] hover:shadow-[0_0_30px_rgba(255,255,255,0.03)] transition-all duration-500 ${
                  isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
                }`}
                style={{
                  transitionDelay: isVisible ? `${400 + i * 100}ms` : "0ms",
                  transform: hoveredStep === i ? "translateY(-4px)" : undefined,
                }}
              >
                {/* Step number */}
                <div className="absolute top-4 right-4 text-[10px] font-label tracking-[0.1em] text-zinc-700">
                  {String(i + 1).padStart(2, "0")}
                </div>

                {/* Progress bar */}
                <div className="absolute top-0 left-0 right-0 h-px">
                  <div
                    className={`h-full bg-gradient-to-r from-white/[0.15] to-transparent transition-all duration-1000 ${
                      isVisible ? "w-full" : "w-0"
                    }`}
                    style={{ transitionDelay: isVisible ? `${600 + i * 150}ms` : "0ms" }}
                  />
                </div>

                <div className={`w-10 h-10 rounded-xl bg-white/[0.04] border border-white/[0.08] flex items-center justify-center mb-4 transition-all duration-300 ${hoveredStep === i ? "bg-white/[0.08] scale-110" : ""}`}>
                  <Icon className={`w-5 h-5 transition-colors duration-300 ${hoveredStep === i ? "text-zinc-200" : "text-zinc-400"}`} />
                </div>

                <h3 className="text-base font-semibold text-white mb-1">{step.title}</h3>
                <span className="text-[10px] font-label tracking-[0.1em] uppercase text-zinc-600 mb-3 block">
                  {step.duration}
                </span>
                <p className="text-xs text-zinc-500 leading-relaxed mb-4">{step.description}</p>

                <div className="px-3 py-1.5 rounded-lg bg-white/[0.04] border border-white/[0.06] inline-block">
                  <span className="text-[10px] text-zinc-400 font-medium">&rarr; {step.result}</span>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
