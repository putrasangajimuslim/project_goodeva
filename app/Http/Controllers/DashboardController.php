<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard');
    }

    public function getData() {
        $data = Projects::groupBy('category')
                ->selectRaw('category, count(*) as total')
                ->get();

        $portfolios = Projects::all();
        $chartData = [];

        // Memproses data portfolio untuk mengisi chartData
        foreach ($portfolios as $portfolio) {
            // Menggunakan created_at sebagai waktu (contoh: H:i)
            $timeLabel = $portfolio->created_at->format('H:i');

            // Contoh pengolahan untuk nilai RPM, misalnya dari nilai tertentu dalam judul
            // Misalnya, kita asumsikan nilai RPM adalah angka terakhir dalam judul proyek
            preg_match('/\d+$/', $portfolio->title, $matches);
            $rpmValue = count($matches) > 0 ? (int) $matches[0] : 0;

            // Menambahkan data ke dalam chartData
            $chartData[] = [
                'time' => $timeLabel,
                'rpm' => $rpmValue,
            ];
        }

        $categories = Projects::distinct('category')->pluck('category');

        $categoryPercentages = [];
        $totalPortfolios = Projects::count();

        foreach ($categories as $category) {
            $categoryPortfolios = Projects::where('category', $category)->count();

            if ($totalPortfolios > 0) {
                $percentage = ($categoryPortfolios / $totalPortfolios) * 100;
                $lastPercentage = number_format($percentage) . "%";
            } else {
                $lastPercentage = "0.00";
            }

            $categoryPercentages[] = [
                'category' => $category,
                'percentage' => $lastPercentage,
            ];
        }

        $data = [
            'pie' => $data,
            'rpm' => $chartData,
            'bulat' => $data,
        ];

        // $data = Projects::all();
        
        return response()->json($data);
    }

    public function getDataChartGaris() {

        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $projectCounts = DB::table('projects')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        $result = [];

        $arrayCount = [];

        foreach ($bulanList as $index => $bulan) {
            $result[$index + 1] = [
                'jumlah' => 0,
            ];
        }

        foreach ($projectCounts as $count) {
            $month = $count->month;

            $result[$month]['jumlah'] = $count->count;
        }
    
        foreach ($result as $r) {

            array_push($arrayCount, $r['jumlah']);
        }

        $result = [
            [$bulanList],
            [$arrayCount]
        ];

        return response()->json($result);
    }

    public function export() {
        $projects = Projects::all();

        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Title');
        // $sheet->setCellValue('B1', 'Description');

        // $row = 2;
        // foreach ($projects as $project) {
        //     $sheet->setCellValue('A' . $row, $project->title);
        //     $sheet->setCellValue('B' . $row, $project->description);
        //     $row++;
        // }

        // $writer = new Xlsx($spreadsheet);

        // $response = Response::stream(
        //     function () use ($writer) {
        //         $writer->save('php://output');
        //     },
        //     200,
        //     [
        //         'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        //         'Content-Disposition' => 'attachment; filename="project.xlsx"'
        //     ]
        // );

        // return $response;

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=project.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['title', 'Description', 'Category']);

        foreach ($projects as $project) {
            fputcsv($handle, [$project->title, $project->description, $project->category]);
        }

        fclose($handle);

        return Response::make('', 200, $headers);
    }
}
