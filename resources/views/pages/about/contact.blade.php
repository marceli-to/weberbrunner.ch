@section('meta_title', 'Kontakt – Büro')
@section('meta_description', '')
<x-layout.inner title="Kontakt">

  <div class="flex flex-col gap-y-24 md:gap-y-40 lg:gap-56">

    @foreach($locations as $location)
      @foreach($location->contacts as $contact)
        <div>

          <x-headings.section class="mb-12 md:mb-16 lg:mb-18">
            {{ $location->title }}
          </x-headings.section>

          <x-cards.location
            :image="$contact->image?->file"
            :alt="$contact->company_name . ' ' . $location->title">

            <x-headings.h3 variant="normal">
              {!! nl2br(e($contact->company_name)) !!}
            </x-headings.h3>

            <p>
              {!! nl2br(e($contact->address)) !!}
              @if($contact->phone)
                <br>Tel {{ $contact->phone }}
              @endif
              @if($contact->email)
                <br>
                <a
                  href="mailto:{{ $contact->email }}"
                  aria-label="Senden Sie eine E-Mail an {{ $contact->email }}"
                  class="hover:no-underline underline-offset-4 lg:underline-offset-6 decoration-1">
                  {{ $contact->email }}
                </a>
              @endif
            </p>

            @if($contact->maps_url)
              <div class="mt-6">
                <x-links.cta href="{{ $contact->maps_url }}" :target="'_blank'">
                  Google Maps
                </x-links.cta>
              </div>
            @endif
          </x-cards.location>

        </div>
      @endforeach
    @endforeach

  </div>

</x-layout.inner>
