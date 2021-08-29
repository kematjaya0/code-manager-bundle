<?php

/**
 * This file is part of the code-manager-bundle.
 */

namespace Kematjaya\CodeManagerBundle\Manager;

use Kematjaya\CodeManagerBundle\Entity\CodeLibraryAttribute;
use Kematjaya\CodeManager\Entity\CodeManagerClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryClientInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryResetInterface;
use Kematjaya\CodeManager\Entity\CodeLibraryInterface;
use Kematjaya\CodeManager\Manager\CodeManager as BaseCodeManager;
use Kematjaya\CodeManager\Exception\CodeLibraryNotFoundException;
use Kematjaya\CodeManager\Exception\NotSupportedResetKeyException;
use Kematjaya\CodeManagerBundle\Repository\CodeLibraryAttributeRepository;
use Kematjaya\CodeManager\Repository\CodeLibraryRepositoryInterface;
use Kematjaya\CodeManager\Builder\AbstractCodeBuilder;
use Kematjaya\CodeManager\Manager\CodeLibraryLogManagerInterface;

/**
 * @package Kematjaya\CodeManagerBundle\Manager
 * @license https://opensource.org/licenses/MIT MIT
 * @author  Nur Hidayatullah <kematjaya0@gmail.com>
 */
class CodeManager extends BaseCodeManager
{
    /**
     * 
     * @var CodeLibraryAttributeRepository
     */
    private $codeLibraryAttributeRepository;
    
    /**
     * 
     * @var CodeLibraryRepositoryInterface
     */
    private $codeLibraryRepo;
    
    /**
     * 
     * @var AbstractCodeBuilder
     */
    private $codeBuilder;
    
    /**
     * 
     * @var CodeLibraryLogManagerInterface
     */
    private $codeLibraryLogManager;
    
    public function __construct(CodeLibraryAttributeRepository $codeLibraryAttributeRepository, AbstractCodeBuilder $codeBuilder, CodeLibraryRepositoryInterface $codeLibraryRepo, CodeLibraryLogManagerInterface $codeLibraryLogManager) 
    {
        $this->codeBuilder = $codeBuilder;
        $this->codeLibraryRepo = $codeLibraryRepo;
        $this->codeLibraryAttributeRepository = $codeLibraryAttributeRepository;
        $this->codeLibraryLogManager = $codeLibraryLogManager;
        parent::__construct($codeBuilder, $codeLibraryRepo, $codeLibraryLogManager);
    }
    
    /**
     * Generate code and save into object and create log
     * 
     * @param CodeLibraryClientInterface $client
     * @return CodeLibraryClientInterface
     * @throws \Exception
     */
    public function generate(CodeLibraryClientInterface $client): CodeLibraryClientInterface
    {
        if (!$client instanceof CodeManagerClientInterface) {
            
            return parent::generate($client);
        }
        
        if (empty($client->getAdditionalConditions())) {
            
            return parent::generate($client);
        }
        
        $codeLibrary = $this->codeLibraryRepo->findOneByClient($client);
        if (!$codeLibrary) {
            throw new CodeLibraryNotFoundException($client);
        }
        
        $attribute = $this->codeLibraryAttributeRepository->findByAdditionals($codeLibrary, $client->getAdditionalConditions());
        
        if ($codeLibrary instanceof CodeLibraryResetInterface) {
            $attribute = $this->resetCode($client, $codeLibrary, $attribute);
        }
        
        $lastSequence = $attribute->getLastSequence() ? $attribute->getLastSequence() : 0;
        $code = $this->codeBuilder->generate($codeLibrary->getFormat(), $client, $codeLibrary->getSeparator());
        $number = $this->generateNumber($lastSequence, null !== $codeLibrary->getLength() ? $codeLibrary->getLength() : 4);
        $completeCode = str_replace(self::REGEX_NUMBER, $number, $code);
        $client->setGeneratedCode($completeCode);
        $this->updateCodeLibraryAttribute($attribute, $number, $completeCode);
        $this->codeLibraryLogManager->createLog($client);
        
        return $client;
    }
    
    protected function resetCode(CodeLibraryClientInterface $client, CodeLibraryResetInterface $codeLibrary, CodeLibraryAttribute $codeLibraryAttribute): CodeLibraryAttribute
    {
        if (null === $codeLibrary->getResetKey()) {
            return $codeLibrary;
        }
        
        if (!$this->codeBuilder->isSupported($codeLibrary->getResetKey())) {
            throw new NotSupportedResetKeyException($codeLibrary);
        }
        
        $strpos = strpos($codeLibrary->getFormat(), $codeLibrary->getResetKey());
        if (false === $strpos) {
            throw new \Exception(sprintf("format key '%s' not found inside format '%s'", $codeLibrary->getResetKey(), $codeLibrary->getFormat()));
        }
        
        
        if (null === $codeLibraryAttribute->getLastCode()) {
            
            return $codeLibraryAttribute;
        }
        
        $library = array_merge($this->codeBuilder->getLibrary(), $client->getLibrary());
        $lastCodes = $this->exploded($codeLibraryAttribute);
        $formats = array_flip(explode($codeLibrary->getSeparator(), $codeLibrary->getFormat()));
        $key = isset($formats[$codeLibrary->getResetKey()]) ? $formats[$codeLibrary->getResetKey()] : null;
        if (!$key) {
            
            throw new \Exception(sprintf('key not found: %s', $codeLibrary->getResetKey()));
        }
        
        $lastValue = $lastCodes[$key];
        $actualValue = $library[$this->codeBuilder->getFormatValue($codeLibrary->getResetKey())];
        if ($lastValue === $actualValue) {
            
            return $codeLibraryAttribute;
        }
        
        $codeLibraryAttribute->setLastSequence(0);
        
        return $codeLibraryAttribute;
    }
    
    protected function exploded(CodeLibraryAttribute $codeLibraryAttribute):array
    {
        $codeLibrary = $codeLibraryAttribute->getCodeLibrary();
        $lastCodes = explode($codeLibrary->getSeparator(), $codeLibraryAttribute->getLastCode());
        if (count($lastCodes) > 1) {
            
            return $lastCodes;
        }
        
        foreach ([CodeLibraryInterface::SEPARATOR_BACKSLASH, CodeLibraryInterface::SEPARATOR_MINUS, CodeLibraryInterface::SEPARATOR_SLASH] as $separator) {
            $lastCodes = explode($separator, $codeLibraryAttribute->getLastCode());
            if (count($lastCodes) > 1) {

                return $lastCodes;
            }
        }
        
        return [];
    }
    
    protected function updateCodeLibraryAttribute(CodeLibraryAttribute $codeLibrary, string $number, string $code):void
    {
        $codeLibrary->setLastCode($code)
                ->setLastSequence((int)$number);
        
        $this->codeLibraryAttributeRepository->save($codeLibrary);
    }
}
