# code-manager-bundle
- installation
```
composer require kematjaya/code-manager-bundle
```
- update database schema
```
php bin/console doctrine:schema:update --force
```
- implement in your entity
```
...
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
...

class Transaction implements CodeLibraryClientInterface
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function getClassId():?string
    {
        return $this->getId();
    }
    
    /**
     * additional code
     */
    public function getLibrary():array
    {
        return [
            "foo" => "foo",
            "bar" => 'bar'
        ];
    }
    
    public function getGeneratedCode():?string
    {
        return $this->getCode();
    }
    
    public function setGeneratedCode(string $code): CodeLibraryClientInterface
    {
        return $this->setCode($code);
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): CreditInterface
    {
        $this->code = $code;

        return $this;
    }
}
```