<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ShiftLogFiles.
 *
 * @Vich\Uploadable
 */
class ShiftLogFiles
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $file_name;

    /**
     * @Vich\UploadableField(mapping="shiftlog_file", fileNameProperty="file_name")
     *
     * @var File
     */
    protected $file;

    /**
     * Set file_name.
     *
     * @param string $fileName
     *
     * @return ShiftLogFiles
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $file;
    }

    public function getFile()
    {
        return $this->file;
    }
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return ShiftLogFiles
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
