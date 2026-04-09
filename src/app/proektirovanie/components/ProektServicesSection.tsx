"use client";

import { useState, useCallback } from "react";
import { useScrollReveal } from "@/hooks/useScrollReveal";
import {
  PenTool,
  Ruler,
  Cpu,
  Palette,
  TreePine,
  Package,
  Check,
  ArrowRight,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import PhoneInput, { isPhoneValid } from "@/components/ui/PhoneInput";
import { ymGoal } from "@/utils/ym";
import { emitFormSuccess } from "@/utils/webhook";

interface Service {
  icon: LucideIcon;
  tag: string;
  title: string;
  description: string;
  deliverables: string[];
}

const services: Service[] = [
  {
    icon: PenTool,
    tag: "АР",
    title: "Архитектурный раздел",
    description: "Планировки, фасады, разрезы, экспликации — полный архитектурный проект",
    deliverables: ["Планировки этажей", "Фасады и разрезы", "3D-визуализация"],
  },
  {
    icon: Ruler,
    tag: "КР",
    title: "Конструктивный раздел",
    description: "Фундамент, стены, перекрытия, кровля — расчёты несущих конструкций",
    deliverables: ["Схемы фундамента", "Расчёт нагрузок", "Узлы и детали"],
  },
  {
    icon: Cpu,
    tag: "ИС",
    title: "Инженерные сети",
    description: "Отопление, водоснабжение, канализация, электрика, вентиляция",
    deliverables: ["Схемы разводки", "Спецификации", "Точки подключения"],
  },
  {
    icon: Palette,
    tag: "ДИ",
    title: "Дизайн интерьера",
    description: "Концепция стиля, планировка мебели, материалы, освещение",
    deliverables: ["Мудборды", "Развёртки стен", "Ведомость отделки"],
  },
  {
    icon: TreePine,
    tag: "ЛП",
    title: "Ландшафтный проект",
    description: "Генплан, зонирование, озеленение, дорожки, освещение участка",
    deliverables: ["Генеральный план", "Дендроплан", "Схема освещения"],
  },
  {
    icon: Package,
    tag: "ПК",
    title: "Полный комплект",
    description: "Все разделы + BIM-модель + авторский надзор. Максимум экономии на стройке",
    deliverables: ["Все разделы", "BIM-модель", "Авторский надзор"],
  },
];

export default function ProektServicesSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });
  const [phone, setPhone] = useState("");
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = useCallback(() => {
    if (!isPhoneValid(phone)) return;
    setSubmitted(true);
    ymGoal("proekt_services_submit", phone, "proekt_services_form");
    emitFormSuccess({ form_id: "proekt_services_form", phone });
  }, [phone]);

  return (
    <section ref={sectionRef} id="services" data-nav-label="Услуги" className="relative py-24 lg:py-32 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6">
        {/* Header */}
        <div className={`max-w-2xl mb-14 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
            Что входит в{" "}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
              проект
            </span>
          </h2>
          <p className="text-base text-zinc-400 font-light">
            Каждый раздел — это полноценный комплект документации. Выбирайте нужные или берите всё сразу.
          </p>
        </div>

        {/* Services grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-16">
          {services.map((s, i) => {
            const Icon = s.icon;
            return (
              <div
                key={i}
                className={`group p-6 bg-white/[0.02] border border-white/[0.06] rounded-2xl hover:bg-white/[0.04] hover:border-white/[0.12] transition-all duration-500 ${
                  isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
                }`}
                style={{ transitionDelay: isVisible ? `${200 + i * 80}ms` : "0ms" }}
              >
                <div className="flex items-center gap-3 mb-4">
                  <div className="w-10 h-10 rounded-xl bg-white/[0.04] border border-white/[0.08] flex items-center justify-center">
                    <Icon className="w-5 h-5 text-zinc-400" />
                  </div>
                  <span className="text-[10px] font-label tracking-[0.15em] uppercase text-zinc-600 bg-white/[0.04] px-2 py-1 rounded-md">
                    {s.tag}
                  </span>
                </div>
                <h3 className="text-base font-semibold text-white mb-2">{s.title}</h3>
                <p className="text-xs text-zinc-500 leading-relaxed mb-4">{s.description}</p>
                <ul className="space-y-1.5">
                  {s.deliverables.map((d, j) => (
                    <li key={j} className="flex items-center gap-2 text-xs text-zinc-400">
                      <Check className="w-3 h-3 text-zinc-600 shrink-0" />
                      {d}
                    </li>
                  ))}
                </ul>
              </div>
            );
          })}
        </div>

        {/* Phone capture form — split layout */}
        <div className={`grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-white/[0.02] border border-white/[0.06] rounded-[2rem] p-8 lg:p-12 transition-all duration-1000 delay-500 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          <div>
            <h3 className="text-xl font-semibold text-white mb-2">Не знаете, что выбрать?</h3>
            <p className="text-sm text-zinc-400 font-light leading-relaxed">
              Оставьте номер — архитектор перезвонит, разберётся в задаче и подскажет оптимальный состав проекта. Бесплатно.
            </p>
          </div>
          <div>
            {submitted ? (
              <div className="text-center py-4">
                <p className="text-lg font-semibold text-white mb-1">Заявка принята</p>
                <p className="text-sm text-zinc-400">Перезвоним за 2 часа</p>
              </div>
            ) : (
              <div className="flex flex-col sm:flex-row gap-3">
                <PhoneInput className="flex-1" value={phone} onChange={setPhone} />
                <button
                  onClick={handleSubmit}
                  disabled={!isPhoneValid(phone)}
                  className={`group inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold rounded-full transition-all duration-300 shrink-0 ${
                    isPhoneValid(phone)
                      ? "bg-white text-black cursor-pointer hover:bg-zinc-200"
                      : "bg-white/[0.06] text-zinc-600 cursor-not-allowed"
                  }`}
                >
                  Подобрать состав
                  <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
