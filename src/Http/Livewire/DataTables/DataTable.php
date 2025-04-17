<?php

namespace QuickerFaster\CodeGen\Http\Livewire\DataTables;

use Livewire\Component;

use App\Modules\Core\Contracts\DataTable\CellFormatterInterface;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Modules\Core\Traits\DataTable\DataTableFieldsConfigTrait;



class DataTable extends Component
{
    use WithPagination;


    ///////////////// DEFINED BY THE PARENT CLASS ///////////////
    public $model;
    public $controls;
    public $columns;
    public $fieldDefinitions;
    public $multiSelectFormFields;
    public $simpleActions;
    public $moreActions;
    public $hiddenFields;
    public $modelName;
    public $moduleName;

    ///////////////// DEFINED BY THIS CLASS ///////////////
    public $visibleColumns;
    public $selectedColumns;
    public $sortField; // Default sort field
    public $sortDirection = 'asc'; // Default sort direction
    public $perPage;
    public $selectedRows = [];
    public $selectAll;
    public $search = '';

    public $queryFilters = [];


    protected $listeners = [
        "perPageEvent" => "changePerPage",
        "searchEvent" => "changeSearch",
        "showHideColumnsEvent" => "showHideColumns",
        'applyFilterEvent' => 'applyFilters',

        "recordDeletedEvent" => "resetSelection",
        'recordSavedEvent' => '$refresh',

    ];





    public function mount()
    {

    }





    public function resetSelection()
    {
        $this->selectedRows = [];
        $this->selectedColumns = null;
        $this->selectAll = null;
    }


    public function formartTableCellData($row, $column, $value) {

        if (isset($this->fieldDefinitions[$column]["formatter"]))
        {
            $formatter = $this->fieldDefinitions[$column]["formatter"];
            if (is_callable($formatter)) {
                return $formatter($value, $row);
            } else if (class_exists($formatter) &&
                in_array(CellFormatterInterface::class, class_implements($formatter))) {
                return $formatter::format($value, $row);
            } else {
                return $value;
            }

        } else {
            return $value;
        }
    }



   public function sortColumn($field)
   {

       if ($this->sortField === $field) {
           // If already sorting by this field, toggle the direction
           $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
       } else {
           // Otherwise, set to sort by this field in ascending order
           $this->sortField = $field;
           $this->sortDirection = 'asc';
           $this->dispatch('sortColumnEvent', ["column" => $field, "direction" => $this->sortDirection]);

       }
   }



   public function changePerPage($perPage)
   {
        $this->perPage = $perPage;
        $this->dispatch("changePerPageEvent", $perPage);
   }


   public function changeSearch($search)
   {

        $this->search = $search;
        $this->dispatch("changeSearchEvent", $search);
   }


    public function showHideColumns($selectedColumns)
    {
        // Update visibleColumns with the selectedColumns
        $this->visibleColumns = $selectedColumns;
    }


    public function applyFilters($filters)
    {

        $this->queryFilters = [];

        foreach ($filters as $field => $filter) {
            if (!isset($filter['operator']) || !isset($filter['value']))
                continue;

            $operator = trim($filter['operator']);
            $value = trim($filter['value']);

            if ($operator !== '' && $value !== '') {
                $this->queryFilters[] = [$field, $operator, $value];
            }
        }


}




    /////////////////// BULK ACTION ////////////////////////

    /************ Select All Checbox Toggle ****************/
    public function updatedSelectAll($value)
    {

        if ($value) {
            $query = app($this->model)->newQuery();

            // Apply search filters
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    foreach ($this->columns as $column) {
                        $q->orWhere($column, 'like', '%' . $this->search . '%');
                    }
                });
            }

            // Apply sorting
            $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
            ;
            $this->selectedRows = $query->pluck('id')->toArray();

        } else {
            $this->selectedRows = [];
        }

        $this->dispatch("toggleRowsSelectedEvent", $this->selectedRows);
    }



    /***************** Single Checbox Toggle ****************/
    public function toggleRowSelected() {
        $this->dispatch("toggleRowsSelectedEvent", $this->selectedRows);
    }



    ///////// Trigger Events /////////////
    public function editRecord($id, $model) {
        $this->dispatch("openEditModalEvent", $id, $model);
    }



    public function showDetail($id) {
        $this->dispatch("openShowItemDetailModalEvent", $id);
    }



    public function deleteRecord($id) {
        $this->dispatch("confirmDeleteEvent", $id);
    }



    // Redirect to the link
    public function openLink($route, $id)
    {
        $modelName = Str::singular(strtolower($this->modelName));
        return redirect()->route($route, [$modelName => $id]);
    }



    /////////////// RENDERING //////////////////////
    public function render()
    {

        // Get the hidden fields on query
        $hiddenFields = $this->hiddenFields["onQuery"];//config( "$moduleName.$modelName.hiddenFields.onQuery") ?? [];

       $modelClass = '\\' . ltrim($this->model, '\\'); // Ensure the model has a leading backslash
       $query = (new $modelClass)->newQuery();


        // Apply search filters
        if (!empty($this->search)) {
            $query?->where(function ($q) use($hiddenFields) {
                foreach ($this->fieldDefinitions as $fieldName => $fieldDefinition) {

                   // Skip the hidden fields
                   if (in_array($fieldName, $hiddenFields))
                        continue;

                    // Check if the field has a relationship
                    if (isset($fieldDefinition['relationship'])) {

                        $relationship = $fieldDefinition['relationship'];

                        // Check the dependent fields
                        if (!isset($relationship['type'])
                            || !isset($relationship['display_field'])
                            || !isset($relationship['dynamic_property']))
                            continue;

                        $relationshipType = $relationship['type'];
                        //$relatedModel = $relationship['model'];
                        $displayField = explode(".", $relationship['display_field']); // Hagndling nested display_name eg user.name
                        $displayField = count($displayField) > 1? "id": $displayField[0]; // id should be replace with a kind of staffProfile()->user->name

                        $dynamicProperty = $relationship['dynamic_property'];

                        // Handle belongsTo, hasMany, belongsToMany relationships
                        if (in_array($relationshipType, ['belongsTo', 'hasMany', 'belongsToMany'])) {
                            $q->orWhereHas($dynamicProperty, function ($relatedQuery) use ($displayField) {
                                $relatedQuery->where($displayField, 'like', '%' . $this->search . '%');
                            });
                        }
                    } else {
                        // For non-relationship fields, do a regular where clause
                        $q->orWhere($fieldName, 'like', '%' . $this->search . '%');
                    }
                }
            });
        }



        // Apply filters globally to the query
        if (is_array($this->queryFilters)) {
            foreach ($this->queryFilters as $filter) {
                $column = "";

                // Ensure that the filter array has three elements eg ["age", ">", "10"]
                if (is_array($filter) && count($filter) === 3 && is_string($filter[0]) && is_string($filter[1])) {
                    [$field, $operator, $value] = $filter;

                    // For relationship dot operator & colomn is expected eg. [item_id.name]
                    if (str_contains($field, "."))
                         [$field, $column] = explode(".",$field);

                    // Check relationship and other required conditions
                    if (isset($this->fieldDefinitions[$field]["relationship"])
                        && isset($this->fieldDefinitions[$field]["relationship"]["type"])
                        && isset($this->fieldDefinitions[$field]["relationship"]["dynamic_property"])
                        && !empty($column)
                    ) {

                        // Filter on the relationship dynamic property defined inside the [model class]
                        $dynamic_property = $this->fieldDefinitions[$field]["relationship"]["dynamic_property"];
                        $query->whereHas($dynamic_property, function ($query) use($column, $operator, $value) {
                            $query->where($column, $operator, $value);
                        });//->with($dynamic_property)->get(); // Earger loading optional

                    } else { // Column without a relationship

                        if ($operator === 'in') {
                            $query->whereIn($field, $value);
                        } else {
                            $query->where($field, $operator, $value);
                        }
                    }

                }
            }
        }




        // Apply sorting
        if ($this->sortField)
            $query?->orderBy($this->sortField, $this->sortDirection);

        $data = $query?->paginate($this->perPage);

        return view('core.views::data-tables.data-table', [
            'data' => $data
        ]);


    }





}


