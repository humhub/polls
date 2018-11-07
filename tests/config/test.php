<?php

return [
    #'humhub_root' => 'D:\codebase\humhub\v1.2-dev',
    'modules' => ['polls'],
    'fixtures' => [
        'default',
        'polls' => \tests\codeception\fixtures\PollFixture::class
    ]
];



