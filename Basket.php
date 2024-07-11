<?php

class Basket
{
    private $products;
    private $deliveryCharges;
    private $offers;
    private $basket;

    public function __construct($products, $deliveryCharges, $offers)
    {
        $this->products = $products;
        $this->deliveryCharges = $deliveryCharges;
        $this->offers = $offers;
        $this->basket = [];
    }

    public function add($productCode)
    {
        if (isset($this->products[$productCode])) {
            $this->basket[] = $productCode;
        }
    }

    public function total()
    {
        $subtotal = 0;
        $productCount = array_count_values($this->basket);
        
        foreach ($productCount as $productCode => $count) {
            $subtotal += $this->products[$productCode] * $count;

            // Apply "buy one red widget, get the second half price" offer
            if ($productCode == 'R01' && $count > 1) {
                $subtotal -= floor($count / 2) * ($this->products[$productCode] / 2);
            }
        }

        $delivery = 0;
        if ($subtotal < 50) {
            $delivery = $this->deliveryCharges['under50'];
        } elseif ($subtotal < 90) {
            $delivery = $this->deliveryCharges['under90'];
        }

        return number_format($subtotal + $delivery, 2);
    }
}

// Example product catalogue, delivery charges, and offers
$products = [
    'R01' => 32.95,
    'G01' => 24.95,
    'B01' => 7.95
];

$deliveryCharges = [
    'under50' => 4.95,
    'under90' => 2.95,
    'over90' => 0
];

$offers = [
    'R01' => 'buy one get second half price'
];

// Create basket instance
$basket = new Basket($products, $deliveryCharges, $offers);

// Adding products
$basket->add('B01');
$basket->add('G01');

echo "Total: $" . $basket->total() . "\n"; // Total: $37.85

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('R01');

echo "Total: $" . $basket->total() . "\n"; // Total: $54.37

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('G01');

echo "Total: $" . $basket->total() . "\n"; // Total: $60.85

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('B01');
$basket->add('B01');
$basket->add('R01');
$basket->add('R01');
$basket->add('R01');

echo "Total: $" . $basket->total() . "\n"; // Total: $98.27
?>
