"use client";

import { useScrollReveal } from "@/hooks/useScrollReveal";
import { Camera } from "lucide-react";
import Image from "next/image";
import { S3_BASE } from "@/constants/site";

const photos = [
  {
    src: `${S3_BASE}/theme_team01.webp`,
    alt: "Команда архитекторов за работой над проектом дома",
    caption: "Команда архитекторов",
    span: "md:col-span-2 md:row-span-2",
    aspect: "aspect-square",
  },
  {
    src: `${S3_BASE}/theme_site01.webp`,
    alt: "Геодезическая съёмка участка перед проектированием",
    caption: "Обследование участка",
    span: "",
    aspect: "aspect-[4/3]",
  },
  {
    src: `${S3_BASE}/theme_cons01.webp`,
    alt: "Строительство дома по индивидуальному проекту",
    caption: "Строительство по проекту",
    span: "",
    aspect: "aspect-[4/3]",
  },
  {
    src: `${S3_BASE}/theme_intr01.webp`,
    alt: "Интерьер загородного дома со вторым светом",
    caption: "Интерьер по проекту",
    span: "md:col-span-2",
    aspect: "aspect-[21/9]",
  },
];

export default function ProektGallerySection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });

  return (
    <section ref={sectionRef} className="relative py-24 lg:py-32 overflow-hidden">
      <div className="max-w-7xl mx-auto px-6">
        {/* Header */}
        <div className={`max-w-2xl mb-12 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-5">
            <Camera className="w-3.5 h-3.5 text-zinc-400" />
            <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
              За кулисами
            </span>
          </div>
          <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
            От проекта —{" "}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
              до результата
            </span>
          </h2>
          <p className="text-base text-zinc-400 font-light">
            Наша команда сопровождает вас на каждом этапе: от первого замера участка до финального вида дома.
          </p>
        </div>

        {/* Bento gallery */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          {photos.map((photo, i) => (
            <div
              key={i}
              className={`group relative rounded-2xl overflow-hidden bg-zinc-900 border border-white/[0.06] hover:border-white/[0.12] transition-all duration-500 ${photo.span} ${photo.aspect} ${
                isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
              }`}
              style={{ transitionDelay: isVisible ? `${200 + i * 100}ms` : "0ms" }}
            >
              <Image
                src={photo.src}
                alt={photo.alt}
                fill
                className="object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
              <div className="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                <span className="text-sm font-medium text-white">{photo.caption}</span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
