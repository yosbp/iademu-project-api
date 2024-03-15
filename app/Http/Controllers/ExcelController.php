<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;

class ExcelController extends Controller
{
    public function createPaymentOrder(string $id)
    {

        $order = Order::where('id', $id)->first();
        $paymentOrder = $order->paymentOrder;
        $provider = $order->provider;

        // Get the total amount of each order
        $subTotal = 0;
        $totalAmount = 0;
        foreach ($order->orderItems as $item) {
            $subTotal += $item->total_amount;
        };
        $totalAmount = $subTotal + $order->tax - $order->exempt;

        // Ruta completa del archivo de plantilla
        $archivoPlantilla = resource_path('templates/orden-de-pago.xlsx');

        // Cargar el archivo
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($archivoPlantilla);

        // Obtener la hoja
        $hoja = $spreadsheet->getActiveSheet();

        // Lógica para cambiar los datos de ciertas celdas en el archivo Excel
        $hoja->setCellValue('F12', Str::upper($paymentOrder->payment_method));
        $hoja->setCellValue('K12', $paymentOrder->payment_reference);
        $hoja->setCellValue('E15', Str::upper($paymentOrder->bank));
        $hoja->setCellValue('L15', $paymentOrder->account_number);
        $hoja->setCellValue('L18', $totalAmount);
        $hoja->setCellValue('B10', Str::upper($provider->name));
        $hoja->setCellValue('B18', Str::upper(NumberToWords::transformNumber('es', $totalAmount)). ' BOLÍVARES EXACTOS');
        $hoja->setCellValue('L5', 'FECHA DE LA ORDEN: ' . $order->created_at->format('d/m/Y'));


        /* $hoja->getStyle(('L5'))->getFont()->setSize(20); */
        // Guardar el archivo en la nueva carpeta
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Devolver el archivo Excel
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename=orden-de-pago.xlsx',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
                'Pragma'              => 'public',
            ]
        );
    }

    public function createBuyOrder(string $id)
    {

        $order = Order::where('id', $id)->first();
        $provider = $order->provider;

        // Get the total amount of each order
        $subTotal = 0;
        $totalAmount = 0;
        foreach ($order->orderItems as $item) {
            $subTotal += $item->total_amount;
        };

        // Ruta completa del archivo de plantilla
        $archivoPlantilla = resource_path('templates/1-orden-de-compra.xlsx');

        // Cargar el archivo
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($archivoPlantilla);

        // Obtener la hoja
        $hoja = $spreadsheet->getActiveSheet();

        // Lógica para cambiar los datos de ciertas celdas en el archivo Excel
        $hoja->setCellValue('B9', Str::upper($provider->name));
        $hoja->setCellValue('B10', Str::upper($provider->address));
        $hoja->setCellValue('I10', Str::upper($provider->phone));
        $hoja->setCellValue('J12', $provider->rif);
        $hoja->setCellValue('J35', $order->exempt);

        // Set the order items starting from A15 to A32
        $row = 15;
        foreach ($order->orderItems as $item) {
            $hoja->setCellValue('A' . $row, $item->item_name);
            $hoja->setCellValue('H' . $row, $item->item_quantity);
            $hoja->setCellValue('I' . $row, $item->item_amount);
            $row++;
        }

        // Guardar el archivo en la nueva carpeta
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        // Devolver el archivo Excel
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename=1-orden-de-compra.xlsx',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
                'Pragma'              => 'public',
            ]
        );

        /* return response()->json($provider, 200); */
    }
}
