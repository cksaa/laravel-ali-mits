<?php
return [
    "access_key_id" => env("ACCESS_KEY_ID"),
    "access_key_secret" => env("ACCESS_KEY_SECRET"),
    "region" => env("REGION_ID", 'cn-hangzhou'),
    "bucket" => env("OSS_BUCKET", null),
    "output_bucket" => env("OSS_BUCKET"),
    "oss_location" => env("OSS_LOCATION", 'oss-cn-hangzhou'),
    "pipeline_id" => env("PIPELINE_ID", null),
    "transcode" => [
        "2k" => env('2k_template_id'),
        "4k" => env('4k_template_id'),
    ]
];
