<?php

return [
    /**
     * https://symfony.com/doc/current/workflow.html
     * The marking store type could be "multiple_state" or "single_state". A single state marking store does not support a model being on multiple places at the same time. This means a "workflow" must use a "multiple_state" marking store and a "state_machine" must use a "single_state" marking store. Symfony configures the marking store according to the "type" by default, so it's preferable to not configure it.
     * A single state marking store uses a string to store the data. A multiple state marking store uses an array to store the data.
     */

    'straight'   => [
        'type'          => 'state_machine', // 'workflow' or 'state_machine'
        'marking_store' => [
            'type'      => 'single_state',// 'multiple_state' or 'single_state'
            'property'  => ['marking']
        ],
        'initial_marking' => 'draft',
        'supports'      => ['App\Models\DemoPost'],
        'places'        => ['draft', 'review', 'rejected', 'published'],
        'transitions'   => [
            'to_review' => [
                'from' => 'draft',
                'to'   => 'review'
            ],
            'publish' => [
                'from' => 'review',
                'to'   => 'published'
            ],
            'reject' => [
                'from' => 'review',
                'to'   => 'rejected'
            ]
        ],
    ]
];
