<?php
declare(strict_types=1);

interface PaymentInterface
{
    public function pay(float $amount): bool;
    public function refund(float $amount): bool;
    public function getMethodName(): string;
}

class CreditCard implements PaymentInterface
{
    public function __construct(private float $limit) {}

    public function pay(float $amount): bool
    {
        return $amount > 0 && $amount <= $this->limit;
    }

    public function refund(float $amount): bool
    {
        return $amount > 0;
    }

    public function getMethodName(): string
    {
        return "Кредитная карта";
    }
}

class PayPal implements PaymentInterface
{
    public function __construct(private float $balance) {}

    public function pay(float $amount): bool
    {
        if ($amount <= 0 || $amount > $this->balance) {
            return false;
        }
        $this->balance -= $amount;
        return true;
    }

    public function refund(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }
        $this->balance += $amount;
        return true;
    }

    public function getMethodName(): string
    {
        return "PayPal";
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}

class Cash implements PaymentInterface
{
    public function pay(float $amount): bool
    {
        return true;
    }

    public function refund(float $amount): bool
    {
        return true;
    }

    public function getMethodName(): string
    {
        return "Наличные";
    }
}

function checkout(PaymentInterface $method, float $amount): void
{
    $ok = $method->pay($amount);
    echo "[{$method->getMethodName()}] Оплата {$amount} руб.: "
        . ($ok ? "успешно" : "отказ") . PHP_EOL;
}

// Демонстрация
$card = new CreditCard(5000);
checkout($card, 3000);   // успех
checkout($card, 9000);   // превышен лимит

$paypal = new PayPal(1000);
checkout($paypal, 700);  // успех, баланс 300
checkout($paypal, 700);  // недостаточно средств
$paypal->refund(700);
echo "Баланс PayPal после возврата: {$paypal->getBalance()}" . PHP_EOL;
checkout($paypal, 700);  // снова успех

// Наличные — всегда успешно; «неуспешным» может быть только некорректный
// сценарий вне контракта, поэтому показываем две успешные операции.
$cash = new Cash();
checkout($cash, 100);
checkout($cash, 1000000);
