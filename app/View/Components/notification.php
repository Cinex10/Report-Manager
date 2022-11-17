<?php

namespace App\View\Components;

use Illuminate\View\Component;

class notification extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $description;
    public $state;
    public $time;
    public function __construct($title, $description, $state, $time)
    {
        $this->title = $title;
        $this->description = $description;
        $this->state = $state;
        $this->time = $time;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.notification', [
            'title' => $this->title,
            'description' => $this->description,
            'state' => $this->state,
            'time' => $this->time,
        ]);
    }
}
