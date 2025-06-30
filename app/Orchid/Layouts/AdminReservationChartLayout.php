<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Chart;

class AdminReservationChartLayout extends Chart
{
    /**
     * @var string
     */
    protected $target = 'reservation_chart';

    /**
     * @var string
     */
    protected $title = 'Biểu đồ số lượng đơn đặt bàn theo ngày';

    /**
     * @var string
     */
    protected $type = self::TYPE_LINE;

    protected function labels(): array
    {
        return $this->query->get('reservation_chart.0.labels', []);
    }

    protected function values(): array
    {
        return $this->query->get('reservation_chart.0.values', []);
    }
} 