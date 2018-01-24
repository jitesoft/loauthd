<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  KeyGenerateCommand.php - Part of the lumen-doctrine-oauth2 project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Commands;

use Illuminate\Console\Command;
use Jitesoft\Exceptions\IO\FileException;
use phpseclib\Crypt\RSA;

class KeyGenerateCommand extends Command {

    protected $signature = "oauth:key:gen";

    protected $description = "Generates the public and private keys used by OAuth.";

    public function handle() {
        $this->info('Generating OAuth public and private keys.');

        $path = storage_path('/oauth');

        if (file_exists($path . '/private.key') || file_exists($path . '/public.key')) {
            throw new FileException('OAuth keys already exist.');
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $rsa = new RSA();

        $keys = $rsa->createKey(2048);

        file_put_contents($path . '/private.key', $keys['privatekey']);
        file_put_contents($path . '/public.key', $keys['publickey']);

        $this->info('Successfully generated key pair.');
    }

}
