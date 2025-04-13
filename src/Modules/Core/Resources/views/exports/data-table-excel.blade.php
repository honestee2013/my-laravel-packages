


<table>
    <thead>
        <tr>
            @foreach ($data->first()->toArray() as $column => $value)
            @if(in_array($column, $columns))

                @if (isset($fieldDefinitions[$column]['label']))
                    <th>{{ ucwords($fieldDefinitions[$column]['label']) }}</th>
                @else
                    <th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>
                @endif
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)

            <tr>
                @foreach ($row->toArray() as $column => $value)
                    @if(in_array($column, $columns))

                        @if ($multiSelectFormFields && in_array($column, array_keys($multiSelectFormFields)))
                            <td>{{ str_replace(',', ', ', str_replace(['[', ']', '"'], '', $value)) }}</td>
                        @elseif (in_array($column, ['image', 'photo', 'picture']))
                            <td></td>
                        @elseif (isset($fieldDefinitions[$column]) && isset($fieldDefinitions[$column]['relationship']))
                            <!---- Has Many Relationship ---->
                            @if (isset($fieldDefinitions[$column]['relationship']['type']) &&
                                    $fieldDefinitions[$column]['relationship']['type'] == 'hasMany')
                                implode(', ',
                                $row->{$fieldDefinitions[$column]['relationship']['dynamic_property']}
                                ->pluck($fieldDefinitions[$column]['relationship']['display_field'])->toArray()
                                )

                                <!---- Belongs To Many Relationship ---->
                            @elseif (isset($fieldDefinitions[$column]['relationship']['type']) &&
                                    $fieldDefinitions[$column]['relationship']['type'] == 'belongsToMany')
                                implode(', ',
                                $row->{$fieldDefinitions[$column]['relationship']['dynamic_property']}
                                ->pluck($fieldDefinitions[$column]['relationship']['display_field'])->toArray()
                                )
                            @else
                                <!---- Belongs Relationship ---->
                                @php
                                    $dynamic_property = $fieldDefinitions[$column]['relationship']['dynamic_property'];
                                    $displayField = $fieldDefinitions[$column]['relationship']['display_field'];
                                    $value = optional($row->{$dynamic_property})->$displayField;

                                @endphp

                                <td>{{ $value }}</td>
                            @endif
                        @else <!---- Ends Relationship Check---->
                            @if (!is_array($value))
                                <td>{{ $value }}</td>

                            @endif

                        @endif

                        @php
                            $colCount++;
                        @endphp

@endif

                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
