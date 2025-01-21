<?php

namespace App\View\Components;

use App\Models\Posisi; 
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class posisiDropdown extends Component
{
    public $posisi;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->posisi = Posisi::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.posisi-dropdown');
    }
}
