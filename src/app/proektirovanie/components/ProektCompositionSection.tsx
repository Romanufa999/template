"use client";

import { useState } from "react";
import { useScrollReveal } from "@/hooks/useScrollReveal";
import {
  PenTool,
  Ruler,
  Cpu,
  Monitor,
  TreePine,
  FileText,
  Check,
  ChevronRight,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import Image from "next/image";
import { S3_BASE } from "@/constants/site";

interface CompositionItem {
  icon: LucideIcon;
  tag: string;
  title: string;
  description: string;
  image: string;
  deliverables: string[];
  pages: string;
}

const items: CompositionItem[] = [
  {
    icon: PenTool,
    tag: "АР",
    title: "Архитектурный раздел",
    description:
      "Планировки этажей, фасады со всех сторон, разрезы, экспликации помещений, ведомость окон и дверей. Основа любого проекта.",
    image: `${S3_BASE}/comp_ar_sec1.webp`,
    deliverables: [
      "Планы этажей с размерами",
      "4 фасада с отметками",
      "Разрезы и узлы",
      "Экспликация помещений",
      "Ведомость окон и дверей",
      "Спецификация отделки фасадов",
    ],
    pages: "25–40 листов",
  },
  {
    icon: Ruler,
    tag: "КР",
    title: "Конструктивный раздел",
    description:
      "Фундамент, стены, перекрытия, стропильная система. Расчёты нагрузок, армирование, узлы и детали для строителей.",
    image: `${S3_BASE}/comp_kr_sec2.webp`,
    deliverables: [
      "Схема фундамента",
      "Расчёт нагрузок",
      "Схемы перекрытий",
      "Стропильная система",
      "Армирование элементов",
      "Детали узлов",
    ],
    pages: "30–50 листов",
  },
  {
    icon: Cpu,
    tag: "ИС",
    title: "Инженерные системы",
    description:
      "Отопление, водоснабжение, канализация, электрика, вентиляция. Схемы разводки, точки подключения, спецификации оборудования.",
    image: `${S3_BASE}/comp_is_sec3.webp`,
    deliverables: [
      "Схема отопления (ОВ)",
      "Водоснабжение и канализация (ВК)",
      "Электрооснащение (ЭО)",
      "Схема вентиляции",
      "Спецификация оборудования",
      "Точки подключения",
    ],
    pages: "35–60 листов",
  },
  {
    icon: Monitor,
    tag: "3D",
    title: "3D-визуализация",
    description:
      "Фотореалистичные рендеры экстерьера с разных ракурсов, при дневном и вечернем освещении. Помогают оценить облик дома до стройки.",
    image: `${S3_BASE}/comp_3d_sec4.webp`,
    deliverables: [
      "6–8 рендеров экстерьера",
      "Дневное и вечернее освещение",
      "Вид с разных ракурсов",
      "Визуализация на участке",
      "Панорама 360° (опц.)",
      "VR-тур (опционально)",
    ],
    pages: "6–12 рендеров",
  },
  {
    icon: FileText,
    tag: "BIM",
    title: "BIM-модель",
    description:
      "Цифровой двойник дома. Автоматическая проверка коллизий между системами. Экономия до 15% бюджета стройки.",
    image: `${S3_BASE}/comp_bim_se5.webp`,
    deliverables: [
      "3D-модель здания",
      "Проверка коллизий",
      "Спецификации из модели",
      "Экспорт IFC / RVT",
      "Ведомость объёмов работ",
      "Координация разделов",
    ],
    pages: "Цифровая модель",
  },
  {
    icon: TreePine,
    tag: "ЛП",
    title: "Ландшафтный проект",
    description:
      "Генплан участка, зонирование, дорожки, площадки, озеленение, освещение. Вписываем дом в ландшафт участка.",
    image: `${S3_BASE}/comp_land_s6.webp`,
    deliverables: [
      "Генеральный план",
      "Дендроплан",
      "Схема дорожек и площадок",
      "План освещения",
      "Зонирование участка",
      "Ведомость посадок",
    ],
    pages: "15–25 листов",
  },
];

export default function ProektCompositionSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });
  const [activeIndex, setActiveIndex] = useState(0);
  const active = items[activeIndex];
  const ActiveIcon = active.icon;

  return (
    <section
      ref={sectionRef}
      id="composition"
      data-nav-label="Состав"
      className="relative py-24 lg:py-32 overflow-hidden"
    >
      {/* Subtle glow */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-white/[0.008] rounded-full blur-[150px] pointer-events-none" />

      <div className="max-w-7xl mx-auto px-6">
        {/* Header */}
        <div
          className={`max-w-2xl mb-14 transition-all duration-1000 ${
            isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"
          }`}
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-5">
            <FileText className="w-3.5 h-3.5 text-zinc-400" />
            <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
              Что вы получите
            </span>
          </div>
          <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
            Состав{" "}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
              проекта
            </span>
          </h2>
          <p className="text-base text-zinc-400 font-light">
            Полный комплект документации для строительства. Каждый раздел — это детально
            проработанный том с чертежами и спецификациями.
          </p>
        </div>

        {/* Tabs + Content */}
        <div className="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6">
          {/* Left: Tab list */}
          <div
            className={`flex flex-row lg:flex-col gap-2 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0 scrollbar-hide transition-all duration-1000 delay-100 ${
              isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
            }`}
          >
            {items.map((item, i) => {
              const Icon = item.icon;
              const isActive = i === activeIndex;
              return (
                <button
                  key={i}
                  onClick={() => setActiveIndex(i)}
                  className={`group flex items-center gap-3 px-4 py-3.5 rounded-xl text-left transition-all duration-300 shrink-0 cursor-pointer ${
                    isActive
                      ? "bg-white/[0.06] border border-white/[0.12] shadow-[0_0_25px_rgba(255,255,255,0.04)]"
                      : "bg-white/[0.01] border border-transparent hover:bg-white/[0.03] hover:border-white/[0.06]"
                  }`}
                >
                  <div
                    className={`w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-colors ${
                      isActive
                        ? "bg-white/[0.1] border border-white/[0.15]"
                        : "bg-white/[0.03] border border-white/[0.06]"
                    }`}
                  >
                    <Icon className={`w-4 h-4 ${isActive ? "text-white" : "text-zinc-500"}`} />
                  </div>
                  <div className="min-w-0">
                    <div className="flex items-center gap-2">
                      <span
                        className={`text-[9px] font-label tracking-[0.15em] uppercase ${
                          isActive ? "text-zinc-300" : "text-zinc-700"
                        }`}
                      >
                        {item.tag}
                      </span>
                      {isActive && <ChevronRight className="w-3 h-3 text-zinc-500" />}
                    </div>
                    <span
                      className={`text-sm font-medium truncate block ${
                        isActive ? "text-white" : "text-zinc-400"
                      }`}
                    >
                      {item.title}
                    </span>
                  </div>
                </button>
              );
            })}
          </div>

          {/* Right: Content panel */}
          <div
            className={`transition-all duration-700 ${
              isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
            }`}
          >
            <div className="bg-white/[0.02] border border-white/[0.06] rounded-2xl overflow-hidden">
              {/* Image */}
              <div className="relative aspect-[16/8] bg-zinc-900 overflow-hidden">
                <Image
                  key={activeIndex}
                  src={active.image}
                  alt={active.title}
                  fill
                  className="object-cover transition-opacity duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />

                {/* Tag overlay */}
                <div className="absolute top-4 left-4 flex items-center gap-2">
                  <div className="px-3 py-1.5 rounded-lg bg-black/60 backdrop-blur-sm border border-white/[0.1]">
                    <span className="text-[10px] font-label tracking-[0.15em] uppercase text-white">
                      {active.tag} — {active.title}
                    </span>
                  </div>
                  <div className="px-3 py-1.5 rounded-lg bg-black/60 backdrop-blur-sm border border-white/[0.1]">
                    <span className="text-[10px] font-label tracking-[0.1em] text-zinc-300">
                      {active.pages}
                    </span>
                  </div>
                </div>
              </div>

              {/* Details */}
              <div className="p-6 lg:p-8">
                <div className="flex items-center gap-3 mb-4">
                  <div className="w-10 h-10 rounded-xl bg-white/[0.04] border border-white/[0.08] flex items-center justify-center">
                    <ActiveIcon className="w-5 h-5 text-zinc-400" />
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold text-white">{active.title}</h3>
                    <span className="text-[10px] font-label tracking-[0.12em] uppercase text-zinc-600">
                      {active.pages}
                    </span>
                  </div>
                </div>

                <p className="text-sm text-zinc-400 leading-relaxed mb-6">
                  {active.description}
                </p>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  {active.deliverables.map((d, j) => (
                    <div
                      key={j}
                      className="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-white/[0.02] border border-white/[0.04]"
                    >
                      <Check className="w-3.5 h-3.5 text-zinc-600 shrink-0" />
                      <span className="text-xs text-zinc-300">{d}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
