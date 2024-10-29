<?php

namespace App\Concerns;

use LaravelZero\Framework\Components\Logo\FigletString;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

/**
 * @codeCoverageIgnore
 */
trait InteractsWithIO
{
    /**
     * Display a "step" message.
     */
    public function step(string $text): void
    {
        $text = '<fg=blue>»»»</> <options=bold>'.$this->formatStepText($text).'</>';

        $this->line($text);
    }

    /**
     * Display a successful "step" message.
     */
    public function successfulStep(string $text): void
    {
        $text = '<fg=green>»»»</> <options=bold><info>✔ </info>'.$this->formatStepText($text).'</>';

        $this->line($text);
    }

    /**
     * Display a warn "step" message.
     */
    public function warnStep(string $text): void
    {
        $text = '<fg=yellow>»»»</> <options=bold><comment>⚠️</comment> '.$this->formatStepText($text).'</>';

        warning('<options=bold>'.$text.'</>');
    }

    /**
     * Formats a text step.
     *
     * @param  string|array  $text
     */
    protected function formatStepText($text): string
    {
        $parameters = [];

        if (is_array($text)) {
            $parameters = $text;
            $text = array_shift($text);
            unset($parameters[0]);
        }

        return sprintf($text, ...collect($parameters)->map(function ($parameter) {
            return '<comment>['.$parameter.']</comment>';
        })->values()->all());
    }

    /**
     * Display a task "step" title.
     */
    public function formatTaskTitle(string $text): string
    {
        return '<fg=blue>»»»</> <options=bold>'.$this->formatStepText($text).'</>';
    }

    /**
     * Write a string as error output. Overriding the default error command message.
     *
     * @param  string  $text
     * @param  int|string|null  $verbosity
     */
    public function error($text, $verbosity = null): void
    {
        error('<bg=red;fg=white> ERROR </> <fg=red>'.$text.'</>');
    }

    /**
     * Write a string as a note output. Overriding the default note command message.
     */
    public function note(string $text): void
    {
        note('<fg=gray>'.$text.'</>');
    }

    /**
     * Write a string as a note output, used under a step as a hint.
     */
    public function hintStep(string $text): void
    {
        note("\r\033[1A\r    <fg=gray>".$text.'</>');
    }

    /**
     * Convert text to Figlet (ASC II) format and display it.
     */
    public function figlet(string $text, array $options = []): void
    {
        $figlet = new \App\Overrides\FigletString($text, array_merge(config('figlet'), $options));
        $this->info(trim((string) $figlet, "\n"));
    }

    /**
     * Confirm a sensitive action from the user when not running the command from the app.
     */
    public function confirmAction(string $hint = ''): bool
    {
        if ($this->isCalledFromApp()) {
            return true;
        }

        return confirm(
            label: 'Are you sure you want to proceed with this action?',
            hint: $hint,
        );
    }

    /**
     * Returns the option value for a given option name or null if the option is not present.
     */
    public function getOption(string $name): mixed
    {
        return $this->input->getOption($name) ?? null;
    }

    /**
     * Returns the argument value for a given argument name or null if the argument is not present.
     */
    public function getArgument(string $name): mixed
    {
        return $this->hasArgument($name) ? $this->argument($name) : null;
    }

    /**
     * Determines if the specified option is enabled (evaluates to true).
     */
    public function isOptionEnabled(string $option): bool
    {
        return filter_var($this->getOption($option), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Checks if the verbose option is enabled.
     */
    public function isVerbose(): bool
    {
        return $this->isOptionEnabled('verbose');
    }

    /**
     * Whether the command is called from this CLI app or not
     */
    public function isCalledFromApp(): bool
    {
        return $this->isOptionEnabled('called-from-app');
    }

    /**
     * Checks if the 'fast' option is present and enabled in the current command.
     */
    public function isFast(): bool
    {
        return $this->isOptionEnabled('fast');
    }

    /**
     * Retrieves the "quiet" flag based on the verbosity setting.
     */
    public function quietFlag(): string
    {
        return $this->isVerbose() ? '' : '--quiet';
    }
}
