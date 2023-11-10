<?php

use App\Commands\Hosts\RawCommand;

it('displays raw content of SSH connections', function () {
    $this->artisan(RawCommand::class)
        ->expectsOutput($this->sshConfig->content())
        ->assertOk();
});
