<?php
declare(strict_types=1);

trait JsonExportTrait
{
    public function export(): string
    {
        return json_encode(get_object_vars($this), JSON_UNESCAPED_UNICODE);
    }

    public function getFormat(): string
    {
        return "JSON";
    }
}

trait XmlExportTrait
{
    public function export(): string
    {
        $xml = "<item>";
        foreach (get_object_vars($this) as $key => $value) {
            $xml .= "<{$key}>" . htmlspecialchars((string)$value) . "</{$key}>";
        }
        return $xml . "</item>";
    }

    public function getFormat(): string
    {
        return "XML";
    }
}

trait PriceTrait
{
    public function getPriceWithTax(float $tax): float
    {
        return $this->price * (1 + $tax / 100);
    }
}

class Product
{
    use JsonExportTrait, XmlExportTrait, PriceTrait {
        JsonExportTrait::export insteadof XmlExportTrait;
        XmlExportTrait::export as exportToXml;

        JsonExportTrait::getFormat insteadof XmlExportTrait;
        XmlExportTrait::getFormat as getXmlFormat;
        JsonExportTrait::getFormat as getJsonFormat;
    }

    public function __construct(
        public string $name,
        public float $price
    ) {}

    /*
     * Будет вызвана именно эта реализация: приоритет такой — метод самого
     * класса > метод трейта > унаследованный метод родителя. Поэтому
     * getFormat() вернёт "Product format", а версии из трейтов остаются
     * доступны через псевдонимы getJsonFormat() и getXmlFormat().
     */
    public function getFormat(): string
    {
        return "Product format";
    }
}

// Демонстрация
$p = new Product("Ноутбук", 1000.0);

echo "JSON: " . $p->export() . PHP_EOL;
echo "XML:  " . $p->exportToXml() . PHP_EOL;
echo "getFormat():     " . $p->getFormat() . PHP_EOL;      // метод класса
echo "getJsonFormat(): " . $p->getJsonFormat() . PHP_EOL;  // из JsonExportTrait
echo "getXmlFormat():  " . $p->getXmlFormat() . PHP_EOL;   // из XmlExportTrait
echo "Цена с налогом 20%: " . $p->getPriceWithTax(20) . PHP_EOL;
