<?php



namespace Solenoid\Network\IPv4;



use \Solenoid\Network\IPv4\Range;



class Firewall
{
    public array $blacklist;
    public array $whitelist;



    # Returns [self]
    public function __construct (array $blacklist = [], array $whitelist = [])
    {
        // (Getting the values)
        $this->blacklist = $blacklist;
        $this->whitelist = $whitelist;
    }

    # Returns [Firewall]
    public static function create (array $blacklist = [], array $whitelist = [])
    {
        // Returning the value
        return new Firewall( $blacklist, $whitelist );
    }



    # Returns [bool]
    public function check (string $ip)
    {
        // (Getting the value)
        $ip = IPv4::select( $ip );



        foreach ($this->blacklist as $range)
        {// Processing each entry
            if ( Range::select( $range )->contains_ip( $ip ) )
            {// Match failed
                // Returning the value
                return false;
            }
        }



        foreach ($this->whitelist as $range)
        {// Processing each entry
            if ( Range::select( $range )->contains_ip( $ip ) )
            {// Match OK
                // Returning the value
                return true;
            }
        }



        if ( $this->whitelist )
        {// Value is not empty
            // Returning the value
            return false;
        }



        // Returning the value
        return true;
    }



    # Returns [assoc]
    public function to_array ()
    {
        // Returning the value
        return get_object_vars( $this );
    }
}



?>