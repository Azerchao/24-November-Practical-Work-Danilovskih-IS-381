<?php
declare(strict_types=1);

interface ReportFormatterInterface
{
    public function format(array $data): string;
}

interface ReportHeaderInterface
{
    public const SEPARATOR = '----------------------------------------';

    public function getHeader(): string;
}

class HtmlFormatter implements ReportFormatterInterface
{
    public function format(array $data): string
    {
        $items = array_map(fn($i) => "  <li>{$i}</li>", $data);
        return "<ul>\n" . implode("\n", $items) . "\n</ul>";
    }
}

class CsvFormatter implements ReportFormatterInterface
{
    public function format(array $data): string
    {
        return implode(';', $data);
    }
}

class SalesReport implements ReportFormatterInterface, ReportHeaderInterface
{
    /** @param array<string,float> $sales продукт => сумма */
    public function __construct(private array $sales) {}

    public function getHeader(): string
    {
        return "Отчёт о продажах за текущий период";
    }

    // Если $data пуст, используются собственные данные отчёта
    public function format(array $data = []): string
    {
        $rows = $data ?: $this->sales;
        $lines = [];
        foreach ($rows as $product => $sum) {
            $lines[] = "Продукт: {$product} — сумма: {$sum} руб.";
        }
        return implode(PHP_EOL, $lines);
    }
}

function renderReport(ReportFormatterInterface $report, array $data = []): void
{
    if ($report instanceof ReportHeaderInterface) {
        echo $report->getHeader() . PHP_EOL;
        echo ReportHeaderInterface::SEPARATOR . PHP_EOL;
    }
    echo $report->format($data) . PHP_EOL . PHP_EOL;
}

// Демонстрация
$items = ['Яблоки', 'Груши', 'Сливы'];
renderReport(new HtmlFormatter(), $items);
renderReport(new CsvFormatter(), $items);

$sales = new SalesReport(['Ноутбук' => 75000, 'Мышь' => 1500]);
renderReport($sales); // тот же объект, переданный как ReportFormatterInterface

$header = $sales; // доступ через интерфейс ReportHeaderInterface
echo "Заголовок через ReportHeaderInterface: " . $header->getHeader() . PHP_EOL;
echo "SEPARATOR = '" . ReportHeaderInterface::SEPARATOR . "'" . PHP_EOL;
