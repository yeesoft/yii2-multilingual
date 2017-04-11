<?php

return [
    'posts' => [
            [
            'id' => 1,
            'slug' => 'title',
            'translations' => [
                0 => [
                    'id' => 1,
                    'owner_id' => 1,
                    'language' => 'en-US',
                    'title' => 'EN-US Title',
                    'content' => 'EN-US Content',
                ],
                1 => [
                    'id' => 2,
                    'owner_id' => 1,
                    'language' => 'es',
                    'title' => 'ES Title',
                    'content' => 'ES Content',
                ],
            ],
        ],
    ],
    'new_post' => [
        'id' => 2,
        'slug' => 'new_post',
        'translations' => [
            0 => [
                'id' => 3,
                'owner_id' => 2,
                'language' => 'en-US',
                'title' => 'New Post EN Title',
                'content' => 'New Post EN Content',
            ],
        ],
    ],
    'new_post_with_translations' => [
        'id' => 3,
        'slug' => 'new_post_with_translations',
        'translations' => [
            0 => [
                'id' => 4,
                'owner_id' => 3,
                'language' => 'en-US',
                'title' => 'Post With Translations EN Title',
                'content' => 'Post With Translations EN Content',
            ],
            1 => [
                'id' => 5,
                'owner_id' => 3,
                'language' => 'es',
                'title' => 'Post With Translations ES Title',
                'content' => 'Post With Translations ES Content',
            ],
        ],
    ],
    'updated_post' => [
        'id' => 2,
        'slug' => 'updated_post',
        'translations' => [
            0 => [
                'id' => 3,
                'owner_id' => 2,
                'language' => 'en-US',
                'title' => 'Updated Post EN Title',
                'content' => 'Updated Post EN Content',
            ],
            1 => [
                'id' => 6,
                'owner_id' => 2,
                'language' => 'es',
                'title' => 'Updated Post ES Title',
                'content' => 'Updated Post ES Content',
            ],
        ],
    ],
    'post_localized_en' => [
        'id' => 3,
        'slug' => 'new_post_with_translations',
        'title' => 'Post With Translations EN Title',
        'content' => 'Post With Translations EN Content',
    ],
    'post_localized_es' => [
        'id' => 3,
        'slug' => 'new_post_with_translations',
        'title' => 'Post With Translations ES Title',
        'content' => 'Post With Translations ES Content',
    ],
];

