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



    # Returns [self]
    public function allow (string $range)
    {
        // (Appending the value)
        $this->whitelist[] = $range;

        // Returning the value
        return $this;
    }

    # Returns [self]
    public function deny (string $range)
    {
        // (Appending the value)
        $this->blacklist[] = $range;

        // Returning the value
        return $this;
    }



    # Returns [bool]
    public function pass (string $ip)
    {
        // (Getting the value)
        $ip = IPv4::select( $ip );



        foreach ( $this->blacklist as $range )
        {// Processing each entry
            if ( Range::select( $range )->contains_ip( $ip ) )
            {// Match failed
                // Returning the value
                return false;
            }
        }



        foreach ( $this->whitelist as $range )
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
}



?>