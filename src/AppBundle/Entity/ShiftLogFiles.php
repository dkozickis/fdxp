<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * ShiftLogFiles.
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
     * @var File $file
     */
    protected $file;

    /**
     * Set file_name
     *
     * @param string $fileName
     * @return ShiftLogFiles
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setFile($file) {

        $this->file = $file;

        return $file;
    }

    public function getFile() {
        return $this->file;
    }
}
