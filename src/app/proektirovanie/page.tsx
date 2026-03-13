import type { Metadata } from "next";
import dynamic from "next/dynamic";
import HeaderNav from "@/components/sections/HeaderNav";
import SectionErrorBoundary from "@/components/ui/SectionErrorBoundary";
import ProektHeroSection from "./components/ProektHeroSection";

function SectionSkeleton() {
  return (
    <div className="bg-black py-24 lg:py-32">
      <div className="mx-auto max-w-7xl px-4 animate-pulse">
        <div className="h-8 w-48 bg-zinc-800/50 rounded-lg mb-6" />
        <div className="h-4 w-96 max-w-full bg-zinc-800/30 rounded mb-4" />
        <div className="h-4 w-72 max-w-full bg-zinc-800/30 rounded" />
      </div>
    </div>
  );
}

const ProektWhySection = dynamic(
  () => import("./components/ProektWhySection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektServicesSection = dynamic(
  () => import("./components/ProektServicesSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektCompositionSection = dynamic(
  () => import("./components/ProektCompositionSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektProcessSection = dynamic(
  () => import("./components/ProektProcessSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektPortfolioSection = dynamic(
  () => import("./components/ProektPortfolioSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektGallerySection = dynamic(
  () => import("./components/ProektGallerySection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektPricingSection = dynamic(
  () => import("./components/ProektPricingSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektFaqSection = dynamic(
  () => import("./components/ProektFaqSection"),
  { loading: () => <SectionSkeleton /> }
);
const ProektCtaFormSection = dynamic(
  () => import("./components/ProektCtaFormSection"),
  { loading: () => <SectionSkeleton /> }
);
const FooterSection = dynamic(
  () => import("@/components/sections/FooterSection"),
  { loading: () => <SectionSkeleton /> }
);

export const metadata: Metadata = {
  title: "Проектирование загородных домов — СтройМСК | Каменные и каркасные дома для Подмосковья",
  description:
    "Индивидуальное проектирование загородных домов в Москве и Подмосковье. 350+ проектов, каменные и каркасные дома 95–700 м². Полный цикл от эскиза до BIM-модели. Бесплатная консультация.",
  openGraph: {
    title: "Проектирование загородных домов — СтройМСК",
    description:
      "Полный цикл проектирования загородных домов. 350+ проектов, каменные и каркасные дома, 15 лет опыта.",
    type: "website",
    locale: "ru_RU",
    siteName: "СтройМСК",
  },
};

export default function ProektirovaniePage() {
  return (
    <main>
      <HeaderNav />
      <SectionErrorBoundary><ProektHeroSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektWhySection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektServicesSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektCompositionSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektProcessSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektPortfolioSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektGallerySection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektPricingSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektFaqSection /></SectionErrorBoundary>
      <SectionErrorBoundary><ProektCtaFormSection /></SectionErrorBoundary>
      <SectionErrorBoundary><FooterSection /></SectionErrorBoundary>
    </main>
  );
}
