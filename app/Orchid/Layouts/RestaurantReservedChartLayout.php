<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Chart;

class RestaurantReservedChartLayout extends Chart
{
    /**
     * @var string
     */
    protected $target = 'reserved_chart';

    /**
     * @var string
     */
    protected $title = 'Số bàn đã đặt trong 7 ngày gần nhất';

    /**
     * @var string
     */
    protected $type = self::TYPE_LINE;

    /**
     * @return array
     */
    protected function labels(): array
    {
        return $this->query->get('reserved_chart.labels', []);
    }

    /**
     * @return array
     */
    protected function values(): array
    {
        return $this->query->get('reserved_chart.values', []);
    }
} 