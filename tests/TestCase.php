<?php

namespace Tests;

use Igorsgm\SshVault\Concerns\HostsCommandHelper;
use Igorsgm\SshVault\SshConfig\SshConfig;
use Illuminate\Support\Facades\File;
use Laravel\Prompts\Prompt;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;
use Tests\Traits\WithTmpFiles;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        HostsCommandHelper,
        WithTmpFiles;

    public SshConfig $sshConfig;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Prompt::fallbackWhen(true);
        $this->initSshConfig();
    }

    protected function tearDown(): void
    {
        // Clean up the temporary SSH config file
        $this->deleteTempDirectory();

        parent::tearDown();
    }

    public function initSshConfig()
    {
        $this->initializeTempDirectory(__DIR__.'/temp');
        // Set the path to the temporary SSH config for the duration of the test
        config(['app.ssh-config-path' => $this->getTempFilePath('ssh-config')]);

        $fixtureContent = File::get(base_path('tests/Fixtures/ssh-config'));
        $this->makeTempFile('ssh-config', $fixtureContent);

        $this->sshConfig = app(SshConfig::class);
    }
}
