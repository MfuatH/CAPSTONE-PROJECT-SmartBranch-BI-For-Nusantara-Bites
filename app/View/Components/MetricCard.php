<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MetricCard extends Component
{
    public $title, $value, $subtitle, $trend, $icon;

    public function __construct($title, $value, $subtitle, $trend, $icon)
    {
        $this->title = $title;
        $this->value = $value;
        $this->subtitle = $subtitle;
        $this->trend = $trend;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.metric-card');
    }
}
