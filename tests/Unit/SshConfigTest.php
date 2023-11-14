<?php

it('returns the correct identity file path', function () {
    $result = $this->sshConfig->defaultIdentityFilePath();

    $expectedPath = $this->getTempFilePath('ssh-config').'/id_rsa';
    expect($result)->toBe($expectedPath);
});
