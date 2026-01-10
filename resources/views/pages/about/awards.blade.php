@section('meta_title', 'Auszeichnungen – Büro')
@section('meta_description', '')
<x-layout.inner title="Auszeichnungen">

  <x-headings.section class="mb-6 md:mb-8 lg:mb-10">
    Auszeichnungen und Preise
  </x-headings.section>

  <x-container.inner class="flex flex-col gap-y-16 md:gap-y-24 lg:gap-y-48 lg:!pr-0">

    {{-- 2023 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2023
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Auszeichnung «best architects 24»</strong> Sanierung, Um- und Anbau, <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Haus B4, Zürich</a>
        </x-cards.award>
      </div>
    </div>

    {{-- 2021 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2021
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Lobende Erwähnung – Hindernisfrei Architektur</strong> – Die Schweizer Fachstelle «40 Jahre Engagement für eine bessere Gesellschaft»
        </x-cards.award>
        <x-cards.award><strong>WIA – Ausstellung Women in Architecture</strong>, Berlin</x-cards.award>
      </div>
    </div>

    {{-- 2020 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2020
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>AW20 Architekturpreis Region Winterthur</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Wohnüberbauung Hagmannareal, Winterthur</a>
        </x-cards.award>
        <x-cards.award>
          <strong>Auszeichnung «best architects 21»</strong> <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Loft Windisch</a>
        </x-cards.award>
      </div>
    </div>

    {{-- 2019 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2019
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award><strong>Architekturpreis Kanton Zürich Auszeichnung 19</strong></x-cards.award>
      </div>
    </div>

    {{-- 2018 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2018
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Nominierung ARC-Award 2018</strong> der <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Wohnüberbauung Hagmannareal, Winterthur</a>
        </x-cards.award>
        <x-cards.award>
          <strong>Auszeichnung Gold «best architects 19»</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Wohnüberbauung Hagmannareal, Winterthur</a>
        </x-cards.award>
      </div>
    </div>

    {{-- 2015 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2015
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Auszeichnung «best architects 16»</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Mehrfamilienhaus Im Amt, Gutenswil</a>
        </x-cards.award>
        <x-cards.award>
          <strong>Auszeichnung «best architects 16»</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Sportzentrum Eselriet, Effretikon</a>
        </x-cards.award>
      </div>
    </div>

    {{-- 2008 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2008
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Auszeichnung «best architects 09»</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Sporthalle Hardau, Zürich</a>
        </x-cards.award>
        <x-cards.award>
          <strong>Europäischer Spengler-Metall Architekturpreis</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Sporthalle Hardau, Zürich</a>
        </x-cards.award>
      </div>
    </div>

    {{-- 2003 --}}
    <div class="md:grid md:grid-cols-9">
      <x-headings.h3 variant="normal" class="md:col-span-1">
        2003
      </x-headings.h3>
      <div class="md:col-span-8 flex flex-col gap-y-8 md:gap-y-16">
        <x-cards.award>
          <strong>Denkmalschutzpreis «Wohnen unter Dächern»</strong> für <a href="#" class="underline underline-offset-4 decoration-1 hover:no-underline">Wohnhaus Schaufelberger</a>
        </x-cards.award>
      </div>
    </div>

  </x-container.inner>
</x-layout.inner>
