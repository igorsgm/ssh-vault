<?php

namespace App\Concerns;

use LaravelZero\Framework\Components\Logo\FigletString;
use Symfony\Component\Console\Question\ChoiceQuestion;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;

trait InteractsWithIO
{
    /**
     * Format input to textual table.
     *
     * @param  array  $headers
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $rows
     * @param  string  $tableStyle
     * @return void
     */
    public function table($headers, $rows, $tableStyle = 'default', array $columnStyles = [])
    {
        $this->line('');

        parent::table(
            collect($headers)->map(function ($header) {
                return "   <comment>$header</comment>";
            })->all(),
            collect($rows)->map(function ($row) {
                return collect($row)->map(function ($cell) {
                    return "   <options=bold>$cell</>";
                })->all();
            })->all(),
            'compact'
        );

        $this->line('');
    }

    /**
     * Display a "step" message.
     *
     * @param  string|array  $text
     * @return void
     */
    public function step($text)
    {
        $text = '<fg=blue>»»»</> <options=bold>'.$this->formatStepText($text).'</>';

        $this->line($text);
    }

    /**
     * Display a successful "step" message.
     *
     * @param  string|array  $text
     * @return void
     */
    public function successfulStep($text)
    {
        $text = '<fg=green>»»»</> <options=bold><info>✔ </info>'.$this->formatStepText($text).'</>';

        $this->line($text);
    }

    /**
     * Display a warn "step" message.
     *
     * @param  string|array  $text
     * @return void
     */
    public function warnStep($text)
    {
        $text = '<fg=yellow>»»»</> <options=bold><comment>⚠️</comment> '.$this->formatStepText($text).'</>';

        warning('<options=bold>'.$text.'</>');
    }

    /**
     * Display a ask "step" message.
     *
     * @param  string|array  $question
     * @param  string|null  $default
     * @return mixed
     */
    public function askStep($question, $default = null)
    {
        $question = $this->formatStepText($question);

        return $this->ask('<fg=yellow>»»</> <options=bold>'.$question.'</>', $default);
    }

    /**
     * Display a confirm "step" message.
     *
     * @param  string|array  $question
     * @param  bool  $default
     * @return bool
     */
    public function confirmStep($question, $default = false)
    {
        $question = $this->formatStepText($question);

        return $this->output->confirm('<fg=yellow>»»</> <options=bold>'.$question.'</>', $default);
    }

    /**
     * Display a secret "step" message.
     *
     * @param  array|string  $question
     * @return mixed
     */
    public function secretStep($question)
    {
        $question = $this->formatStepText($question);

        return $this->secret('<fg=yellow>»»</> <options=bold>'.$question.'</>');
    }

    /**
     * Formats a text step.
     *
     * @param  string|array  $text
     * @return string
     */
    protected function formatStepText($text)
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
        $figlet = new FigletString($text, array_merge(config('logo'), $options));
        $this->info(trim((string) $figlet, "\n"));
    }

    /**
     * Display a ask "step" message.
     *
     * @param  string|array  $question
     * @param  array  $choices
     * @param  string|null  $default
     * @return int
     */
    public function choiceStep($question, $choices, $default = null)
    {
        $question = $this->formatStepText($question);

        $question = new class('<fg=yellow>»»</> <options=bold>'.$question.'</>', $choices, $default) extends ChoiceQuestion
        {
            /**
             * Determines if the given array is associative.
             */
            public function isAssoc(array $array): bool
            {
                return true;
            }
        };

        return (int) $this->output->askQuestion($question);
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
     * Whether the command should skip the build process of the app or not.
     */
    public function isToSkipBuild(): bool
    {
        return $this->isOptionEnabled('no-build') || empty($this->option('no-build'));
    }

    /**
     * Retrieves the "quiet" flag based on the verbosity setting.
     */
    public function quietFlag(): string
    {
        return $this->isVerbose() ? '' : '--quiet';
    }
}
