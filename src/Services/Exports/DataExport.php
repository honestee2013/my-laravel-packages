<?php

namespace  App\Modules\Core\Services\Exports;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DataExport implements FromView, WithDrawings
{
    protected $data;
    public $colCount = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $this->data["colCount"] = $this->colCount;

        //dd($this->data);
        return view('core.views::exports.data-table-excel', $this->data);
    }




    public function drawings()
    {
        $drawings = [];
        $imageFields = ['image', 'photo', 'picture']; // Fields to check for images

        $cols = 0;

        foreach ($this->data['data'] as $rowIndex => $row) {
            $colCount = 0;
            foreach ($row->toArray() as $fieldName => $value) {
                // Check if this column is one of the image fields

                if (in_array($fieldName, $imageFields) && !empty($value)) {
                    $drawing = new Drawing();
                    $drawing->setName('Image');
                    $drawing->setDescription('Image');
                    $drawing->setPath(public_path('storage/' . $value)); // Correct the path to your image
                    $drawing->setHeight(50); // Adjust the image height

                    // Automatically determine column letter based on columnIndex
                    $columnLetter = $this->getColumnLetter($colCount); // Convert numeric column index to letter
                    $drawing->setCoordinates($columnLetter . ($rowIndex + 2)); // Place image at rowIndex + 2

                    $drawings[] = $drawing;
                }

                $colCount++;
            }
        }
        return $drawings;
    }




    private  function getColumnLetter($index)
    {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = intval($index / 26) - 1;
        }
        return $letters;
    }




    public function export(){
        if ($this->data["fileType"] == "pdf")
            return $this->exportPDF();
        else if ($this->data["fileType"] == "xlsx" || $this->data["fileType"] == "csv")
            return $this->exportXLS();

    }



    public function exportPDF(){
        $pdf = Pdf::loadView('core.views::exports.data-table-pdf', $this->data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->download($this->data["fileName"] . '.pdf');
        }, $this->data["fileName"] . '.pdf');
    }


    public function exportXLS(){
        return Excel::download($this, "{$this->data["fileName"]}.{$this->data["fileType"]}");
    }






 /*private function exportSelected($format, $fileName = "")
    {



        // PDF Export
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.data-table-pdf', [
                'data' => $data,
                'columns' => $this->columns,
                'fieldDefinitions' => $this->fieldDefinitions,
                'multiSelectFormFields' => $this->multiSelectFormFields
            ]);
            return response()->streamDownload(function () use ($pdf, $fileName) {
                echo $pdf->download($fileName . '.pdf');
            }, $fileName . '.pdf');
        }

        $dataArray = [
            'data' => $data,
            'columns' => $this->columns,
            'fieldDefinitions' => $this->fieldDefinitions,
            'multiSelectFormFields' => $this->multiSelectFormFields
        ];
        // Excel Export
        return Excel::download(new DataExport($dataArray), "{$fileName}.{$format}");

    }*/









}



