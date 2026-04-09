"use client";

import { useScrollReveal } from "@/hooks/useScrollReveal";
import { Check, Star } from "lucide-react";

interface Tier {
  name: string;
  price: string;
  description: string;
  features: string[];
  featured?: boolean;
}

const tiers: Tier[] = [
  {
    name: "Эскизный проект",
    price: "от 500 ₽/м²",
    description: "Планировки и фасады для согласования концепции",
    features: [
      "2–3 варианта планировок",
      "Фасады в 2 ракурсах",
      "Экспликация помещений",
      "Базовая 3D-визуализация",
    ],
  },
  {
    name: "Полный проект",
    price: "от 1 500 ₽/м²",
    description: "Всё для начала строительства без вопросов",
    features: [
      "Архитектурный раздел (АР)",
      "Конструктивный раздел (КР)",
      "Инженерные сети (ИС)",
      "3D-визуализация",
      "BIM-модель",
      "Смета на строительство",
    ],
    featured: true,
  },
  {
    name: "Премиум",
    price: "от 3 000 ₽/м²",
    description: "Максимум: дизайн интерьера, ландшафт, авторский надзор",
    features: [
      "Всё из «Полного проекта»",
      "Дизайн-проект интерьера",
      "Ландшафтный проект",
      "VR-тур по дому",
      "Авторский надзор",
      "Приоритетная поддержка",
    ],
  },
];

export default function ProektPricingSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.08 });

  return (
    <section ref={sectionRef} id="pricing" data-nav-label="Стоимость" className="relative py-24 lg:py-32 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6">
        {/* Header */}
        <div className={`max-w-2xl mb-14 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
            Стоимость{" "}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
              проектирования
            </span>
          </h2>
          <p className="text-base text-zinc-400 font-light">
            Прозрачные цены. Фиксированная стоимость в договоре. Для домов от 500 м² — индивидуальные условия.
          </p>
        </div>

        {/* Pricing cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
          {tiers.map((tier, i) => (
            <div
              key={i}
              className={`relative flex flex-col p-7 rounded-2xl border transition-all duration-500 ${
                tier.featured
                  ? "bg-white/[0.04] border-white/[0.15] shadow-[0_0_40px_rgba(255,255,255,0.05)]"
                  : "bg-white/[0.02] border-white/[0.06] hover:bg-white/[0.04] hover:border-white/[0.12]"
              } ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"}`}
              style={{ transitionDelay: isVisible ? `${200 + i * 100}ms` : "0ms" }}
            >
              {tier.featured && (
                <div className="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-white/[0.1] border border-white/[0.15] flex items-center gap-1.5">
                  <Star className="w-3 h-3 text-white fill-white" />
                  <span className="text-[10px] font-label tracking-[0.1em] uppercase text-white">Популярный</span>
                </div>
              )}

              <h3 className="text-lg font-semibold text-white mb-1">{tier.name}</h3>
              <div className="text-2xl font-bold text-white tracking-[-0.02em] mb-2">{tier.price}</div>
              <p className="text-xs text-zinc-500 mb-6 leading-relaxed">{tier.description}</p>

              <ul className="space-y-2.5 mb-8 grow">
                {tier.features.map((f, j) => (
                  <li key={j} className="flex items-center gap-2.5 text-sm text-zinc-300">
                    <Check className="w-3.5 h-3.5 text-zinc-500 shrink-0" />
                    {f}
                  </li>
                ))}
              </ul>

              <a
                href="#cta-form"
                className={`w-full inline-flex items-center justify-center px-5 py-3 text-sm font-semibold rounded-full transition-all duration-300 ${
                  tier.featured
                    ? "bg-white text-black hover:bg-zinc-200"
                    : "bg-white/[0.06] text-zinc-300 border border-white/[0.08] hover:bg-white/[0.1] hover:text-white"
                }`}
              >
                Заказать проект
              </a>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
