<?php

namespace App\View\Components;

use App\Models\KaryawanBaru;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class KaryawanDropdown extends Component
{
    public $karyawans;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->karyawans = KaryawanBaru::doesntHave('gambarKaryawan')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $karyawans = KaryawanBaru::doesntHave('gambarKaryawan')->get();
        return view('components.karyawan-dropdown', compact('karyawans'));
    }
}
