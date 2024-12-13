<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SampleChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $data): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $chart = $this->chart->barChart()
            ->setTitle('Jumlah Temuan per Patrol Berdasarkan Tahun')
            ->setSubtitle('Data Berdasarkan Tahun');

        // Menambahkan data untuk setiap Patrol
        foreach ($data['patrols'] as $patrol) {
            $chart->addData($patrol, $data['temuan'][$patrol]);
        }

        // Menambahkan label Tahun di X-Axis
        $chart->setXAxis($data['tahun']);

        return $chart;
    }
}
