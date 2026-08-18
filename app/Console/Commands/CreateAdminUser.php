<?php

namespace App\Console\Commands;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdminUser extends Command
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--name= : The name of the admin}
                            {--email= : The email address to sign in with}
                            {--password= : The password (prompted for when omitted)}
                            {--promote : Grant admin rights to the account if the email already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user, or promote an existing user to admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email') ?? $this->promptFor(
            'email',
            fn (): string => text(
                label: 'Email address',
                required: true,
                validate: fn (string $value): ?string => $this->firstError(['email' => $value], ['email' => $this->emailPromptRules()]),
            ),
        );

        if ($email === null) {
            return self::FAILURE;
        }

        if ($existing = User::query()->where('email', $email)->first()) {
            return $this->promote($existing);
        }

        $name = $this->option('name') ?? $this->promptFor(
            'name',
            fn (): string => text(
                label: 'Name',
                required: true,
                validate: fn (string $value): ?string => $this->firstError(['name' => $value], ['name' => $this->nameRules()]),
            ),
        );

        $plainPassword = $this->option('password') ?? $this->promptFor(
            'password',
            fn (): string => password(
                label: 'Password',
                required: true,
                validate: fn (string $value): ?string => $this->firstError(
                    ['password' => $value, 'password_confirmation' => $value],
                    ['password' => $this->passwordRules()],
                ),
            ),
        );

        if ($name === null || $plainPassword === null) {
            return self::FAILURE;
        }

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $plainPassword, 'password_confirmation' => $plainPassword],
            ['name' => $this->nameRules(), 'email' => $this->emailRules(), 'password' => $this->passwordRules()],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $plainPassword,
            'is_admin' => true,
        ]);

        // The admin panel sits behind the `verified` middleware, so an account
        // made from the console would otherwise be locked out of the panel it
        // was just given rights to.
        $user->forceFill(['email_verified_at' => now()])->save();

        $this->components->info("Admin {$user->email} created.");

        return self::SUCCESS;
    }

    /**
     * Grant admin rights to an account that already exists.
     */
    protected function promote(User $user): int
    {
        if ($user->isAdmin()) {
            $this->components->warn("{$user->email} is already an admin.");

            return self::SUCCESS;
        }

        $allowed = $this->option('promote') || ($this->input->isInteractive() && confirm(
            label: "{$user->email} already exists. Grant this account admin rights?",
            default: false,
        ));

        if (! $allowed) {
            $this->components->error("{$user->email} already exists. Pass --promote to grant it admin rights.");

            return self::FAILURE;
        }

        $user->forceFill([
            'is_admin' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->components->info("{$user->email} is now an admin.");

        return self::SUCCESS;
    }

    /**
     * Prompt for a value, or explain which option is missing when the command
     * is running without a terminal to prompt on.
     */
    protected function promptFor(string $option, callable $prompt): ?string
    {
        if (! $this->input->isInteractive()) {
            $this->components->error("The --{$option} option is required when running without interaction.");

            return null;
        }

        return $prompt();
    }

    /**
     * Email rules for the prompt. Uniqueness is deliberately left out here so an
     * existing address reaches the promote path instead of being rejected.
     *
     * @return array<int, mixed>
     */
    protected function emailPromptRules(): array
    {
        return ['required', 'string', 'email', 'max:255'];
    }

    /**
     * Validate one field and return the first error message, if any.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $rules
     */
    protected function firstError(array $data, array $rules): ?string
    {
        $validator = Validator::make($data, $rules);

        return $validator->fails() ? (string) $validator->errors()->first() : null;
    }
}
