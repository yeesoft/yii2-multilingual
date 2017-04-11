<?php

return [
    'custom_language_model' => [
        'modelClassName' => 'models\PostAdvanced',
        'data' => [
            'slug' => 'new_post_with_translations_model',
            'title_en_us' => 'Post With Translations EN Title',
            'title' => 'Post With Translations EN Title',
            'content_en_us' => 'Post With Translations EN Content',
            'content' => 'Post With Translations EN Content',
            'title_es' => 'Post With Translations ES Title',
            'content_es' => 'Post With Translations ES Content',
        ],
    ],
    'custom_params' => [
        'modelClassName' => 'models\PostCustomized',
        'data' => [
            'slug' => 'new_post_with_custom_params',
            'title_en_us' => 'Post With Custom Params EN Title',
            'title' => 'Post With Custom Params EN Title',
            'content_en_us' => 'Post With Custom Params EN Content',
            'content' => 'Post With Custom Params EN Content',
            'title_es' => 'Post With Custom Params ES Title',
            'content_es' => 'Post With Custom Params ES Content',
        ],
    ],
    'custom_table_name_param' => [
        'modelClassName' => 'models\PostCustomizedTableName',
        'data' => [
            'slug' => 'new_post_with_custom_table_name_param',
            'title_en_us' => 'Post With Custom Table Name EN Title',
            'title' => 'Post With Custom Table Name EN Title',
            'content_en_us' => 'Post With Custom Table Name EN Content',
            'content' => 'Post With Custom Table Name EN Content',
            'title_es' => 'Post With Custom Table Name ES Title',
            'content_es' => 'Post With Custom Table Name ES Content',
        ],
    ],
    'localized_prefix_param' => [
        'modelClassName' => 'models\PostWithLocalizedPrefix',
        'data' => [
            'slug' => 'new_post_with_localized_prefix',
            'title_en_us' => 'Post With Localized Prefix EN Title',
            'title' => 'Post With Localized Prefix EN Title',
            'content_en_us' => 'Post With Localized Prefix EN Content',
            'content' => 'Post With Localized Prefix EN Content',
            'title_es' => 'Post With Localized Prefix ES Title',
            'content_es' => 'Post With Localized Prefix ES Content',
        ],
    ],
];

