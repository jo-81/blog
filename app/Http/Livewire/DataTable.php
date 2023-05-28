<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $orderField = '';
    public string $orderDirection = 'ASC';
    protected $queryString = [
        'search' => ['except' => ''],
        'orderField' => ['except' => ''],
        'orderDirection' => ['except' => 'ASC'],
    ];
    public array $selection = [];
    public $entity;
    public array $labels = [];
    public int $pagination = 10;

    public function render()
    {
        $items = $this->entity::where('name', 'LIKE', "%{$this->search}%");
        if (! empty($this->orderField)) {
            $items->orderBy($this->orderField, $this->orderDirection);
        }

        return view('livewire.data-table', [
            'items' => $items->paginate($this->pagination)
        ]);
    }
    
    /**
     * setOrderField
     * Trie un champ
     * 
     * @param  string $name
     * @return void
     */
    public function setOrderField(string $name)
    {
        if ($name === $this->orderField) {
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }
    
    /**
     * remove
     * Supprime une sélection d'éléments
     *
     * @param  array<int> $ids
     * @return void
     */
    public function remove(array $ids): void
    {
        $this->entity::destroy($ids);
        session()->flash('success', "Votre sélection a bien été supprimée");
        $this->selection = [];
    }
}
