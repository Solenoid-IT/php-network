<?php



namespace Solenoid\Network;



class Host
{
    public string $value;



    # Returns [self]
    public function __construct (string $value)
    {
        // (Getting the value)
        $this->value = $value;
    }

    # Returns [Host]
    public static function select (string $value)
    {
        // Returning the value
        return new Host( $value );
    }



    # Returns [assoc]
    public function ping (int $num_tries = 1)
    {
        // (Getting the value)
        $v = escapeshellarg( $this->value );

        // (Pinging the IP)
        $result = shell_exec("ping -c $num_tries $v");



        // (Getting the value)
        preg_match( '/^([\d]+) packets transmitted, ([\d]+) received, ([\d]+)\% packet loss, time ([\d]+)ms$/mi', $result, $matches );



        // Returning the value
        return
            [
                'transmitted_packets' => (int) $matches[1],
                'received_packets'    => (int) $matches[2],
                'packet_loss'         => (int) $matches[3],
                'time'                => (int) $matches[4]
            ]
        ;
    }

    # Returns [assoc|false]
    public function resolve (?string $server = null)
    {
        // (Setting the value)
        $list = [];



        // (Getting the values)
        $domain = escapeshellarg( $this->value );
        $server = $server ? '@' . escapeshellarg( $server ) : '';



        // (Executing the command)
        $result = shell_exec("dig +noall +answer $domain any $server");

        if ( $result === null )
        {// (Unable to get the data)
            // Returning the value
            return false;
        }



        // (Getting the value)
        $records = explode( "\n", $result );

        foreach ($records as $i => $record)
        {// Processing each entry
            if ( $record === '' )
            {// Match OK
                // Continuing the iteration
                continue;
            }



            // (Getting the values)
            $parts     = preg_split( '/\s+/', $record );

            $host      = $parts[0];
            $host      = $host[ strlen( $host ) - 1 ] === '.' ? substr( $host, 0, strlen( $host ) - 1 ) : $host;

            $ttl       = (int) $parts[1];

            $class     = $parts[2];

            $type      = $parts[3];

            $data      = preg_match( '/[\d]+\s+IN\s+' . $type . '\s+(.+)/', $record, $matches ) === 1 ? $matches[1] : $parts[4];



            switch ( $type )
            {
                case 'A':
                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'  => $host,
                        'ttl'   => $ttl,
                        'class' => $class,
                        'type'  => $type,

                        'ip'    => $data
                    ]
                    ;
                break;

                case 'AAAA':
                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'  => $host,
                        'ttl'   => $ttl,
                        'class' => $class,
                        'type'  => $type,

                        'ip'    => $data
                    ]
                    ;
                break;

                case 'CNAME':
                    // (Getting the value)
                    $dst_host = $data;
                    $dst_host = $dst_host[ strlen( $dst_host ) - 1 ] === '.' ? substr( $dst_host, 0, strlen( $dst_host ) - 1 ) : $dst_host;



                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'     => $host,
                        'ttl'      => $ttl,
                        'class'    => $class,
                        'type'     => $type,

                        'dst_host' => $dst_host
                    ]
                    ;
                break;

                case 'NS':
                    // (Getting the value)
                    $ns = $data;
                    $ns = $ns[ strlen( $ns ) - 1 ] === '.' ? substr( $ns, 0, strlen( $ns ) - 1 ) : $ns;



                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'   => $host,
                        'ttl'    => $ttl,
                        'class'  => $class,
                        'type'   => $type,

                        'server' => $ns
                    ]
                    ;
                break;

                case 'SOA':
                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'   => $host,
                        'ttl'    => $ttl,
                        'class'  => $class,
                        'type'   => $type,

                        'data'   => $data
                    ]
                    ;
                break;

                case 'TXT':
                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'    => $host,
                        'ttl'     => $ttl,
                        'class'   => $class,
                        'type'    => $type,

                        'content' => trim( $data, " \n\r\t\v\0\"" )
                    ]
                    ;
                break;

                case 'MX':
                    // (Getting the values)
                    $mx_parts  = preg_split( '/\s+/', $data );

                    $mx_priority = (int) $mx_parts[0];
                    $mx_server = $mx_parts[1];
                    $mx_server = $mx_server[ strlen( $mx_server ) - 1 ] === '.' ? substr( $mx_server, 0, strlen( $mx_server ) - 1 ) : $mx_server;



                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'     => $host,
                        'ttl'      => $ttl,
                        'class'    => $class,
                        'type'     => $type,

                        'priority' => $mx_priority,
                        'server'   => $mx_server
                    ]
                    ;
                break;

                default:
                    # debug
                    #\Solenoid\Debug\Debugger::print_json( $parts, true );



                    // (Appending the value)
                    $list[ $type ][] =
                    [
                        'host'   => $host,
                        'ttl'    => $ttl,
                        'class'  => $class,
                        'type'   => $type,

                        'data'   => $data
                    ]
                    ;
            }
        }



        // Returning the value
        return $list;
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->value;
    }
}



?>