<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  KeyGenerateCommand.php - Part of the loauthd project.

  © - Jitesoft 2018
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\Loauthd\Commands;

use Illuminate\Console\Command;
use Jitesoft\Exceptions\IO\DirectoryException;
use Jitesoft\Exceptions\IO\FileException;
use phpseclib\Crypt\RSA;
use function rtrim;

class KeyGenerateCommand extends Command {

    protected $signature = "oauth:key:gen {path?} {--force?}";

    protected $description = "Generates the public and private keys used by GrantHelper.";

    /**
     * @throws FileException
     * @throws DirectoryException
     */
    public function handle() {
        $this->info('Generating GrantHelper public and private keys.');

        $path = $this->hasArgument('path') ? $this->argument('path') : storage_path('/oauth');
        $path = rtrim($path, '\/');
        $this->info(sprintf('%s %s', 'Trying to write files to', $path));

        $privateKeyPath = sprintf('%s/%s', $path, 'private.key');
        $publicKeyPath  = sprintf('%s/%s', $path, 'public.key');

        if (!$this->hasOption('force')) {
            if (file_exists($privateKeyPath)) {
                throw new FileException('Private key already exist.');
            }

            if (file_exists($publicKeyPath)) {
                throw new FileException('Public key already exist.');
            }
        }

        if (!is_dir($path)) {
            if (!mkdir($path, 0700, true)) {
                throw new DirectoryException('Failed to create directory.', $path);
            }
        }

        $rsa  = new RSA();
        $keys = $rsa->createKey(2048, false);

        file_put_contents($privateKeyPath, $keys['privatekey']);
        file_put_contents($publicKeyPath, $keys['publickey']);

        $result = chmod($privateKeyPath, 0600);
        $result = $result && chmod($publicKeyPath, 0600);

        if (!$result) {
            $this->error('Failed to set mode on the public and private key. Please change keys access mode to 0600.');
        }

        $this->info(sprintf('Successfully generated key pair at %s.', $path));
    }

}
