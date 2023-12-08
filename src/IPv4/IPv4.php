<?php



namespace Solenoid\Network\IPv4;



class IPv4
{
    public string $address;



    # Returns [self]
    public function __construct (string $address)
    {
        // (Getting the value)
        $this->address = $address;
    }

    # Returns [IPv4]
    public static function select (string $address)
    {
        // Returning the value
        return new IPv4( $address );
    }



    # Returns [array<Range>]
    public function match_ranges (array $ranges)
    {
        // (Setting the value)
        $results = [];

        foreach ($ranges as $range)
        {// Processing each entry
            if ( $range->contains_ip( $this ) )
            {// Match OK
                // (Appending the value)
                $results[] = $range;
            }
        }



        // Returning the value
        return $results;
    }



    # Returns [int|false]
    public function to_int ()
    {
        // Returning the value
        return ip2long( $this->address );
    }



    # Returns [bool]
    public function is_valid ()
    {
        // Returning the value
        return $this->to_int() !== false;
    }



    # Returns [string]
    public function __toString()
    {
        // Returning the value
        return $this->address;
    }
}



?>