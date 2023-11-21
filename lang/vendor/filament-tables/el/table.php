<?php

return [

    'column_toggle' => [

        'heading' => 'Στήλες',

    ],

    'columns' => [

        'text' => [
            'more_list_items' => 'και :count περισσότερα',
        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Επιλέξτε/Ξε-επιλέξτε όλες τις εγγραφές για μαζικές ενέργειες.',
        ],

        'bulk_select_record' => [
            'label' => 'Επιλέξτε/Ξε-επιλέξτε εγγραφή :key για μαζικές ενέργειες.',
        ],

        'bulk_select_group' => [
            'label' => 'Επιλέξτε/Ξε-επιλέξτε γκρουπ :title για μαζικές ενέργειες.',
        ],

        'search' => [
            'label' => 'Αναζήτηση',
            'placeholder' => 'Αναζήτηση',
            'indicator' => 'Αναζήτηση',
        ],

    ],

    'summary' => [

        'heading' => 'Σύνοψη',

        'subheadings' => [
            'all' => 'Όλα :label',
            'group' => ':group σύνοψη',
            'page' => 'Αυτή η σελίδα',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Μέσος όρος',
            ],

            'count' => [
                'label' => 'Σύνολο',
            ],

            'sum' => [
                'label' => 'Άθροισμα',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Ολοκλήρωση της αναδιάταξης των εγγραφών',
        ],

        'enable_reordering' => [
            'label' => 'Αναδιάταξη εγγραφών',
        ],

        'filter' => [
            'label' => 'Φιλτράρισμα',
        ],

        'group' => [
            'label' => 'Ομαδοποίηση',
        ],

        'open_bulk_actions' => [
            'label' => 'Μαζικές ενέργειες',
        ],

        'toggle_columns' => [
            'label' => 'Ενεργοποίηση/Απενεργοποίση στηλών',
        ],

    ],

    'empty' => [

        'heading' => 'Καμία εγγραφή :model',

        'description' => 'Δημιουργήστε :model για να ξεκινήσετε.',

    ],

    'filters' => [

        'actions' => [

            'remove' => [
                'label' => 'Αφαίρεση φίλτρου',
            ],

            'remove_all' => [
                'label' => 'Αφαιρέστε όλα τα φίλτρα',
                'tooltip' => 'Αφαιρέστε όλα τα φίλτρα',
            ],

            'reset' => [
                'label' => 'Επαναφορά',
            ],

        ],

        'heading' => 'Φίλτρα',

        'indicator' => 'Ενεργά φίλτρα',

        'multi_select' => [
            'placeholder' => 'Όλα',
        ],

        'select' => [
            'placeholder' => 'Όλα',
        ],

        'trashed' => [

            'label' => 'Διαγραμμένες εγγραφές',

            'only_trashed' => 'Μόνο διαγραμμένες εγγραφές',

            'with_trashed' => 'Με διαγραμμένες εγγραφές',

            'without_trashed' => 'Χωρίς διαγραμμένες εγγραφές',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Γκρουπάρισμα βάση',
                'placeholder' => 'Γκρουπάρισμα βάση',
            ],

            'direction' => [

                'label' => 'Σείρα ταξινόμησης',

                'options' => [
                    'asc' => 'Αύξουσα',
                    'desc' => 'Φθίνουσα',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Σύρετε και αφήστε τις εγγραφές στη σειρά.',

    'selection_indicator' => [

        'selected_count' => '1 εγγραφή επιλέχθηκε|:count εγγραφές επιλέχθηκαν',

        'actions' => [

            'select_all' => [
                'label' => 'Eπιλογή και των :count εγγραφών',
            ],

            'deselect_all' => [
                'label' => 'Ακύρωση επιλογής όλων',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Tαξινόμηση βάση',
            ],

            'direction' => [

                'label' => 'Σείρα ταξινόμησης',

                'options' => [
                    'asc' => 'Αύξουσα',
                    'desc' => 'Φθίνουσα',
                ],

            ],

        ],

    ],

];
