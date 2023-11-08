<?php

use App\SshConfig\SshConfigParser;

it('returns an empty collection for unmatched SSH config content', function () {
    $parser = new SshConfigParser();
    $hosts = $parser->parseString("Random\nContent");

    expect($hosts)->toBeEmpty();
});
