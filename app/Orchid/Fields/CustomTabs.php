<?php

namespace App\Orchid\Fields;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Orchid\Screen\Layouts\Tabs;
use Orchid\Screen\Repository;

class CustomTabs extends Tabs
{
    protected $name = "";
    public function __construct(string $name, array $layouts = [])
    {
        parent::__construct();
        $this->name = $name;
        $this->layouts = $layouts;
    }
    public function build(Repository $repository)
    {
        $activeTab = $this->variables['activeTab'] ?? 'default-tab';
        Session::put('active_tab_', $activeTab);


        return $this->buildAsDeep($repository);
    }
}
