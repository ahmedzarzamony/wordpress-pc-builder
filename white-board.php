<?php


$args = [
    'post_type' => 'products',
    'tax_query' => [
        [
            'taxonomy' => 'purpose',                //(string) - Taxonomy.
            'field' => 'name',                    //(string) - Select taxonomy term by ('id' or 'slug')
            'terms' => 'gaming',    //(int/string/array) - Taxonomy term(s).
            'operator' => '='                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
        ],
        [
            'taxonomy' => 'extra',                //(string) - Taxonomy.
            'field' => 'name',                    //(string) - Select taxonomy term by ('id' or 'slug')
            'terms' => 'VR',    //(int/string/array) - Taxonomy term(s).
            'operator' => '='                    //(string) - Operator to test. Possible values are 'IN', 'NOT IN', 'AND'.
        ]
    ],
    'meta_query' => [
        [
            'key' => 'price',
            'value' => 500,
            'type' => 'NUMERIC',
            'compare' => '<='
        ]
    ]  
];

select (price) from products group by component.name 

select price from products where price <= 500 where tax_purpose=gaming and tax_extra=vr group_by tax_component


select (cpu+gpu+mb+ram+hdd) where price <= 500

product[
    id => id,
    name => name,
    price => price,
    com_group => ram,
    com_is_gpu => true,
    com_is_cpu => false,
    brand_name => kingston,
    com_is_gpu => true,
    brand => [
        name => brand_name
    ],
    purpse => purpse,
    extra => extra,

]