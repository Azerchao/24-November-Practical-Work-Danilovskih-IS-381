<?php
declare(strict_types=1);

class Vehicle
{
    public function __construct(
        protected string $brand,
        protected int $maxSpeed
    ) {}

    public function getInfo(): string
    {
        return "Марка: {$this->brand}, макс. скорость: {$this->maxSpeed} км/ч";
    }
}

class Car extends Vehicle
{
    public function __construct(string $brand, int $maxSpeed, private string $fuelType)
    {
        parent::__construct($brand, $maxSpeed);
    }

    public function getInfo(): string
    {
        return parent::getInfo() . ", тип топлива: {$this->fuelType}";
    }
}

class Airplane extends Vehicle
{
    public function __construct(string $brand, int $maxSpeed, private float $wingspan)
    {
        parent::__construct($brand, $maxSpeed);
    }

    public function getInfo(): string
    {
        return parent::getInfo() . ", размах крыльев: {$this->wingspan} м";
    }
}

// Демонстрация
$vehicles = [
    new Car("Toyota", 200, "бензин"),
    new Airplane("Boeing 737", 850, 35.8),
];

foreach ($vehicles as $v) {
    echo $v->getInfo() . PHP_EOL;
    echo "  instanceof Vehicle: " . ($v instanceof Vehicle ? 'да' : 'нет') . PHP_EOL;
}
