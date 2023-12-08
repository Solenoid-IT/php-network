<?php



namespace Solenoid\Network;



class MAC
{
    public string $address;



    # Returns [self]
    public function __construct (string $address)
    {
        // (Getting the value)
        $this->address = $address;
    }

    # Returns [MAC]
    public static function select (string $address)
    {
        // Returning the value
        return new MAC( $address );
    }



    # Returns [MAC]
    public function normalize ()
    {
        // (Getting the value)
        $address = preg_replace( '/[^a-zA-Z0-9]/', '', $this->address );
        $address = str_split( $address, 2 );
        $address = implode( ':', $address );
        $address = strtoupper($address);



        // Returning the value
        return MAC::select( $address );
    }

    # Returns [MAC]
    public function sum (int $value)
    {
        // (Getting the values)
        $mac_value = str_replace( ':', '', $this->address );
        $mac_value = hexdec($mac_value);

        $value     = $mac_value + $value;
        $value     = dechex($value);
        $value     = str_pad( $value, 12, '0', STR_PAD_LEFT );
        $value     = str_split( $value, 2 );
        $value     = implode( ':', $value );
        $value     = strtoupper( $value );



        // Returning the value
        return MAC::select( $value );
    }



    # Returns [string]
    public function __toString ()
    {
        // Returning the value
        return $this->address;
    }
}



?>