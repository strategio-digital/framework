<?php
declare(strict_types=1);

namespace Megio;

use Megio\Debugger\JsonLogstashLogger;
use Nette\DI\Compiler;
use Nette\Neon\Neon;
use Megio\Extension\Extension;
use Megio\Helper\Path;
use Nette\Bridges\DITracy\ContainerPanel;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Symfony\Component\Dotenv\Dotenv;
use Tracy\Debugger;
use Tracy\ILogger;

class Bootstrap
{
    protected bool $invokedLogger = false;
    
    public function projectRootPath(string $rootPath): Bootstrap
    {
        /** @var string $realPath */
        $realPath = realpath($rootPath);
        Path::setProjectPath($realPath);
        
        // Load environment variables
        $_ENV = array_merge(getenv(), $_ENV);
        $envPath = Path::wwwDir() . '/../.env';
        
        if (file_exists($envPath)) {
            $dotenv = new Dotenv();
            $dotenv->loadEnv($envPath);
        }
        
        return $this;
    }
    
    public function logger(ILogger $logger): Bootstrap
    {
        // Setup debugger
        Debugger::enable($_ENV['APP_ENVIRONMENT'] === 'develop' ? Debugger::Development : Debugger::Production, Path::logDir());
        Debugger::$strictMode = E_ALL;
        Debugger::setLogger($logger);
        
        if (array_key_exists('TRACY_EDITOR', $_ENV) && array_key_exists('TRACY_EDITOR_MAPPING', $_ENV)) {
            Debugger::$editor = $_ENV['TRACY_EDITOR'];
            Debugger::$editorMapping = ['/var/www/html' => $_ENV['TRACY_EDITOR_MAPPING']];
        }
        
        $this->invokedLogger = true;
        
        return $this;
    }
    
    /**
     * @param string $configPath
     * @param float $startedAt
     * @return Container
     */
    public function configure(string $configPath, float $startedAt): Container
    {
        if ($this->invokedLogger === false) {
            $this->logger(new JsonLogstashLogger());
        }
        
        date_default_timezone_set($_ENV['APP_TIME_ZONE']);
        
        // Create DI container
        $container = $this->createContainer($configPath);
        $container->parameters['startedAt'] = $startedAt;
        
        // Register Tracy DI panel
        $container->addService('tracy.bar', Debugger::getBar());
        Debugger::getBar()->addPanel(new ContainerPanel($container));
        
        // Initialize extensions
        if (method_exists($container, 'initialize')) {
            $container->initialize();
        }
        
        return $container;
    }
    
    /**
     * @param string $configPath
     * @return \Nette\DI\Container
     */
    protected function createContainer(string $configPath): Container
    {
        $loader = new ContainerLoader(Path::tempDir() . '/di', $_ENV['APP_ENVIRONMENT'] === 'develop');
        
        /** @var Container $class */
        $class = $loader->load(function (Compiler $compiler) use ($configPath) {
            // Load entry-point config
            $compiler->loadConfig($configPath);
            
            // Add "extensions" extension
            $compiler->addExtension('extensions', new Extension());
            
            // Register custom extensions
            $neon = Neon::decodeFile($configPath);
            if (array_key_exists('extensions', $neon) && $neon['extensions']) {
                foreach ($neon['extensions'] as $name => $extension) {
                    /** @var \Nette\DI\CompilerExtension $instance */
                    $instance = new $extension();
                    $compiler->addExtension($name, $instance);
                }
            }
        });
        
        return new $class;
    }
}