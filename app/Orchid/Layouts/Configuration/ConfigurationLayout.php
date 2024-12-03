<?php

namespace App\Orchid\Layouts\Configuration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;

class ConfigurationLayout extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [];

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::tabs([
                __('Category') => [
                    Layout::rows([
                        Button::make(__('Add Category'))
                        ->icon('plus')
                        ->modal('productModal')
                        ->method('createCategory')
                        ->class('btn btn-primary float-end'),
                    ]),
                    CategoryLayout::class
                ],
                __('Color') => [
                    Layout::rows([
                        Button::make(__('Add Color'))
                        ->icon('plus')
                        ->modal('productModal')
                        ->method('createColor')
                        ->class('btn btn-primary float-end'),
                    ]),  
                    ColorLayout::class
                ],
                __('Size') => [
                    Layout::rows([
                        Button::make(__('Add Size'))
                        ->icon('plus')
                        ->modal('productModal')
                        ->method('createSize')
                        ->class('btn btn-primary float-end'),
                    ]),
                    SizeLayout::class
                ],
            ]),
        ];
    }

    /**
     * Update state
     *
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $activeTab = $request->query('tab');
        session(['active_tab' => $activeTab]);
        return $repository;
    }
}
