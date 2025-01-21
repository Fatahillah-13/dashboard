<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Departemen;

class departemenDropdown extends Component
{
    public $departemen;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->departemen = Departemen::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.departemen-dropdown');
    }
}
