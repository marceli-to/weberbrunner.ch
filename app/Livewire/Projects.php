<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;

class Projects extends Component
{
    #[Url]
    public array $types = [];

    #[Url]
    public array $status = [];

    #[Url]
    public array $locations = [];

    #[Url]
    public bool $publications = false;

    public function toggleType($type)
    {
        if (in_array($type, $this->types)) {
            $this->types = array_values(array_diff($this->types, [$type]));
        } else {
            $this->types[] = $type;
        }
    }

    public function toggleStatus($status)
    {
        if (in_array($status, $this->status)) {
            $this->status = array_values(array_diff($this->status, [$status]));
        } else {
            $this->status[] = $status;
        }
    }

    public function toggleLocation($location)
    {
        if (in_array($location, $this->locations)) {
            $this->locations = array_values(array_diff($this->locations, [$location]));
        } else {
            $this->locations[] = $location;
        }
    }

    public function togglePublications()
    {
        $this->publications = !$this->publications;
    }

    public function clearFilters()
    {
        $this->types = [];
        $this->status = [];
        $this->locations = [];
        $this->publications = false;
    }

    public function render()
    {
        // Dummy project data
        $projects = collect([
            ['title' => 'Recyclingzentrum Juch-Areal, Zürich', 'slug' => 'recyclingzentrum-juch-areal', 'image' => 'images/dummy-teaser-1.jpg', 'type' => 'oeffentliche-gebaeude', 'status' => 'realisiert', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Wohnhaus H. Weiningen', 'slug' => 'wohnhaus-h-weiningen', 'image' => 'images/dummy-teaser-2.jpg', 'type' => 'wohnungsbau', 'status' => 'realisiert', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Lokstadt, Winterthur', 'slug' => 'lokstadt-winterthur', 'image' => 'images/dummy-teaser-3.jpg', 'type' => 'wohnungsbau', 'status' => 'in-bearbeitung', 'location' => 'zuerich', 'publication' => true],
            ['title' => 'Stadtraum Bahnhof, Langenthal', 'slug' => 'stadtraum-bahnhof-langenthal', 'image' => 'images/dummy-teaser-4.jpg', 'type' => 'oeffentliche-gebaeude', 'status' => 'projekte', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Puschkinallee', 'slug' => 'puschkinallee', 'image' => 'images/dummy-teaser-5.jpg', 'type' => 'wohnungsbau', 'status' => 'realisiert', 'location' => 'berlin', 'publication' => false],
            ['title' => 'Spinnerei III, Windisch', 'slug' => 'spinnerei-iii-windisch', 'image' => 'images/dummy-teaser-6.jpg', 'type' => 'bauen-im-bestand', 'status' => 'realisiert', 'location' => 'zuerich', 'publication' => true],
            ['title' => 'Bergacker, Zürich-Affoltern', 'slug' => 'bergacker-zuerich-affoltern', 'image' => 'images/dummy-teaser-7.jpg', 'type' => 'wohnungsbau', 'status' => 'in-bearbeitung', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Hagmannareal, Winterthur', 'slug' => 'hagmannareal-winterthur', 'image' => 'images/dummy-teaser-8.jpg', 'type' => 'wohnungsbau', 'status' => 'projekte', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Recyclingzentrum Juch-Areal', 'slug' => 'recyclingzentrum-juch-areal-2', 'image' => 'images/dummy-teaser-9.jpg', 'type' => 'zirkulaeres-bauen', 'status' => 'realisiert', 'location' => 'zuerich', 'publication' => true],
            ['title' => 'Stadtraum Bahnhof, Langenthal', 'slug' => 'stadtraum-bahnhof-langenthal-2', 'image' => 'images/dummy-teaser-10.jpg', 'type' => 'oeffentliche-gebaeude', 'status' => 'in-bearbeitung', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Wohnhaus H. Weiningen', 'slug' => 'wohnhaus-h-weiningen-2', 'image' => 'images/dummy-teaser-11.jpg', 'type' => 'wohnungsbau', 'status' => 'realisiert', 'location' => 'zuerich', 'publication' => false],
            ['title' => 'Lokstadt, Winterthur', 'slug' => 'lokstadt-winterthur-2', 'image' => 'images/dummy-teaser-12.jpg', 'type' => 'zustandsanalyse', 'status' => 'projekte', 'location' => 'zuerich', 'publication' => false],
        ]);

        $filtered = $projects;

        // Filter by types (multi-select AND logic)
        if (!empty($this->types)) {
            $filtered = $filtered->filter(fn($p) => !in_array($p['type'], $this->types));
        }

        // Filter by status (multi-select AND logic)
        if (!empty($this->status)) {
            $filtered = $filtered->filter(fn($p) => !in_array($p['status'], $this->status));
        }

        // Filter by locations (multi-select AND logic)
        if (!empty($this->locations)) {
            $filtered = $filtered->filter(fn($p) => !in_array($p['location'], $this->locations));
        }

        // Filter by publications
        if ($this->publications) {
            $filtered = $filtered->filter(fn($p) => $p['publication'] === true);
        }

        return view('livewire.projects', [
            'projects' => $filtered->values(),
            'availableTypes' => [
                'oeffentliche-gebaeude' => 'Öffentliche Gebäude',
                'wohnungsbau' => 'Wohnungsbau',
                'bauen-im-bestand' => 'Bauen im Bestand',
                'zustandsanalyse' => 'Zustandsanalyse',
                'zirkulaeres-bauen' => 'Zirkuläres Bauen',
                'lca' => 'LCA',
            ],
            'availableStatus' => [
                'projekte' => 'Projekte',
                'in-bearbeitung' => 'In Bearbeitung',
                'realisiert' => 'Realisiert',
            ],
            'availableLocations' => [
                'zuerich' => 'Zürich',
                'berlin' => 'Berlin',
            ],
        ]);
    }
}
