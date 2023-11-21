<?php

return [

    'label' => 'Pagination navigation',

    'overview' => '{1} Showing 1 result|[2,*] Showing :first to :last of :total results',

    'fields' => [

        'records_per_page' => [

            'label' => 'Ανα σελίδα',

            'options' => [
                'all' => 'Όλα',
            ],

        ],

    ],

    'actions' => [

        'go_to_page' => [
            'label' => 'Πήγαινε στην σελίδα :page',
        ],

        'next' => [
            'label' => 'Επόμενη',
        ],

        'previous' => [
            'label' => 'Προηγούμενη',
        ],

    ],

];
