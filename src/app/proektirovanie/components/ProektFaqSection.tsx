"use client";

import { useState } from "react";
import { useScrollReveal } from "@/hooks/useScrollReveal";
import { ChevronDown } from "lucide-react";

const faqs = [
  {
    q: "Сколько времени занимает проектирование?",
    a: "Эскизный проект — 5–7 дней. Полный комплект документации — 30–45 дней. Сроки фиксируются в договоре.",
  },
  {
    q: "Можно ли вносить изменения в процессе?",
    a: "Да, на этапе эскизного проекта — бесплатно. На этапе рабочей документации — в рамках согласованных правок.",
  },
  {
    q: "Что входит в рабочую документацию?",
    a: "Архитектурный раздел (АР), конструктивный раздел (КР), инженерные сети (ОВ, ВК, ЭО), спецификации и смета.",
  },
  {
    q: "Работаете ли вы со сложным рельефом?",
    a: "Да, у нас есть опыт проектирования на склонах, у воды и на участках со сложной геологией.",
  },
  {
    q: "Что такое BIM-модель и зачем она нужна?",
    a: "BIM — цифровой двойник дома. Позволяет обнаружить коллизии до начала стройки и сэкономить до 15% бюджета.",
  },
  {
    q: "Можно ли строить по вашему проекту с другим подрядчиком?",
    a: "Да, проект — ваша собственность. Мы выдаём полный комплект, достаточный для любого подрядчика.",
  },
  {
    q: "Какую площадь домов вы проектируете?",
    a: "Любую — от компактных коттеджей 100 м² до резиденций 1000+ м². Для домов от 500 м² — индивидуальные условия.",
  },
];

export default function ProektFaqSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.08 });
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  return (
    <section ref={sectionRef} id="faq" data-nav-label="FAQ" className="relative py-24 lg:py-32 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr] gap-12">
          {/* Left: Sticky header */}
          <div className={`lg:sticky lg:top-32 lg:self-start transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
              Частые{" "}
              <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
                вопросы
              </span>
            </h2>
            <p className="text-sm text-zinc-400 font-light leading-relaxed mb-6">
              Не нашли ответ? Оставьте заявку ниже — архитектор ответит на любой вопрос.
            </p>
            <a
              href="#cta-form"
              className="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-zinc-300 border border-white/[0.08] rounded-full hover:bg-white/[0.04] hover:border-white/[0.16] transition-all duration-300"
            >
              Задать вопрос
            </a>
          </div>

          {/* Right: FAQ items */}
          <div className="flex flex-col gap-2">
            {faqs.map((faq, i) => (
              <div
                key={i}
                className={`border border-white/[0.06] rounded-xl overflow-hidden transition-all duration-500 ${
                  isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"
                }`}
                style={{ transitionDelay: isVisible ? `${200 + i * 60}ms` : "0ms" }}
              >
                <button
                  onClick={() => setOpenIndex(openIndex === i ? null : i)}
                  className="w-full flex items-center justify-between px-6 py-4 text-left cursor-pointer hover:bg-white/[0.02] transition-colors"
                >
                  <span className="text-sm font-medium text-white pr-4">{faq.q}</span>
                  <ChevronDown className={`w-4 h-4 text-zinc-500 shrink-0 transition-transform duration-300 ${openIndex === i ? "rotate-180" : ""}`} />
                </button>
                <div className={`overflow-hidden transition-all duration-300 ${openIndex === i ? "max-h-40 pb-4" : "max-h-0"}`}>
                  <p className="px-6 text-sm text-zinc-400 leading-relaxed">{faq.a}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
