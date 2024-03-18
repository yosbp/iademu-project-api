<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NumberToWords\NumberToWords;
use Illuminate\Support\Str;

class WordController extends Controller
{
    public function createGoodsAndServicesWord(string $id)
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
        $archivoPlantilla = resource_path('templates/2-bienes-y-servicios-recibidos.docx');

        // Cargar el archivo
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($archivoPlantilla);

        // Lógica para cambiar los datos de ciertas celdas en el archivo Word
        $phpWord->setValue('date', $order->created_at->format('d/m/Y'));
        $phpWord->setValue('provider_name', $provider->name);
        $phpWord->setValue('provider_rif', $provider->rif);
        $phpWord->setValue('description', $paymentOrder->description);
        $phpWord->setValue('text_amount', Str::upper(NumberToWords::transformNumber('es', $totalAmount)) . '(' . $totalAmount . ')');

        // Puedes devolver una respuesta adecuada, por ejemplo, un mensaje de éxito
        return response()->stream(
            function () use ($phpWord) {
                $phpWord->saveAs('php://output');
            },
            200,
            [
                'Content-Type' => 'application/msword',
                'Content-Disposition' => 'attachment; filename="2-bienes-y-servicios-recibidos.docx"',
            ]
        );
    }

    public function approveMemorandum(string $id){
        $order = Order::where('id', $id)->first();
        $provider = $order->provider;

        // Order Date with Carbon
        $orderDate = $order->created_at;
        $date = Carbon::parse($orderDate)->locale('es');

        // Ruta completa del archivo de plantilla
        $archivoPlantilla = resource_path('templates/7-memorandum-de-aprobacion.docx');

        // Cargar el archivo
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($archivoPlantilla);

        // Lógica para cambiar los datos de ciertas celdas en el archivo Word
        $phpWord->setValue('date', $order->created_at->format('d/m/Y'));
        $phpWord->setValue('provider_name', Str::upper($provider->name));
        $phpWord->setValue('day', $date->day);
        $phpWord->setValue('month', $date->monthName);
        $phpWord->setValue('year', $date->year);

        // Puedes devolver una respuesta adecuada, por ejemplo, un mensaje de éxito
        return response()->stream(
            function () use ($phpWord) {
                $phpWord->saveAs('php://output');
            },
            200,
            [
                'Content-Type' => 'application/msword',
                'Content-Disposition' => 'attachment; filename="3-memorandum-de-aprobacion.docx"',
            ]
        );
    }
}
