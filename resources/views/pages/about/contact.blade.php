@section('meta_title', 'Kontakt – Büro')
@section('meta_description', '')
<x-layout.inner title="Kontakt">
  <div class="flex flex-col gap-y-24 md:gap-y-40 lg:gap-56">
    <div>
      <x-headings.section class="mb-12 md:mb-16 lg:mb-18">
        Zürich
      </x-headings.section>

      <x-cards.location
        image="images/dummy-location-zuerich.jpg"
        alt="weberbrunner architektur ag Zürich"
        maps-url="https://maps.google.com">

        <x-headings.h3 weight="normal">
          weberbrunner architektur ag
        </x-headings.h3>

        <p>
          Binzstrasse 23, 8045 Zürich<br>
          Tel +41 44 405 20 80<br>
          <a 
            href="mailto:info@weberbrunner.ch"
            aria-label="Senden Sie eine E-Mail an info@weberbrunner.ch"
            class="hover:underline underline-offset-4 lg:underline-offset-8 decoration-1">
            info@weberbrunner.ch
          </a>
        </p>
      </x-cards.location>
    </div>
    <div>
      <x-headings.section class="mb-12 md:mb-16 lg:mb-18">
        Berlin
      </x-headings.section>

      <x-cards.location
        image="images/dummy-location-berlin.jpg"
        alt="weberbrunner pischetsrieder architektur Gesellschaft von Architekten mbH"
        maps-url="https://maps.google.com">
        <x-headings.h3 weight="normal">
          weberbrunner pischetsrieder architektur<br>Gesellschaft von Architekten mbH
        </x-headings.h3>
        <p>
          Zehdenicker Straße 2110119 Berlin<br>
          Tel +49 30 123 456 78<br>
          <a 
            href="mailto:info@wbp-architektur.de"
            aria-label="Senden Sie eine E-Mail an info@wbp-architektur.de"
            class="hover:underline underline-offset-4 lg:underline-offset-8 decoration-1">
            info@wbp-architektur.de
          </a>
        </p>
      </x-cards.location>
    </div>
  </div>
</x-layout.inner>
