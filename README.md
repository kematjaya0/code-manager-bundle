# Code Manager Bundle

Bundle Symfony untuk integrasi dengan library [kematjaya/code-manager](https://github.com/kematjaya0/code-manager).

Generate kode otomatis (misal nomor invoice, transaksi) untuk entity Doctrine saat event `preFlush`.

Mendukung Symfony 7.4, PHP >= 8.4, dan Doctrine ORM 2.14+ / 3.x.

## Instalasi

```bash
composer require kematjaya/code-manager-bundle
```

## Update Schema Database

Bundle ini menyediakan entity `CodeLibrary` dan `CodeLibraryLog` (tabel Doctrine):

```bash
php bin/console doctrine:schema:update --force
```

## Penggunaan pada Entity

Implementasikan `Kematjaya\CodeManager\Entity\CodeLibraryClientInterface` pada entity Anda:

```php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

#[ORM\Entity]
class Transaction implements CodeLibraryClientInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassId(): ?string
    {
        return null === $this->id ? null : (string) $this->id;
    }

    /**
     * Parameter dinamis untuk format kode (mis. {branch}, dsb)
     */
    public function getLibrary(): array
    {
        return [];
    }

    public function getGeneratedCode(): ?string
    {
        return $this->code;
    }

    public function setGeneratedCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
```

Saat entity di-persist dan di-flush tanpa `generatedCode`, `CodeManagerListener` akan secara otomatis men-generate kode sesuai konfigurasi `CodeLibrary` di database.
