@section('meta_title', 'Team – Büro')
@section('meta_description', '')

@php
$team = [
  ['firstname' => 'Anna', 'name' => 'Müller', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2018', 'email' => 'anna.mueller@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Boris', 'name' => 'Brunner', 'title' => 'dipl. Arch. FH / BSA / SIA', 'employed_since' => '2015', 'email' => 'boris.brunner@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Carla', 'name' => 'Schneider', 'title' => 'M. Sc. Arch TU Zürich', 'employed_since' => '2020', 'email' => 'carla.schneider@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Daniel', 'name' => 'Weber', 'title' => 'B. Sc. Architektur ZHAW', 'employed_since' => '2021', 'email' => 'daniel.weber@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Elena', 'name' => 'Fischer', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2019', 'email' => 'elena.fischer@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Florian', 'name' => 'Keller', 'title' => 'dipl. Arch. FH', 'employed_since' => '2017', 'email' => 'florian.keller@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Gabriela', 'name' => 'Hofmann', 'title' => 'M. Sc. Arch TU Wien', 'employed_since' => '2022', 'email' => 'gabriela.hofmann@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Hans', 'name' => 'Zimmermann', 'title' => 'B. Sc. Architektur FHNW', 'employed_since' => '2020', 'email' => 'hans.zimmermann@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Irene', 'name' => 'Schmid', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2016', 'email' => 'irene.schmid@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Jonas', 'name' => 'Meier', 'title' => 'Lernender', 'employed_since' => '2024', 'email' => 'jonas.meier@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Kathrin', 'name' => 'Gerber', 'title' => 'M. Sc. Arch TU München', 'employed_since' => '2019', 'email' => 'kathrin.gerber@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Lukas', 'name' => 'Huber', 'title' => 'dipl. Arch. FH / SIA', 'employed_since' => '2018', 'email' => 'lukas.huber@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Maria', 'name' => 'Steiner', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2021', 'email' => 'maria.steiner@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Niklaus', 'name' => 'Baumann', 'title' => 'B. Sc. Architektur HSR', 'employed_since' => '2023', 'email' => 'niklaus.baumann@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Olivia', 'name' => 'Graf', 'title' => 'M. Sc. Arch TU Berlin', 'employed_since' => '2020', 'email' => 'olivia.graf@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Patrick', 'name' => 'Frei', 'title' => 'dipl. Arch. FH', 'employed_since' => '2017', 'email' => 'patrick.frei@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Rahel', 'name' => 'Widmer', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2019', 'email' => 'rahel.widmer@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Stefan', 'name' => 'Moser', 'title' => 'M. Sc. Arch TU Darmstadt', 'employed_since' => '2022', 'email' => 'stefan.moser@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Tamara', 'name' => 'Bühler', 'title' => 'B. Sc. Architektur ZHAW', 'employed_since' => '2021', 'email' => 'tamara.buehler@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Urs', 'name' => 'Roth', 'title' => 'dipl. Arch. FH / BSA', 'employed_since' => '2014', 'email' => 'urs.roth@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Vera', 'name' => 'Künzler', 'title' => 'M. Sc. Arch ETH', 'employed_since' => '2020', 'email' => 'vera.kuenzler@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Werner', 'name' => 'Sutter', 'title' => 'M. Sc. Arch TU Graz', 'employed_since' => '2018', 'email' => 'werner.sutter@weberbrunner.ch', 'based_in' => 'zurich'],
  ['firstname' => 'Xenia', 'name' => 'Lehmann', 'title' => 'Lernende', 'employed_since' => '2025', 'email' => 'xenia.lehmann@weberbrunner.ch', 'based_in' => 'berlin'],
  ['firstname' => 'Yannick', 'name' => 'Wyss', 'title' => 'B. Sc. Architektur FHNW', 'employed_since' => '2023', 'email' => 'yannick.wyss@weberbrunner.ch', 'based_in' => 'zurich'],
];
@endphp

<x-layout.inner
  title="Team"
  containerClass="!pl-0 md:!pl-40"
  headerClass="pl-20"
  mainClass="!pb-0 relative">

  <div x-data="{ filter: 'all' }">
    <nav class="flex justify-center md:justify-start mb-10 md:mb-15">
    <ul class="flex items-center">
      <li class="flex items-center after:content-['/'] after:ml-3 after:mr-3">
        <button
          @click="filter = filter === 'zurich' ? 'all' : 'zurich'"
          :class="{ 'underline': filter === 'zurich' }">
          Zürich
        </button>
      </li>
      <li>
        <button
          @click="filter = filter === 'berlin' ? 'all' : 'berlin'"
          :class="{ 'underline': filter === 'berlin' }">
          Berlin
        </button>
      </li>
    </ul>
  </nav>

  {{-- <div class="md:border-l border-t border-black md:min-h-(--content-height-md) lg:min-h-(--content-height-lg)">
    <div class="md:grid md:grid-cols-12">
      @foreach($team as $member)
        <div
          data-based-in="{{ $member['based_in'] }}"
          x-show="filter === 'all' || filter === '{{ $member['based_in'] }}'"
          @class([
            'border-black md:col-span-6 lg:col-span-4',
            'md:border-r' => $loop->iteration % 2 !== 0,
            'lg:border-r-0' => $loop->iteration % 2 !== 0 && $loop->iteration % 3 === 0,
            'lg:border-r' => $loop->iteration % 2 === 0 && $loop->iteration % 3 !== 0,
            'border-t' => $loop->iteration > 1,
            'md:border-t-0' => $loop->iteration === 2,
            'lg:border-t-0' => $loop->iteration === 3,
          ])>
          <x-cards.team
            image="images/dummy-team-{{ $loop->iteration }}.jpg"
            :firstname="$member['firstname']"
            :name="$member['name']"
            :title="$member['title']"
            :employed-since="$member['employed_since']"
            :email="$member['email']"
          />
        </div>
      @endforeach
    </div>
  </div> --}}

<div class="md:min-h-(--content-height-md) lg:min-h-(--content-height-lg)">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 border-t md:border-l border-black">
    @foreach($team as $member)
      <div
        x-show="filter === 'all' || filter === '{{ $member['based_in'] }}'"
        class="border-r border-b border-black bg-white"
      >
        <x-cards.team
          image="images/dummy-team-{{ $loop->iteration }}.jpg"
          :firstname="$member['firstname']"
          :name="$member['name']"
          :title="$member['title']"
          :employed-since="$member['employed_since']"
          :email="$member['email']"
        />
      </div>
    @endforeach
  </div>
</div>


</x-layout.inner>
