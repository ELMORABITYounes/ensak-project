<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrHCours", type="integer",nullable=true)
     */
    private $nbrHCours;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrHTd", type="integer", nullable=true)
     */
    private $nbrHTd;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Module
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     *
     * @Vich\UploadableField(mapping="cahier_de_charges", fileNameProperty="cahierName", size="cahierSize")
     *
     * @var File
     */
    private $cahierFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $cahierName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $cahierSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set nbrHCours
     *
     * @param integer $nbrHCours
     *
     * @return Module
     */
    public function setNbrHCours($nbrHCours)
    {
        $this->nbrHCours = $nbrHCours;

        return $this;
    }

    /**
     * Get nbrHCours
     *
     * @return int
     */
    public function getNbrHCours()
    {
        return $this->nbrHCours;
    }

    /**
     * Set nbrHTd
     *
     * @param string $nbrHTd
     *
     * @return Module
     */
    public function setNbrHTd($nbrHTd)
    {
        $this->nbrHTd = $nbrHTd;

        return $this;
    }

    /**
     * Get nbrHTd
     *
     * @return string
     */
    public function getNbrHTd()
    {
        return $this->nbrHTd;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $pdf
     * @throws \Exception
     */
    public function setCahierFile(File $pdf = null)
    {
        $this->cahierFile = $pdf;

        if (null !== $pdf) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCahierFile()
    {
        return $this->cahierFile;
    }

    /**
     * Set pdfName
     *
     * @param string $pdfName
     *
     * @return Module
     */
    public function setCahierName($pdfName)
    {
        $this->cahierName = $pdfName;

        return $this;
    }

    /**
     * Get pdfName
     *
     * @return string
     */
    public function getCahierName()
    {
        return $this->cahierName;
    }

    /**
     * Set pdfSize
     *
     * @param integer $pdfSize
     *
     * @return Module
     */
    public function setCahierSize($pdfSize)
    {
        $this->cahierSize = $pdfSize;

        return $this;
    }

    /**
     * Get pdfSize
     *
     * @return integer
     */
    public function getCahierSize()
    {
        return $this->cahierSize;
    }

}

