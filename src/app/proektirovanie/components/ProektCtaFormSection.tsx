"use client";

import { useState, useCallback } from "react";
import { useScrollReveal } from "@/hooks/useScrollReveal";
import { ArrowRight, CheckCircle2, Shield, Star, Home } from "lucide-react";
import PhoneInput, { isPhoneValid } from "@/components/ui/PhoneInput";
import { ymGoal } from "@/utils/ym";
import { emitFormSuccess } from "@/utils/webhook";

export default function ProektCtaFormSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.08 });
  const [phone, setPhone] = useState("");
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = useCallback(() => {
    if (!isPhoneValid(phone)) return;
    setSubmitted(true);
    ymGoal("proekt_cta_submit", phone, "proekt_cta_form");
    emitFormSuccess({ form_id: "proekt_cta_form", phone });
  }, [phone]);

  return (
    <section ref={sectionRef} id="cta-form" data-nav-label="Контакты" className="relative py-24 lg:py-32 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6">
        <div className={`grid grid-cols-1 lg:grid-cols-2 gap-12 items-center bg-white/[0.02] border border-white/[0.06] rounded-[2.5rem] p-8 md:p-14 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          {/* Left: Text */}
          <div>
            <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
              Начнём проектирование{" "}
              <span className="text-transparent bg-clip-text bg-linear-to-r from-zinc-400 to-zinc-700">
                вашего дома?
              </span>
            </h2>
            <p className="text-base text-zinc-400 font-light leading-relaxed mb-8">
              Бесплатная консультация с архитектором. Без обязательств. Перезвоним за 2 часа.
            </p>

            {/* Trust indicators */}
            <div className="flex flex-col gap-3">
              <div className="flex items-center gap-2.5 text-sm text-zinc-400">
                <Shield className="w-4 h-4 text-zinc-600 shrink-0" />
                Гарантия 10 лет на проект
              </div>
              <div className="flex items-center gap-2.5 text-sm text-zinc-400">
                <Star className="w-4 h-4 text-zinc-600 shrink-0" />
                350+ реализованных проектов
              </div>
              <div className="flex items-center gap-2.5 text-sm text-zinc-400">
                <Home className="w-4 h-4 text-zinc-600 shrink-0" />
                15 лет опыта проектирования
              </div>
            </div>
          </div>

          {/* Right: Form */}
          <div>
            {submitted ? (
              <div className="text-center py-8">
                <div className="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/10 border border-green-500/30 mb-5">
                  <CheckCircle2 className="w-8 h-8 text-green-400" />
                </div>
                <h3 className="text-xl font-bold text-white mb-2">Заявка отправлена</h3>
                <p className="text-sm text-zinc-400 font-light">
                  Архитектор свяжется с вами в течение 2 часов
                </p>
              </div>
            ) : (
              <div className="bg-white/[0.03] border border-white/[0.08] rounded-2xl p-7 space-y-5">
                <div>
                  <label className="font-label text-[0.55rem] text-zinc-600 tracking-[0.12em] uppercase mb-1.5 block ml-1">
                    Телефон
                  </label>
                  <PhoneInput value={phone} onChange={setPhone} showCheck />
                </div>

                <button
                  onClick={handleSubmit}
                  disabled={!isPhoneValid(phone)}
                  className={`group w-full inline-flex items-center justify-center gap-2.5 px-5 py-4 text-[15px] font-semibold rounded-full transition-all duration-300 ${
                    isPhoneValid(phone)
                      ? "bg-white text-black cursor-pointer hover:bg-zinc-100 hover:scale-[1.01] hover:shadow-[0_0_40px_rgba(255,255,255,0.15)]"
                      : "bg-white/[0.06] text-zinc-600 cursor-not-allowed"
                  }`}
                >
                  Получить консультацию
                  <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                </button>

                <p className="text-zinc-600 text-[0.55rem] text-center font-label">
                  Нажимая кнопку, вы соглашаетесь с{" "}
                  <a href="/privacy/" className="underline hover:text-zinc-400 transition-colors">политикой конфиденциальности</a>
                </p>
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  );
}
