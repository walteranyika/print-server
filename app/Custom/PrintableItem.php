<?php

namespace App\Custom;

class PrintableItem
{
   private $name;
   private $price;
   private $quantity;

    /**
     * @param $name
     * @param $price
     * @param $quantity
     */
    public function __construct($name, $price, $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getTotal()
    {
       return $this->quantity*$this->price;
    }

    public function getPrintatbleRow()
    {
        $name = mb_strimwidth(strtoupper($this->name),0,24,'.');
        $string = str_pad((int)$this->quantity." x", 5,' ');
        $string .= str_pad($name, 25,' ');
        $string .= str_pad($this->price, 9, ' ',STR_PAD_LEFT);
        $string .= str_pad($this->price*$this->quantity, 9,' ',STR_PAD_LEFT);
        return $string."\n";
    }
    public function getPrintatbleRowMod()
    {
        $name = mb_strimwidth(strtoupper($this->name),0,24,'.');
        $string = str_pad($this->quantity, 5,' ');
        $string .= str_pad($name, 25,' ');
        $string .= str_pad($this->price, 9, ' ',STR_PAD_LEFT);
        $string .= str_pad($this->price*$this->quantity, 9,' ',STR_PAD_LEFT);
        return $string."\n";
    }


}
