<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublicLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $canonical = null,
        public ?string $image = null,
        public string $type = 'website',
        public array $structuredData = [],
        public string $robots = 'index, follow',
    ) {}

    public function render(): View
    {
        return view('layouts.public');
    }
}
