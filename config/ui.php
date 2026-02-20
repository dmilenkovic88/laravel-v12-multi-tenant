<?php

return [
// UI configuration values for tables, cards, and other components
  'table' => [
    'table_padding' => 'py-2',
    'table_header' =>
        '[&_[data-flux-column]]:py-2 [&_[data-flux-column]]:border-y',
    'table_rows_padding' =>
        '[&_[data-flux-cell]]:py-1 [&_[data-flux-cell]]:border-dotted',
    'table_row_hover' =>
        'hover:bg-zinc-100 dark:hover:bg-zinc-800 transition duration-300',
    'table_row_cell_hover' =>
        'border-b-2 border-transparent hover:text-blue-700 hover:border-blue-700 transition-colors duration-1000',
    'table_responsive_collumn' =>
        'hidden sm:table-cell md:table-cell xl:table-cell',
    'table_responsive_collumn_xl' =>
        'hidden xl:table-cell',
    'table_responsive_collumn_xl_lg' =>
        'hidden lg:table-cell xl:table-cell',
    'table_responsive_collumn_xl_lg_md' =>
        'hidden md:table-cell lg:table-cell xl:table-cell',
    'table_responsive_collumn_xl_lg_md_sm' =>
        'hidden sm:table-cell md:table-cell lg:table-cell xl:table-cell',
  ],
];

