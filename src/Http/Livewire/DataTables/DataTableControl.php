<?php

namespace QuickerFaster\CodeGen\Http\Livewire\DataTables;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Modules\Log\Models\UserActivityLog;

use QuickerFaster\CodeGen\Services\Exports\DataExportExcel;
use QuickerFaster\CodeGen\Services\Exports\DataTableExportCSV;
use QuickerFaster\CodeGen\Services\Imports\DataImport;
use Maatwebsite\Excel\Excel;


use QuickerFaster\CodeGen\Services\AccessControl\AccessControlService;
use QuickerFaster\CodeGen\Services\GUI\SweetAlertService;


class DataTableControl extends Component
{

    use WithFileUploads;

    ////////// DEFINE BY THE PARENT ///////////
    public $controls;
    public $columns;
    public $hiddenFields;
    public $visibleColumns;
    public $model;
    public $fieldDefinitions;
    public $multiSelectFormFields;
    public $modelName;
    public $moduleName;


    ////////// DEFINE BY THE CLASS ///////////
    public $file;
    public $bulkAction = '';
    public $selectedColumns;
    public $search = '';
    public $perPage;

    public $sortField;
    public $sortDirection = "asc";

    public $selectedRows = [];
    public array $filters = [];



    protected $listeners = [
        "toggleRowsSelectedEvent" => "toggleRowsSelected",
        "sortColumnEvent" => "updateSortParameters",
        "changeSearchEvent" => "changeSearch",
        "changePerPageEvent" => "changePerPage",
    ];



    public function mount()
    {
        // For selecting columns to SHOW/HIDE on the data table
        $this->selectedColumns = [...$this->visibleColumns];
    }




    public function updated($field, $value)
    {
        // Event name = 'Field name' + 'Event'. eg perPageEvent
        $event = "{$field}Event";
        $this->dispatch($event, $value);

    }



    // Update visibleColumns with the selectedColumns
    public function showHideSelectedColumns()
    {
        // Update visibleColumns with the selectedColumns
        $this->visibleColumns = $this->selectedColumns;
        $this->dispatch("showHideColumnsEvent", $this->selectedColumns);

    }



    



    public function toggleRowsSelected($rowIds)
    {
        $this->selectedRows = $rowIds;
    }


    public function applyBulkAction()
    {
        if ($this->bulkAction == "delete") {
            return $this->dispatch('confirmDeleteEvent', $this->selectedRows);
        } else if (str_contains($this->bulkAction, ":")) {
            $data = explode(":", $this->bulkAction);
            //public function updateModelField($modelIds, $fieldName, $fieldValue)
            $this->dispatch('updateModelFieldEvent', $this->selectedRows, $data[0], $data[1]);
        } else { // Export
            return $this->exportSelectedRows();
        }
    }




    public function applyFilters()
    {
        $this->dispatch('applyFilterEvent', $this->filters);
    }






    private function exportSelectedRows()
    {
        // File type
        $fileType = "xlsx";
        if ($this->bulkAction == "exportCSV")
            $fileType = "csv";
        else if ($this->bulkAction == "exportPDF")
            $fileType = "pdf";

        return $this->export($fileType, "", "selected");
    }



    // Export XLS, CSV, PDF files
    public function export($fileType, $fileName = "", $rows = "table")
    {
        // Check if the user has permission to perform the action
        if (!AccessControlService::checkPermission( 'export', $this->modelName)) {
            SweetAlertService::showError($this, "Error!", AccessControlService::MSG_PERMISSION_DENIED);
            return;
        }


        if (!$fileName && $this->model)
            $fileName = class_basename($this->model);
    
        if ($fileType === "xlsx") {
            return $this->exportToExcel($fileName, $rows);
        } elseif ($fileType === "csv") {
            return $this->exportToCSV($fileName, $rows);
        } elseif ($fileType === "pdf") {
            return $this->exportToPDF($fileName, $rows);
        } elseif ($fileType === "print") {
            //return $this->printPreview($rows); // 👈 Here it is integrated
        }
    }
    


    private function exportToExcel($fileName = "", $rows = "table")
    {
        
        $modelClass = '\\' . ltrim($this->model, '\\');
        $query = (new $modelClass)->newQuery();
        $hiddenFields = $this->hiddenFields["onQuery"];

        // Apply filters and search first
        $this->applySearchAndFilters($query, $hiddenFields);

        // Then restrict to selected IDs if needed
        if ($rows === "selected" && !empty($this->selectedRows)) {
            $query->whereIn('id', $this->selectedRows);
        }

        // Apply sorting
        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $data = $query->get();
        $excel = app(Excel::class);

        return $excel->download(
            new DataExportExcel($data, $this->fieldDefinitions, $this->visibleColumns),
            $fileName . '.xlsx'
        );
    }



    private function exportToCSV($fileName = "", $rows = "table")
    {
        $modelClass = '\\' . ltrim($this->model, '\\');
        $query = (new $modelClass)->newQuery();
        $hiddenFields = $this->hiddenFields["onQuery"];

        $this->applySearchAndFilters($query, $hiddenFields);

        if ($rows === "selected" && !empty($this->selectedRows)) {
            $query->whereIn('id', $this->selectedRows);
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $data = $query->get();
        $excel = app(Excel::class);

        return $excel->download(
            new DataTableExportCSV($data, $this->fieldDefinitions, $this->visibleColumns),
            $fileName . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }




    public function exportToPDF($fileName, $rows = "table")
    {

        $modelClass = '\\' . ltrim($this->model, '\\');
        $query = (new $modelClass)->newQuery();
        $hiddenFields = $this->hiddenFields["onQuery"];

        $this->applySearchAndFilters($query, $hiddenFields);

        if ($rows === "selected" && !empty($this->selectedRows)) {
            $query->whereIn('id', $this->selectedRows);
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $data = $query->get();
        $columns = $this->visibleColumns;
        $fieldDefs = $this->fieldDefinitions;

        $pdf = \PDF::loadView('core.views::exports.data-table-pdf', compact('data', 'columns', 'fieldDefs'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName . '.pdf');
    }




    protected function applySearchAndFilters(&$query, $hiddenFields)
    {
        if (!empty($this->search)) {
            $query->where(function ($q) use ($hiddenFields) {
                foreach ($this->fieldDefinitions as $fieldName => $definition) {
                    if (in_array($fieldName, $hiddenFields))
                        continue;

                    if (isset($definition['relationship'])) {
                        $rel = $definition['relationship'];
                        if (!isset($rel['type'], $rel['display_field'], $rel['dynamic_property']))
                            continue;

                        $displayField = explode(".", $rel['display_field']);
                        $displayField = count($displayField) > 1 ? "id" : $displayField[0];

                        $q->orWhereHas(
                            $rel['dynamic_property'],
                            fn($r) =>
                            $r->where($displayField, 'like', '%' . $this->search . '%')
                        );
                    } else {
                        $q->orWhere($fieldName, 'like', '%' . $this->search . '%');
                    }
                }
            });
        }

        foreach ($this->queryFilters ?? [] as $filter) {
            if (is_array($filter) && count($filter) === 3) {
                [$field, $operator, $value] = $filter;
                $column = "";

                if (str_contains($field, ".")) {
                    [$field, $column] = explode(".", $field);
                }

                if (isset($this->fieldDefinitions[$field]['relationship']['dynamic_property']) && $column) {
                    $dp = $this->fieldDefinitions[$field]['relationship']['dynamic_property'];
                    $query->whereHas($dp, fn($q) => $q->where($column, $operator, $value));
                } else {
                    $operator === 'in'
                        ? $query->whereIn($field, $value)
                        : $query->where($field, $operator, $value);
                }
            }
        }
    }




    public function updateSortParameters($params)
    {
        $this->sortField = $params["column"];
        $this->sortDirection = $params["direction"];
    }


    public function changePerPage($perPage)
    {
        $this->perPage = $perPage;
    }


    public function changeSearch($search)
    {
        $this->search = $search;
    }


    public function import()
    {

        // Check if the user has permission to perform the action
        if (!AccessControlService::checkPermission( 'import', $this->modelName)) {
            SweetAlertService::showError($this, "Error!", AccessControlService::MSG_PERMISSION_DENIED);
            return;
        }

        return (new DataImport($this->model, $this->columns))->import($this->file->path(), $this);
    }


    public function printTable()
    {
        // Check if the user has permission to perform the action
        if (!AccessControlService::checkPermission( 'print', $this->modelName)) {
            SweetAlertService::showError($this, "Error!", AccessControlService::MSG_PERMISSION_DENIED);
            return;
        }
        
        $this->dispatch('print-table-event');
    }



    public function render()
    {
        return view('core.views::data-tables.data-table-control', []);
    }


}


