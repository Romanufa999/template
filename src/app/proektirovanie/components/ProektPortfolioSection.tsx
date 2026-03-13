"use client";

import { useState, useCallback, useMemo } from "react";
import { useScrollReveal } from "@/hooks/useScrollReveal";
import {
  Expand,
  X,
  ChevronLeft,
  ChevronRight,
  MapPin,
  ArrowRight,
  SlidersHorizontal,
  Home,
  Building2,
  Layers,
  ChevronDown,
} from "lucide-react";
import Image from "next/image";
import { S3_BASE } from "@/constants/site";
import PhoneInput, { isPhoneValid } from "@/components/ui/PhoneInput";
import { ymGoal } from "@/utils/ym";
import { emitFormSuccess } from "@/utils/webhook";

interface Project {
  src: string;
  title: string;
  area: string;
  areaNum: number;
  style: string;
  type: "каменный" | "каркасный";
  material: string;
  floors: string;
  location: string;
}

const projects: Project[] = [
  // --- Каменные дома ---
  {
    src: `${S3_BASE}/pr01kamen01.webp`,
    title: "Коттедж Берёзка",
    area: "150 м²",
    areaNum: 150,
    style: "Классика",
    type: "каменный",
    material: "Кирпич",
    floors: "2 этажа",
    location: "Новорижское ш.",
  },
  {
    src: `${S3_BASE}/pr03kamen02.webp`,
    title: "Дом Престиж",
    area: "200 м²",
    areaNum: 200,
    style: "Современный",
    type: "каменный",
    material: "Газобетон",
    floors: "2 этажа",
    location: "Минское ш.",
  },
  {
    src: `${S3_BASE}/pr05kamen03.webp`,
    title: "Усадьба Красная",
    area: "250 м²",
    areaNum: 250,
    style: "Классика",
    type: "каменный",
    material: "Кирпич",
    floors: "2 этажа",
    location: "Рублёво-Успенское ш.",
  },
  {
    src: `${S3_BASE}/pr07kamen04.webp`,
    title: "Дом Комфорт",
    area: "170 м²",
    areaNum: 170,
    style: "Модерн",
    type: "каменный",
    material: "Газобетон",
    floors: "2 этажа",
    location: "Калужское ш.",
  },
  {
    src: `${S3_BASE}/pr09kamen05.webp`,
    title: "Вилла Европа",
    area: "220 м²",
    areaNum: 220,
    style: "Европейский",
    type: "каменный",
    material: "Керамический блок",
    floors: "2 этажа",
    location: "Киевское ш.",
  },
  {
    src: `${S3_BASE}/pr11kamen06.webp`,
    title: "Дом Панорама",
    area: "300 м²",
    areaNum: 300,
    style: "Хай-тек",
    type: "каменный",
    material: "Газобетон",
    floors: "2 этажа",
    location: "Новорижское ш.",
  },
  {
    src: `${S3_BASE}/pr13kamen07.webp`,
    title: "Коттедж Тёплый",
    area: "190 м²",
    areaNum: 190,
    style: "Современный",
    type: "каменный",
    material: "Тёплая керамика",
    floors: "2 этажа",
    location: "Можайское ш.",
  },
  {
    src: `${S3_BASE}/pr15kamen08.webp`,
    title: "Усадьба Дворянская",
    area: "280 м²",
    areaNum: 280,
    style: "Классика",
    type: "каменный",
    material: "Кирпич",
    floors: "2 этажа",
    location: "Рублёвское ш.",
  },
  {
    src: `${S3_BASE}/pr17kamen09.webp`,
    title: "Дом L-формы",
    area: "240 м²",
    areaNum: 240,
    style: "Минимализм",
    type: "каменный",
    material: "Газобетон",
    floors: "2 этажа",
    location: "Ильинское ш.",
  },
  {
    src: `${S3_BASE}/pr19kamen10.webp`,
    title: "Резиденция Бетон",
    area: "260 м²",
    areaNum: 260,
    style: "Хай-тек",
    type: "каменный",
    material: "Монолит",
    floors: "2 этажа",
    location: "Новорижское ш.",
  },
  {
    src: `${S3_BASE}/pr21kamen11.webp`,
    title: "Дом Уютный",
    area: "140 м²",
    areaNum: 140,
    style: "Современный",
    type: "каменный",
    material: "Газобетон",
    floors: "1 этаж",
    location: "Щёлковское ш.",
  },
  {
    src: `${S3_BASE}/pr23kamen12.webp`,
    title: "Баварский Дом",
    area: "165 м²",
    areaNum: 165,
    style: "Европейский",
    type: "каменный",
    material: "Кирпич",
    floors: "2 этажа",
    location: "Дмитровское ш.",
  },
  {
    src: `${S3_BASE}/pr25kamen13.webp`,
    title: "Куб Премиум",
    area: "300 м²",
    areaNum: 300,
    style: "Минимализм",
    type: "каменный",
    material: "Газобетон",
    floors: "2 этажа",
    location: "Рублёво-Успенское ш.",
  },
  {
    src: `${S3_BASE}/pr27kamen14.webp`,
    title: "Неоклассика",
    area: "230 м²",
    areaNum: 230,
    style: "Неоклассика",
    type: "каменный",
    material: "Кирпич",
    floors: "2 этажа",
    location: "Истринский р-н",
  },
  {
    src: `${S3_BASE}/pr29kamen15.webp`,
    title: "Ранчо Солнечный",
    area: "180 м²",
    areaNum: 180,
    style: "Современный",
    type: "каменный",
    material: "Газобетон",
    floors: "1 этаж",
    location: "Калужское ш.",
  },
  // --- Каркасные дома ---
  {
    src: `${S3_BASE}/pr02karkas1.webp`,
    title: "Сканди Хаус",
    area: "120 м²",
    areaNum: 120,
    style: "Скандинавский",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "1 этаж + мансарда",
    location: "Ярославское ш.",
  },
  {
    src: `${S3_BASE}/pr04karkas2.webp`,
    title: "Барнхаус Тёмный",
    area: "180 м²",
    areaNum: 180,
    style: "Барнхаус",
    type: "каркасный",
    material: "Каркас металл",
    floors: "2 этажа",
    location: "Дмитровское ш.",
  },
  {
    src: `${S3_BASE}/pr06karkas3.webp`,
    title: "Эко-Куб",
    area: "130 м²",
    areaNum: 130,
    style: "Минимализм",
    type: "каркасный",
    material: "СИП-панели",
    floors: "2 этажа",
    location: "Ленинградское ш.",
  },
  {
    src: `${S3_BASE}/pr08karkas4.webp`,
    title: "Шалаш Лесной",
    area: "100 м²",
    areaNum: 100,
    style: "А-фрейм",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "1,5 этажа",
    location: "Ярославское ш.",
  },
  {
    src: `${S3_BASE}/pr10karkas5.webp`,
    title: "Дом со Вторым Светом",
    area: "160 м²",
    areaNum: 160,
    style: "Современный",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "2 этажа",
    location: "Егорьевское ш.",
  },
  {
    src: `${S3_BASE}/pr12karkas6.webp`,
    title: "Дача Уют",
    area: "110 м²",
    areaNum: 110,
    style: "Классика",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "1 этаж",
    location: "Горьковское ш.",
  },
  {
    src: `${S3_BASE}/pr14karkas7.webp`,
    title: "Лофт Хаус",
    area: "200 м²",
    areaNum: 200,
    style: "Лофт",
    type: "каркасный",
    material: "Каркас металл",
    floors: "2 этажа",
    location: "Новорижское ш.",
  },
  {
    src: `${S3_BASE}/pr16karkas8.webp`,
    title: "Мини Эко",
    area: "95 м²",
    areaNum: 95,
    style: "Эко",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "1 этаж",
    location: "Симферопольское ш.",
  },
  {
    src: `${S3_BASE}/pr18karkas9.webp`,
    title: "Шале Альпийское",
    area: "210 м²",
    areaNum: 210,
    style: "Шале",
    type: "каркасный",
    material: "Каркас дерево + камень",
    floors: "2 этажа",
    location: "Дмитровское ш.",
  },
  {
    src: `${S3_BASE}/pr20karkas0.webp`,
    title: "Канадский Дом",
    area: "155 м²",
    areaNum: 155,
    style: "Классика",
    type: "каркасный",
    material: "Каркас канадский",
    floors: "2 этажа",
    location: "Каширское ш.",
  },
  {
    src: `${S3_BASE}/pr22karkas1a.webp`,
    title: "Барнхаус Контраст",
    area: "175 м²",
    areaNum: 175,
    style: "Барнхаус",
    type: "каркасный",
    material: "Каркас металл",
    floors: "2 этажа",
    location: "Минское ш.",
  },
  {
    src: `${S3_BASE}/pr24karkas2a.webp`,
    title: "Зелёный Дом",
    area: "145 м²",
    areaNum: 145,
    style: "Скандинавский",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "2 этажа",
    location: "Ленинградское ш.",
  },
  {
    src: `${S3_BASE}/pr26karkas3a.webp`,
    title: "Финский Коттедж",
    area: "125 м²",
    areaNum: 125,
    style: "Финский",
    type: "каркасный",
    material: "Каркас дерево",
    floors: "1 этаж + мансарда",
    location: "Волоколамское ш.",
  },
  {
    src: `${S3_BASE}/pr28karkas4a.webp`,
    title: "Дуплекс Модерн",
    area: "270 м²",
    areaNum: 270,
    style: "Модерн",
    type: "каркасный",
    material: "Каркас + фиброцемент",
    floors: "2 этажа",
    location: "Новорижское ш.",
  },
  // --- Особый проект: Райт 700 м² ---
  {
    src: `${S3_BASE}/pr30wright1.webp`,
    title: "Резиденция Райт",
    area: "700 м²",
    areaNum: 700,
    style: "Райт",
    type: "каменный",
    material: "Камень + дерево",
    floors: "2 этажа",
    location: "Рублёво-Архангельское",
  },
];

type FilterType = "all" | "каменный" | "каркасный";
type AreaFilter = "all" | "small" | "medium" | "large";

const ITEMS_PER_PAGE = 9;

export default function ProektPortfolioSection() {
  const { ref: sectionRef, isVisible } = useScrollReveal<HTMLElement>({ threshold: 0.05 });
  const [lightbox, setLightbox] = useState<number | null>(null);
  const [phone, setPhone] = useState("");
  const [submitted, setSubmitted] = useState(false);
  const [typeFilter, setTypeFilter] = useState<FilterType>("all");
  const [areaFilter, setAreaFilter] = useState<AreaFilter>("all");
  const [visibleCount, setVisibleCount] = useState(ITEMS_PER_PAGE);
  const [hoveredCard, setHoveredCard] = useState<number | null>(null);

  const handleSubmit = useCallback(() => {
    if (!isPhoneValid(phone)) return;
    setSubmitted(true);
    ymGoal("proekt_portfolio_submit", phone, "proekt_portfolio_form");
    emitFormSuccess({ form_id: "proekt_portfolio_form", phone });
  }, [phone]);

  const filtered = useMemo(() => {
    return projects.filter((p) => {
      if (typeFilter !== "all" && p.type !== typeFilter) return false;
      if (areaFilter === "small" && p.areaNum > 150) return false;
      if (areaFilter === "medium" && (p.areaNum <= 150 || p.areaNum > 250)) return false;
      if (areaFilter === "large" && p.areaNum <= 250) return false;
      return true;
    });
  }, [typeFilter, areaFilter]);

  const visible = filtered.slice(0, visibleCount);
  const hasMore = visibleCount < filtered.length;

  const handleFilterChange = (type: FilterType) => {
    setTypeFilter(type);
    setVisibleCount(ITEMS_PER_PAGE);
  };

  const handleAreaChange = (area: AreaFilter) => {
    setAreaFilter(area);
    setVisibleCount(ITEMS_PER_PAGE);
  };

  const lightboxProject = lightbox !== null ? visible[lightbox] : null;

  return (
    <>
      <section ref={sectionRef} id="portfolio" data-nav-label="Проекты" className="relative py-24 lg:py-32 overflow-hidden">
        {/* Background decorative elements */}
        <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-white/[0.01] rounded-full blur-[120px] pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-white/[0.015] rounded-full blur-[100px] pointer-events-none" />

        <div className="max-w-7xl mx-auto px-6">
          {/* Header */}
          <div className={`max-w-3xl mb-10 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/[0.04] border border-white/[0.08] mb-5">
              <Home className="w-3.5 h-3.5 text-zinc-400" />
              <span className="text-[11px] text-zinc-300 font-label tracking-[0.15em] uppercase">
                Портфолио проектов
              </span>
            </div>
            <h2 className="text-[clamp(2rem,4vw,3rem)] font-bold text-white tracking-[-0.03em] leading-tight mb-4">
              Реализованные{" "}
              <span className="text-transparent bg-clip-text bg-linear-to-r from-white via-zinc-300 to-zinc-600">
                проекты
              </span>
            </h2>
            <p className="text-base text-zinc-400 font-light">
              {filtered.length} проектов загородных домов — от компактных коттеджей 95 м² до элитных резиденций 700 м².
              Каменные и каркасные дома для Подмосковья.
            </p>
          </div>

          {/* Filters */}
          <div className={`flex flex-wrap gap-3 mb-10 transition-all duration-1000 delay-100 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"}`}>
            <div className="flex items-center gap-1.5 mr-2">
              <SlidersHorizontal className="w-3.5 h-3.5 text-zinc-500" />
              <span className="text-[10px] font-label tracking-[0.12em] uppercase text-zinc-600">Фильтр:</span>
            </div>

            {/* Type filters */}
            <div className="flex gap-1.5">
              {([
                { key: "all" as FilterType, label: "Все", icon: Layers },
                { key: "каменный" as FilterType, label: "Каменные", icon: Building2 },
                { key: "каркасный" as FilterType, label: "Каркасные", icon: Home },
              ]).map((f) => {
                const Icon = f.icon;
                return (
                  <button
                    key={f.key}
                    onClick={() => handleFilterChange(f.key)}
                    className={`inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-medium transition-all duration-300 cursor-pointer ${
                      typeFilter === f.key
                        ? "bg-white text-black shadow-[0_0_20px_rgba(255,255,255,0.1)]"
                        : "bg-white/[0.04] text-zinc-400 border border-white/[0.06] hover:bg-white/[0.08] hover:text-zinc-200"
                    }`}
                  >
                    <Icon className="w-3 h-3" />
                    {f.label}
                  </button>
                );
              })}
            </div>

            <div className="w-px h-6 bg-white/[0.08] self-center mx-1" />

            {/* Area filters */}
            <div className="flex gap-1.5">
              {([
                { key: "all" as AreaFilter, label: "Любая площадь" },
                { key: "small" as AreaFilter, label: "до 150 м²" },
                { key: "medium" as AreaFilter, label: "150–250 м²" },
                { key: "large" as AreaFilter, label: "от 250 м²" },
              ]).map((f) => (
                <button
                  key={f.key}
                  onClick={() => handleAreaChange(f.key)}
                  className={`px-3.5 py-2 rounded-full text-xs font-medium transition-all duration-300 cursor-pointer ${
                    areaFilter === f.key
                      ? "bg-white/[0.12] text-white border border-white/[0.15]"
                      : "bg-white/[0.03] text-zinc-500 border border-white/[0.04] hover:bg-white/[0.06] hover:text-zinc-300"
                  }`}
                >
                  {f.label}
                </button>
              ))}
            </div>
          </div>

          {/* Counter */}
          <div className={`mb-6 transition-all duration-500 ${isVisible ? "opacity-100" : "opacity-0"}`}>
            <span className="text-xs text-zinc-600 font-label">
              Показано {visible.length} из {filtered.length} проектов
            </span>
          </div>

          {/* Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            {visible.map((p, i) => (
              <div
                key={`${p.title}-${i}`}
                onClick={() => setLightbox(i)}
                onMouseEnter={() => setHoveredCard(i)}
                onMouseLeave={() => setHoveredCard(null)}
                className={`group relative rounded-2xl overflow-hidden cursor-pointer bg-zinc-900 border border-white/[0.06] aspect-[4/3] hover:border-white/[0.15] transition-all duration-500 ${
                  isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"
                }`}
                style={{
                  transitionDelay: isVisible ? `${150 + (i % ITEMS_PER_PAGE) * 60}ms` : "0ms",
                  transform: hoveredCard === i ? "scale(1.02)" : undefined,
                }}
              >
                <Image src={p.src} alt={p.title} fill className="object-cover group-hover:scale-110 transition-transform duration-700" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent group-hover:from-black/90 transition-colors duration-500" />

                {/* Badges */}
                <div className="absolute top-4 left-4 flex flex-wrap gap-1.5">
                  <span className={`px-2 py-1 rounded-md backdrop-blur-sm border text-[10px] font-label ${
                    p.type === "каменный"
                      ? "bg-amber-900/40 border-amber-500/20 text-amber-200"
                      : "bg-emerald-900/40 border-emerald-500/20 text-emerald-200"
                  }`}>
                    {p.type === "каменный" ? "Каменный" : "Каркасный"}
                  </span>
                  <span className="px-2 py-1 rounded-md bg-black/60 backdrop-blur-sm border border-white/[0.1] text-[10px] text-white font-label">
                    {p.area}
                  </span>
                  <span className="px-2 py-1 rounded-md bg-black/60 backdrop-blur-sm border border-white/[0.1] text-[10px] text-zinc-300 font-label">
                    {p.style}
                  </span>
                </div>

                {/* Expand icon */}
                <div className="absolute top-4 right-4 w-8 h-8 rounded-lg bg-black/40 backdrop-blur-sm border border-white/[0.1] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <Expand className="w-3.5 h-3.5 text-white" />
                </div>

                {/* Info */}
                <div className="absolute bottom-4 left-4 right-4">
                  <h3 className="text-base font-semibold text-white mb-1.5">{p.title}</h3>
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-1 text-[10px] text-zinc-400">
                      <MapPin className="w-3 h-3" />
                      {p.location}
                    </div>
                    <div className="text-[10px] text-zinc-500 font-label">
                      {p.material} · {p.floors}
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Load more */}
          {hasMore && (
            <div className="flex justify-center mb-16">
              <button
                onClick={() => setVisibleCount((c) => c + ITEMS_PER_PAGE)}
                className="group inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-white/[0.04] border border-white/[0.08] text-sm font-medium text-zinc-300 hover:bg-white/[0.08] hover:border-white/[0.15] hover:text-white transition-all duration-300 cursor-pointer"
              >
                <ChevronDown className="w-4 h-4 transition-transform duration-300 group-hover:translate-y-0.5" />
                Показать ещё {Math.min(ITEMS_PER_PAGE, filtered.length - visibleCount)} проектов
              </button>
            </div>
          )}

          {/* Stats bar */}
          <div className={`grid grid-cols-2 md:grid-cols-4 gap-4 mb-16 transition-all duration-1000 delay-300 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-6"}`}>
            {[
              { value: "350+", label: "проектов реализовано" },
              { value: "95–700", label: "м² — диапазон площадей" },
              { value: "15+", label: "направлений Подмосковья" },
              { value: "50/50", label: "каменные / каркасные" },
            ].map((s, i) => (
              <div key={i} className="text-center p-5 bg-white/[0.02] border border-white/[0.06] rounded-2xl">
                <div className="text-xl font-bold text-white tracking-[-0.02em] mb-1">{s.value}</div>
                <div className="text-[10px] font-label tracking-[0.1em] uppercase text-zinc-600">{s.label}</div>
              </div>
            ))}
          </div>

          {/* Phone capture form */}
          <div className={`grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-white/[0.02] border border-white/[0.06] rounded-[2rem] p-8 lg:p-12 transition-all duration-1000 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}>
            <div>
              <h3 className="text-xl font-semibold text-white mb-2">Хотите похожий проект?</h3>
              <p className="text-sm text-zinc-400 font-light leading-relaxed">
                Оставьте номер — покажем портфолио под ваш стиль и рассчитаем стоимость проектирования.
              </p>
            </div>
            <div>
              {submitted ? (
                <div className="text-center py-4">
                  <p className="text-lg font-semibold text-white mb-1">Заявка принята</p>
                  <p className="text-sm text-zinc-400">Перезвоним с подборкой проектов</p>
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
                    Получить подборку
                    <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" />
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Lightbox */}
      {lightbox !== null && lightboxProject && (
        <div className="fixed inset-0 z-[200] bg-black/95 backdrop-blur-xl flex items-center justify-center" onClick={() => setLightbox(null)}>
          <button onClick={() => setLightbox(null)} className="absolute top-6 right-6 w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors z-10 cursor-pointer">
            <X className="w-5 h-5" />
          </button>
          <button
            onClick={(e) => { e.stopPropagation(); setLightbox((lightbox - 1 + visible.length) % visible.length); }}
            className="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors z-10 cursor-pointer"
          >
            <ChevronLeft className="w-5 h-5" />
          </button>
          <button
            onClick={(e) => { e.stopPropagation(); setLightbox((lightbox + 1) % visible.length); }}
            className="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors z-10 cursor-pointer"
          >
            <ChevronRight className="w-5 h-5" />
          </button>
          <div className="relative max-w-5xl w-full mx-4 aspect-[4/3]" onClick={(e) => e.stopPropagation()}>
            <Image src={lightboxProject.src} alt={lightboxProject.title} fill className="object-contain" />
          </div>
          <div className="absolute bottom-8 left-1/2 -translate-x-1/2 text-center">
            <h3 className="text-lg font-semibold text-white mb-1">{lightboxProject.title}</h3>
            <p className="text-sm text-zinc-400">
              {lightboxProject.area} · {lightboxProject.type} · {lightboxProject.material} · {lightboxProject.location}
            </p>
            <div className="flex justify-center gap-2 mt-3">
              <span className={`px-2.5 py-1 rounded-md text-[10px] font-label ${
                lightboxProject.type === "каменный"
                  ? "bg-amber-900/40 border border-amber-500/20 text-amber-200"
                  : "bg-emerald-900/40 border border-emerald-500/20 text-emerald-200"
              }`}>
                {lightboxProject.type}
              </span>
              <span className="px-2.5 py-1 rounded-md bg-white/[0.08] border border-white/[0.1] text-[10px] text-zinc-300 font-label">
                {lightboxProject.style}
              </span>
              <span className="px-2.5 py-1 rounded-md bg-white/[0.08] border border-white/[0.1] text-[10px] text-zinc-300 font-label">
                {lightboxProject.floors}
              </span>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
