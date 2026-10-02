# code-manager-bundle
Generate kode otomatis (mis. nomor invoice) untuk entity Doctrine saat flush.

Branch `6.4` mendukung Symfony 6.4, PHP >= 8.1, Doctrine ORM 2.14+ / 3.x.

- installation
```
composer require kematjaya/code-manager-bundle
```
- update database schema (bundle menyediakan entity `CodeLibrary` dan `CodeLibraryLog`, tabel `code_library` dan `code_library_log`)
```
php bin/console doctrine:schema:update --force
```
- tambahkan format kode untuk entity, mis. `INV/{number}/{YYYY}` dengan separator `/`
```php
use Kematjaya\CodeManagerBundle\Entity\CodeLibrary;

$library = (new CodeLibrary())
    ->setClassName(Transaction::class)
    ->setFormat('INV/{number}/{YYYY}')
    ->setSeparator('/')
    ->setLength(4)          // panjang {number}, default 4
    ->setResetKey('{YYYY}'); // opsional: sequence kembali ke 1 jika nilai {YYYY} berubah
$em->persist($library);
$em->flush();
```
- implement in your entity
```php
use Doctrine\ORM\Mapping as ORM;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;

#[ORM\Entity]
class Transaction implements CodeLibraryClientInterface
{
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $code = null;

    public function getClassId(): ?string
    {
        return null === $this->getId() ? null : (string) $this->getId();
    }

    /**
     * additional code, dipakai di format sebagai {foo} dan {bar}
     */
    public function getLibrary(): array
    {
        return [
            "foo" => "foo",
            "bar" => 'bar'
        ];
    }

    public function getGeneratedCode(): ?string
    {
        return $this->code;
    }

    public function setGeneratedCode(string $code): CodeLibraryClientInterface
    {
        $this->code = $code;

        return $this;
    }
}
```
- memakai repository sendiri: override alias di `config/services.yaml`
```yaml
Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface:
    alias: App\Repository\CodeLibraryRepository
Kematjaya\CodeManager\Repository\CodeLibraryLogRepositoryInterface:
    alias: App\Repository\CodeLibraryLogRepository
```

## Test
```
LOCAL_PACKAGES="code-manager" sh ../test.sh code-manager-bundle all
EXTRA_REQUIRE="doctrine/orm:^2.14" sh ../test.sh code-manager-bundle 8.1 6.4
```
