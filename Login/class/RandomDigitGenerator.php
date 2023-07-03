<?php

namespace App\Login\class;

class RandomDigitGenerator
{
    private $randomDigit;
    public function __construct()
    {
        $this->randomDigit = "";
        for ($i = 0; $i < 5; $i++) {
            $this->randomDigit .= rand(0, 9);
        }
    }
    public function get()
    {
        $number =str_split($this -> randomDigit);
        if(count($number) < 5 ){
            self::class;
            self::get();
        }else{
            return $this -> randomDigit;
        }
        
    }
}
