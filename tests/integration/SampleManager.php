<?php declare(strict_types=1);

namespace Rackspace\Integration;

class SampleManager extends \OpenStack\Integration\SampleManager
{
    protected function getGlobalReplacements()
    {
        return [
            '{username}' => getenv('RS_USERNAME'),
            '{apiKey}'   => getenv('RS_API_KEY'),
            '{region}'   => getenv('RS_REGION'),
        ];
    }

    protected function getConnectionTemplate()
    {
        if ($this->verbosity === 1) {
            $subst = <<<'EOL'
use Rackspace\Integration\DefaultLogger;
use Rackspace\Integration\Utils;
use GuzzleHttp\MessageFormatter;

$options = [
    'debugLog'         => true,
    'logger'           => new DefaultLogger(),
    'messageFormatter' => new MessageFormatter(),
];
$rackspace = new Rackspace\Rackspace(Utils::getAuthOpts($options));
EOL;
        } elseif ($this->verbosity === 2) {
            $subst = <<<'EOL'
use Rackspace\Integration\DefaultLogger;
use Rackspace\Integration\Utils;
use GuzzleHttp\MessageFormatter;

$options = [
    'debugLog'         => true,
    'logger'           => new DefaultLogger(),
    'messageFormatter' => new MessageFormatter(MessageFormatter::DEBUG),
];
$rackspace = new Rackspace\Rackspace(Utils::getAuthOpts($options));
EOL;
        } else {
            $subst = <<<'EOL'
use Rackspace\Integration\Utils;

$rackspace = new Rackspace\Rackspace(Utils::getAuthOpts());
EOL;
        }

        return $subst;
    }

    public function write($path, array $replacements)
    {
        $replacements = array_merge($this->getGlobalReplacements(), $replacements);

        $sampleFile = rtrim($this->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;

        if (!file_exists($sampleFile) || !is_readable($sampleFile)) {
            throw new \RuntimeException(sprintf("%s either does not exist or is not readable", $sampleFile));
        }

        $content = strtr(file_get_contents($sampleFile), $replacements);
        $content = str_replace("'vendor/'", "'" . dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "vendor'", $content);

        $subst = $this->getConnectionTemplate();
        $content = preg_replace('/\([^)]+\)/', '', $content, 1);
        $content = str_replace('$rackspace = new Rackspace\Rackspace;', $subst, $content);

        $tmp = tempnam(sys_get_temp_dir(), 'rackspace');
        file_put_contents($tmp, $content);

        $this->paths[] = $tmp;

        return $tmp;
    }
}