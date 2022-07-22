<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the https://github.com/tymondesigns/jwt-auth/blob/develop/LICENSE
 * This is a copy file of https://github.com/tymondesigns/jwt-auth/blob/develop/src/Console/JWTGenerateSecretCommand.php
 *
 * The modifications below are in :
 * - namespace changes for code to run
 * - change console command signature from "jwt:secret" to "jwt:generate-key"
 * - message with the word "jwt-auth" changed to "jwt" to resolve library usage confusion
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JWTGenerateSecretCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    // Original : protected $signature = 'jwt:secret
    protected $signature = 'jwt:generate-key 
        {--s|show : Display the key instead of modifying files.}
        {--always-no : Skip generating key if it already exists.}
        {--f|force : Skip confirmation when overwriting an existing key.}';

    /**
     * The console command description.
     *
     * @var string
     *
     * Original : protected $description = 'Set the JWTAuth secret key used to sign the tokens';
     */
    protected $description = 'Set the JWT secret key used to sign the tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = Str::random(64);

        if ($this->option('show')) {
            $this->comment($key);

            return;
        }

        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayKey($key);
        }

        if (Str::contains(file_get_contents($path), 'JWT_SECRET') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL."JWT_SECRET=$key".PHP_EOL, FILE_APPEND);
        } else {
            if ($this->option('always-no')) {
                $this->comment('Secret key already exists. Skipping...');

                return;
            }

            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to your secret key.');

                return;
            }

            // update existing entry
            file_put_contents($path, str_replace(
                'JWT_SECRET='.$this->laravel['config']['jwt.secret'],
                'JWT_SECRET='.$key, file_get_contents($path)
            ));
        }

        $this->displayKey($key);
    }

    /**
     * Display the key.
     *
     * @param  string  $key
     * @return void
     */
    protected function displayKey($key)
    {
        $this->laravel['config']['jwt.secret'] = $key;

        // Original : $this->info("jwt-auth secret [$key] set successfully.");
        $this->info("jwt secret key [$key] set successfully.");
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->option('force') ? true : $this->confirm(
            'This will invalidate all existing tokens. Are you sure you want to override the secret key?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath().DIRECTORY_SEPARATOR.'.env';
        }

        return $this->laravel->basePath('.env');
    }
}