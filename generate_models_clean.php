<?php

$models = [

'User' => [
    'extends' => 'Authenticatable',
    'traits' => ['HasFactory','Notifiable','HasRoles','SoftDeletes'],
    'fillable' => ['name','email','password','employee_id','title','department','phone','mobile','is_active','shop_floor_only','default_warehouse'],
],

'Customer' => ['fillable' => ['customer_number','name','email','phone','payment_terms']],
'Vendor' => ['fillable' => ['vendor_number','name','email','payment_terms']],
'Warehouse' => ['fillable' => ['code','name','address1','city','state','zip','country','is_default','is_active']],
'BinLocation' => ['fillable' => ['warehouse_id','code','zone','aisle','row','level']],
'Part' => ['fillable' => ['part_number','description','type','unit_price']],
'Inventory' => ['fillable' => ['part_id','warehouse_id','qty_on_hand']],
'Bom' => ['fillable' => ['parent_part_id','revision','status']],
'BomLine' => ['fillable' => ['bom_id','part_id','quantity']],
'Quote' => ['fillable' => ['quote_number','customer_id','status','quote_date','total']],

'Order' => [
    'fillable' => [
        'order_number','customer_id','quote_id','status',
        'order_date','due_date','total','paid'
    ],
],

'OrderLine' => ['fillable' => ['order_id','part_id','quantity','unit_price']],
'PurchaseOrder' => ['fillable' => ['po_number','vendor_id','status','po_date']],
'PoLine' => ['fillable' => ['purchase_order_id','part_id','quantity']],
'Receipt' => ['fillable' => ['receipt_number','vendor_id','receipt_date']],
'WorkOrder' => ['fillable' => ['wo_number','part_id','status','quantity']],
'Invoice' => ['fillable' => ['invoice_number','customer_id','total']],
'Payment' => ['fillable' => ['payment_number','amount','payment_date']],
];

$basePath = __DIR__ . '/app/Models/';

foreach ($models as $name => $config) {

    $extends = $config['extends'] ?? 'Model';
    $traits = $config['traits'] ?? ['HasFactory'];
    $fillable = $config['fillable'] ?? [];

    $imports = [
        "use Illuminate\\Database\\Eloquent\\Model;",
        "use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;"
    ];

    if ($extends === 'Authenticatable') {
        $imports[] = "use Illuminate\\Foundation\\Auth\\User as Authenticatable;";
        $imports[] = "use Illuminate\\Notifications\\Notifiable;";
        $imports[] = "use Spatie\\Permission\\Traits\\HasRoles;";
        $imports[] = "use Illuminate\\Database\\Eloquent\\SoftDeletes;";
    }

    $traitString = implode(', ', $traits);

    $fillableString = implode("',\n        '", $fillable);

    $content = "<?php

namespace App\Models;

" . implode("\n", $imports) . ";

class $name extends $extends
{
    use $traitString;

    protected \$fillable = [
        '$fillableString'
    ];
}
";

    file_put_contents($basePath . $name . '.php', $content);
}
