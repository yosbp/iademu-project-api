<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WordController extends Controller
{
    public function createGoodsAndServicesWord(Request $request)
    {
        // Datos para el archivo Word
        $wordData = [
            'valor1' => 'Dato 1',
            'valor2' => 'Dato 2',
            // Otros datos
        ];

        // Ruta de la carpeta para almacenar el archivo
        $carpetaNueva = 'nueva_carpeta';

        // Verifica si la carpeta existe, si no, créala
        if (!Storage::exists($carpetaNueva)) {
            Storage::makeDirectory($carpetaNueva);
        }

        // Ruta completa para almacenar el archivo en la nueva carpeta
        $rutaArchivo = $carpetaNueva . '/2-bienes-y-servicios-recibidos.docx';

        // Ruta completa del archivo de plantilla
        $archivoPlantilla = resource_path('templates/2-bienes-y-servicios-recibidos.docx');

        // Cargar el archivo
        $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($archivoPlantilla);

        // Lógica para cambiar los datos de ciertas celdas en el archivo Word
        $phpWord->setValue('valor1', $wordData['valor1']);
        $phpWord->setValue('valor2', $wordData['valor2']);

        // Guardar el archivo en la nueva carpeta
        $phpWord->saveAs(storage_path("app/{$rutaArchivo}"));

        // Puedes devolver una respuesta adecuada, por ejemplo, un mensaje de éxito
        return response()->json(['message' => 'Archivo Word guardado correctamente']);
    }
}
