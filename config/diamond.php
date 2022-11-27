<?php

return [
    /*
     | --------------------------------------------------------------------
     | Base directory config
     | --------------------------------------------------------------------
     |
     | Base directory for your Domain Driven Design live
     | the default value is "src" you can change it, whenever you want
     |
     */
    'base_directory' => 'src/',

    /*
     | --------------------------------------------------------------------
     | Domain Driven Design structures
     | --------------------------------------------------------------------
     |
     | We made a configuration for the structure, so you can add or change the name
     | anything you want. * please only change the value not the key,
     | because the key as identifier, so we not recommend to change the identifier
     |
     */
    'structures' => [
        'infrastructure' => 'Infrastructure',
        'domain' => 'Domain',
    ],
];
