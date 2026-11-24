<?php
declare(strict_types=1);

trait LoggerTrait
{
    private array $logs = [];

    public function log(string $message): void
    {
        $this->logs[] = '[' . date('Y-m-d H:i:s') . '] ' . $message;
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }
}

/*
 * Трейт удобнее наследования: UserService и OrderService логически не связаны
 * («пользователь» не является «заказом»), поэтому общий родитель был бы
 * искусственным. К тому же в PHP только одно наследование — оно могло бы
 * понадобиться для реальной иерархии. Трейт даёт общую логику логирования
 * без этих ограничений, а у каждого класса остаётся свой отдельный $logs.
 */
class UserService
{
    use LoggerTrait;

    public function register(string $username): void
    {
        $this->log("Зарегистрирован пользователь: {$username}");
    }
}

class OrderService
{
    use LoggerTrait;

    public function createOrder(int $orderId, float $total): void
    {
        $this->log("Создан заказ №{$orderId} на сумму {$total} руб.");
    }
}

function printLogs(string $title, array $logs): void
{
    echo "== {$title} ==" . PHP_EOL;
    echo $logs ? implode(PHP_EOL, $logs) . PHP_EOL : "(пусто)" . PHP_EOL;
}

// Демонстрация
$users = new UserService();
$orders = new OrderService();

$users->register("alice");
$users->register("bob");
$orders->createOrder(101, 2500.50);
$orders->createOrder(102, 990.00);

printLogs("Журнал UserService", $users->getLogs());
printLogs("Журнал OrderService", $orders->getLogs());

$users->clearLogs();
echo PHP_EOL . "После очистки журнала UserService:" . PHP_EOL;
printLogs("Журнал UserService", $users->getLogs());
printLogs("Журнал OrderService", $orders->getLogs());
