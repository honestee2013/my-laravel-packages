<?php

namespace App\Modules\Core\Http\Livewire\DataTables;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Modules\Log\Models\UserActivityLog;
use App\Modules\Core\Services\Exports\DataExport;
use App\Modules\Core\Services\Imports\DataImport;


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



    // Apply Bulk action on the selected rows
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

        if ($rows == "selected")
            $data["data"] = $this->getSelectedExportData();
        else
            $data["data"] = $this->getExportData();



        $data['columns'] = $this->visibleColumns;
        $data['fieldDefinitions'] = $this->fieldDefinitions;
        $data['multiSelectFormFields'] = $this->multiSelectFormFields;


        // File name
        $fileName = "";
        if ($this->model)
            $fileName = "-" . class_basename($this->model);
        $fileName = "Selected" . $fileName . "-Record";

        $data['fileName'] = $fileName;
        $data['fileType'] = $fileType;

        $result = (new DataExport($data))->export();

        return $result;
    }




    private function getSelectedExportData()
    {

       //dd( UserActivityLog::find(1)->with('user')->first());//->user->name;

        // Adjust the query to include relationships if necessary
        $query = $this->model::whereIn('id', $this->selectedRows);

        // Prepare to exclude dynamic_property fields AS THE DO NOT EXIST ON TABLE SCHEMA
        $dynamicProperties = [];
        foreach ($this->fieldDefinitions as $column => $definition) {
            if (!in_array($column, $this->visibleColumns)) // Skip hidden field
                continue;

            if (isset($definition['relationship'])) {


                $relationship = $definition['relationship'];
                $dynamicProperty = $relationship['dynamic_property'];

                // Load the relationship to include it in the export
                $query->with($dynamicProperty);
                $dynamicProperties[] = $dynamicProperty;
            }
        }

        $visibleColumns = array_diff($this->visibleColumns, $this->hiddenFields['onQuery']);
        $data = $query->get(array_diff($visibleColumns, $dynamicProperties));
        return $data;
    }



    // Query the record for data to be exported
    private function getExportData()
    {

        // Get the hidden fields on query
        //$hiddenFields = $this->hiddenFields["onQuery"];//config( "$moduleName.$modelName.hiddenFields.onQuery") ?? [];
        $visibleColumns = [];
        foreach ($this->fieldDefinitions as $column => $definition) {
            // Skip the hidden fields
            if (!in_array($column, $this->hiddenFields["onQuery"]))
                $visibleColumns[] = $column;
        }


       $modelClass = '\\' . ltrim($this->model, '\\'); // Ensure the model has a leading backslash
       $query = (new $modelClass)->newQuery();

        return $this->model::query()

            ->when($this->search, function ($query) use($visibleColumns)  {
                foreach ($this->fieldDefinitions as $column => $definition) {

                    //if (in_array($column, $visibleColumns)) {

                        // Check if the column is part of a relationship
                        if (isset($definition['relationship'])) {
                            $relationship = $definition['relationship'];

                            // Check the dependent fields
                            if (
                                !isset($relationship['type'])
                                || !isset($relationship['display_field'])
                                || !isset($relationship['dynamic_property'])
                            )
                                continue;

                            $relationshipType = $relationship['type'];
                            //$relatedModel = $relationship['model'];
                            $displayField = $relationship['display_field'];
                            //$foreignKey = $relationship['foreign_key'];
                            $dynamicProperty = $relationship['dynamic_property'];

                            // Handle belongsTo, hasMany, belongsToMany relationships
                            if (in_array($relationshipType, ['belongsTo', 'hasMany', 'belongsToMany'])) {
                                $query->orWhereHas($dynamicProperty, function ($relatedQuery) use ($displayField) {
                                    $relatedQuery->where($displayField, 'like', '%' . $this->search . '%');
                                });
                            }
                        } else {
                            // For non-relationship fields, do a regular where clause
                            $query->orWhere($column, 'like', '%' . $this->search . '%');
                        }
                    //}

                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->take($this->perPage);//  // Limit the number of records to the per-page value
            //->get($visibleColumns);
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
        return (new DataImport($this->model, $this->columns))->import($this->file->path(), $this);
    }



    public function render()
    {
        return view('core.views::data-tables.data-table-control', []);
    }


}


