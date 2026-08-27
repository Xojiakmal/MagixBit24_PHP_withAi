<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SearchDropdown extends Component
{
    public $model; // e.g., 'App\Models\User'
    public $searchFields = ['name']; // Array of fields to search
    public $displayField = 'name'; // Field to display in the dropdown
    public $valueField = 'id';
    public $placeholder = 'Qidirish...';
    public $emitEvent = 'itemSelected';
    public $eventName = 'contactSelected'; // unique event name
    
    public $search = '';
    public $results = [];
    public $selectedValue = null;
    public $selectedName = '';
    public $showDropdown = false;

    public function mount($searchField = null, $searchFields = null)
    {
        if ($searchFields) {
            $this->searchFields = $searchFields;
        } elseif ($searchField) {
            $this->searchFields = [$searchField];
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            $this->showDropdown = false;
            return;
        }

        $query = $this->model::query();
        
        $searchTerm = strtolower($this->search);
        
        $results = $query->where(function ($q) {
            foreach ($this->searchFields as $field) {
                $q->orWhere($field, 'ilike', '%' . $this->search . '%');
            }
        })
        ->limit(30)
        ->get();

        // Sort by similarity in PHP
        $this->results = $results->sortByDesc(function ($item) use ($searchTerm) {
            $maxSim = 0;
            foreach ($this->searchFields as $field) {
                $val = strtolower($item->{$field} ?? '');
                similar_text($searchTerm, $val, $percent);
                if ($percent > $maxSim) {
                    $maxSim = $percent;
                }
            }
            return $maxSim;
        })->take(10)->values()->toArray();
            
        $this->showDropdown = true;
    }

    public function selectItem($id, $name)
    {
        $this->selectedValue = $id;
        $this->selectedName = $name;
        $this->search = '';
        $this->showDropdown = false;
        
        $this->dispatch($this->emitEvent, id: $id, name: $name, eventName: $this->eventName);
    }

    public function clearSelection()
    {
        $this->selectedValue = null;
        $this->selectedName = '';
        $this->dispatch($this->emitEvent, id: null, name: '', eventName: $this->eventName);
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }
}
