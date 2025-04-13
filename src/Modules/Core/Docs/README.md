# YAML Schema Guide for Code Generation

This guide explains the proper format for defining your schema in YAML files for code generation (migrations, models, config files). Using YAML provides a structured and maintainable way to define your data structures.

## File Structure

YAML files should be placed in the `app/Modules/Core/Yaml` directory (or a similar central location). You can organize them further into subdirectories by module (e.g., `app/Modules/Core/Yaml/Inventory/inventory_transaction.yaml`).

## YAML Structure

The top-level element in your YAML file should be `models`, which contains a list of your model definitions.

```yaml
models:
  ModelName:
    module: ModuleName
    fields:
      # ... field definitions
    fieldGroups:
      # ... field group definitions
    relations:
      # ... relationship definitions
    includes:
      # ... partial config includes
    # ... other model properties (e.g. hiddenFields, simpleActions, etc.)




Model Definition
Each model definition requires a module key (e.g. Inventory) and then contains the following keys:

module (Required)
The name of the module the model belongs to.

.YML FILE
module: Inventory



fields (Required)
A list of field definitions for the model. Each field is defined as follows:

.YML FILE
fieldName:
  type: dataType
  validation:
    - rule1
    - rule2:argument  # Example: - unique:users,email
  foreign:
    table: related_table
    column: related_column
    onDelete: onDeleteOption # Example: cascade, restrict, null




EXAMPLE:
transaction_id:
  type: string
  validation:
    - unique:inventory_transactions,transaction_id
uuid:
  type: uuid
  validation:
    - unique
transaction_date:
  type: timestamp
quantity:
  type: decimal
  validation:
    - precision:15,4 # Precision 15, scale 4
transaction_type_id:
  type: integer
  validation:
    - required
  foreign:
    table: transaction_types
    column: id
    onDelete: restrict



    Data Types
Common data types include: string, integer, uuid, timestamp, decimal, text, boolean, date, datetime, bigInteger, smallInteger, unsignedInteger, float, double.

Validation Rules
Validation rules are defined as a list. You can include standard Laravel validation rules (e.g., required, nullable, email, unique, min, max, string, integer, numeric, boolean, date, after, before) and custom rules.  For unique, specify the table and column (e.g., unique:users,email). For precision with decimal, specify the precision and scale (e.g., precision:15,4).  Multiple validation rules can be applied to a single field.

Foreign Key Constraints
Foreign key constraints are defined using the foreign key and include the table, column, and onDelete options. Valid onDelete options are: cascade, restrict, null.  The table and column specify the related table and column.

fieldGroups (Optional)
Defines groups of fields for organizing forms or views. Each group has a title, groupType (e.g., hr for a horizontal rule), and a list of fields.

YAML

fieldGroups:
  - title: Basic Info
    groupType: hr
    fields:
      - code
      - name
      - description
relations (Optional)
Defines model relationships (e.g., belongsTo, hasMany, belongsToMany, hasOne). This section is used for generating model relationships.

YAML

relations:
  transactionType: belongsTo:App\Modules\Inventory\Models\TransactionType:transaction_type_id
  items: belongsToMany:App\Modules\Item\Models\Item:inventory_transaction_item:inventory_transaction_id:item_id # Example belongsToMany
The format is relationName: relationType:RelatedModel:foreignKey (or relationName: relationType:PivotModel:foreignKey1:foreignKey2 for belongsToMany).  The full namespace of the related model should be included.

includes (Optional)
Specifies partial config files to include. These files should be placed in the app/Modules/ModuleName/Data directory and should return a PHP array containing configuration data.  These partials are merged with the YAML configuration.  Partials defined within fieldDefinitions will override those defined at the top level.

YAML

includes:
  - /Partials/Fields/item_hidden_fields.php

fields:
  status_id:
    partial: /Partials/Fields/belongs_to_status_field.php # Partial within fields
Other Model Properties (Optional)
You can include other model properties like hiddenFields, simpleActions, isTransaction, dispatchEvents, and controls. These will be added to the generated config files.

YAML

hiddenFields:
  onTable: [uuid]
simpleActions: [show, edit]
controls:
  addButton: true
isTransaction: true
dispatchEvents: true
Example YAML File
YAML

models:
  InventoryTransaction:
    module: Inventory
    fields:
      transaction_id:
        type: string
        validation:
          - unique:inventory_transactions,transaction_id
      transaction_date:
        type: timestamp
      quantity:
        type: decimal
        validation:
          - precision:15,4
      transaction_type_id:
        type: integer
        validation:
          - required
        foreign:
          table: transaction_types
          column: id
          onDelete: restrict
      item_id:
        type: integer
        validation:
          - required
        foreign:
          table: items
          column: id
          onDelete: cascade
      status_id:
        partial: /Partials/Fields/belongs_to_status_field.php
    fieldGroups:
      - title: Transaction Details
        groupType: hr
        fields:
          - transaction_id
          - transaction_date
      # ... other field groups
    relations:
      transactionType: belongsTo:App\Modules\Inventory\Models\TransactionType:transaction_type_id
      items: belongsToMany:App\Modules\Item\Models\Item:inventory_transaction_item:inventory_transaction_id:item_id
    includes:
      - /Partials/Fields/transaction_hidden_fields.php
    hiddenFields:
      onTable: [uuid]
    simpleActions: [show, edit]
    controls:
      addButton: true
    isTransaction: true
    dispatchEvents: true


```
