<?php


namespace AlexVenga\Application;


use Symfony\Component\Yaml\Yaml;
use AlexVenga\Application\Exceptions\SocketException;


class App
{

    /**
     * Address of the config file
     *
     * @var string
     */
    protected $configFileName;

    /**
     * Config from file $configFileName
     *
     * @var array
     */
    protected $config;

    /**
     * Main socket
     *
     * @var resource
     */
    protected $mainSocket;

    /**
     * Flag need stop server
     *
     * @var bool
     */
    protected $needStopServer;

    /**
     * Array of child processes pid
     *
     * @var array
     */
    protected $childProcesses;

    /**
     * App constructor.
     *
     * @param $configFileName string
     */
    public function __construct($configFileName)
    {
        $this->configFileName = $configFileName;
        $this->needStopServer = false;
        $this->childProcesses = [];
    }

    /**
     * Main Application
     *
     * @throws SocketException
     */
    public function run(): void
    {

        $this->loadConfig();

        $this->connectSocket();

        while (!$this->needStopServer) {

            if (($messageSocket = socket_accept($this->mainSocket)) === false) {
                throw new SocketException(sprintf(
                        'socket_accept() failed: reason: %s',
                        socket_strerror(socket_last_error($this->mainSocket)))
                );
            }



        }

    }


    /**
     * @throws SocketException
     */
    protected function connectSocket(): void
    {

        if (($this->mainSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            throw new SocketException(sprintf(
                    'socket_create() failed: reason: %s',
                    socket_strerror(socket_last_error()))
            );
        }

        if (socket_bind($this->mainSocket, $this->config['ip'], $this->config['port']) === false) {
            throw new SocketException(sprintf(
                    'socket_bind() failed: reason: %s',
                    socket_strerror(socket_last_error($this->mainSocket)))
            );
        }

        if (socket_listen($this->mainSocket, 5) === false) {
            throw new SocketException(sprintf(
                    'socket_listen() failed: reason: %s',
                    socket_strerror(socket_last_error($this->mainSocket)))
            );
        }
    }

    /**
     * Load YAML config from $this->configFileName file
     */
    protected function loadConfig(): void
    {
        $this->config = Yaml::parseFile($this->configFileName);
    }

}