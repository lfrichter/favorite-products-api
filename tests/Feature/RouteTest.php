<?php

uses(Tests\TestCase::class);

it('can list routes', function () {
    $this->artisan('route:list')->expectsOutputToContain('api/users')->assertExitCode(0);
});
