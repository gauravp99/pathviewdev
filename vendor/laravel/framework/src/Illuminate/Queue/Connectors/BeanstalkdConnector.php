<?php namespace Illuminate\Queue\Connectors;

use Illuminate\Queue\BeanstalkdQueue;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;

class BeanstalkdConnector implements ConnectorInterface
{

    /**
     * Establish a queue connection.
     *
     * @param  array $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $pheanstalk = new Pheanstalk($config['host'], array_get($config, 'port', PheanstalkInterface::DEFAULT_PORT));

        return new BeanstalkdQueue(
            $pheanstalk, $config['queue'], array_get($config, 'ttr', Pheanstalk::DEFAULT_TTR)
        );
    }

}
