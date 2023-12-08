<?php



namespace Solenoid\Network\IPv4;



use \Solenoid\Network\IPv4\IPv4;



class Range
{
    public string $value;

    public IPv4 $start;
    public IPv4 $end;



    # Returns [self]
    public function __construct (string $value)
    {
        // (Getting the values)
        $parts          = explode( '/', $value );

        $network        = $parts[0];
        $mask_bits      = isset( $parts[1] ) ? (int) $parts[1] : 32;

        $netmask        = implode( '.', array_map( function ($octet) { return bindec( $octet ); }, str_split( str_repeat( '1', $mask_bits ) . str_repeat( '0', 32 - $mask_bits ), 8 ) ) );

        $network_binary = implode( '', array_map( function ($octet) { return str_pad( decbin( $octet ), 8, '0', STR_PAD_LEFT ); }, explode( '.', $network ) ) );
        $netmask_binary = implode( '', array_map( function ($octet) { return str_pad( decbin( $octet ), 8, '0', STR_PAD_LEFT ); }, explode( '.', $netmask ) ) );



        // (Setting the value)
        $start_binary = '';

        for ($i = 0; $i < 32; $i++)
        {// Iterating each index
            // (Appending the value)
            $start_binary .= $network_binary[ $i ] === '1' && $netmask_binary[ $i ] === '1' ? '1' : '0';
        }

        // (Getting the value)
        $start = implode( '.', array_map( function ($octet) { return bindec( $octet ); }, str_split( $start_binary, 8 ) ) );



        // (Getting the value)
        $netmask_inverse_binary = '';

        for ($i = 0; $i < 32; $i++)
        {// Iterating each index
            // (Appending the value)
            $netmask_inverse_binary .= $netmask_binary[ $i ] === '1' ? '0' : '1';
        }



        // (Setting the value)
        $end_binary = '';

        for ($i = 0; $i < 32; $i++)
        {// Iterating each index
            // (Appending the value)
            $end_binary .= ( $start_binary[ $i ] === '0' && $netmask_inverse_binary[ $i ] === '0' ) ? '0' : '1';
        }

        // (Getting the value)
        $end = implode( '.', array_map( function ($octet) { return bindec( $octet ); }, str_split( $end_binary, 8 ) ) );



        // (Getting the values)
        $this->value = $value;

        $this->start = IPv4::select( $start );
        $this->end   = IPv4::select( $end );
    }

    # Returns [Range]
    public static function select (string $value)
    {
        // Returning the value
        return new Range( $value );
    }



    # Returns [bool]
    public function contains_ip (IPv4 $ip)
    {
        // (Getting the values)
        $start   = $this->start->to_int();
        $current = $ip->to_int();
        $end     = $this->end->to_int();



        // Returning the value
        return $current >= $start && $current <= $end;
    }



    # Returns [assoc]
    public function to_array ()
    {
        // Returning the value
        return
        [
            'start' => $this->start->address,
            'end'   => $this->end->address
        ]
        ;
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->value;
    }
}



?>