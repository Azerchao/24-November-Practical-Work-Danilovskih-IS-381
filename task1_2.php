<?php
declare(strict_types=1);

/*
 * Нельзя создать объект Employee напрямую, потому что класс объявлен как
 * abstract: он задаёт лишь общий шаблон, а метод calculateSalary() не имеет
 * реализации. Объект с «недостающим» методом был бы неполным, поэтому PHP
 * запрещает new Employee(...) — нужно создавать наследников, реализующих
 * все абстрактные методы.
 */
abstract class Employee
{
    public function __construct(
        protected string $name,
        protected float $baseSalary
    ) {}

    abstract public function calculateSalary(): float;

    public function getName(): string
    {
        return $this->name;
    }
}

class Manager extends Employee
{
    public function calculateSalary(): float
    {
        return $this->baseSalary * 1.20;
    }
}

class Developer extends Employee
{
    private int $overtimeHours = 0;

    public function setOvertimeHours(int $hours): void
    {
        $this->overtimeHours = $hours;
    }

    public function calculateSalary(): float
    {
        return $this->baseSalary + $this->overtimeHours * 500;
    }
}

class Intern extends Employee
{
    public function calculateSalary(): float
    {
        return $this->baseSalary * 0.5;
    }
}

function printPayroll(array $employees): void
{
    $total = 0.0;
    foreach ($employees as $e) {
        $salary = $e->calculateSalary();
        $total += $salary;
        printf("%-12s — %10.2f руб.\n", $e->getName(), $salary);
    }
    printf("Общий фонд оплаты труда: %.2f руб.\n", $total);
}

// Демонстрация
$dev = new Developer("Пётр", 80000);
$dev->setOvertimeHours(10);

printPayroll([
    new Manager("Анна", 100000),
    $dev,
    new Intern("Иван", 40000),
]);
