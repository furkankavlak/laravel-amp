<?php

return [

    'prefix' => null,

    'suffix' => 'amp',

    // (optional)
    'view-affix' => '-amp',

    // (optional)
    'view-bool-name' => 'hasAmpUrl',

    // Use non-amp view if affixed view does not exists.
    'view-fallback' => false,

    'layouts' => [
        'amp::tag'
    ]
];
