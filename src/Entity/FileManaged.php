<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 文件entity
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\FileManagedRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FileManaged
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $originFileName;

    /**
     * @var string
     * @ORM\Column(type="integer")
     */
    private $fileSize;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fileMime;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $filePath;

    /**
     * @var UserInterface
     * @ORM\ManyToOne (targetEntity="Teebb\CoreBundle\Entity\User")
     */
    private $author;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getOriginFileName(): string
    {
        return $this->originFileName;
    }

    /**
     * @param string $originFileName
     */
    public function setOriginFileName(string $originFileName): void
    {
        $this->originFileName = $originFileName;
    }

    /**
     * @return string
     */
    public function getFileSize(): string
    {
        return $this->fileSize;
    }

    /**
     * @param string $fileSize
     */
    public function setFileSize(string $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function getFileMime(): string
    {
        return $this->fileMime;
    }

    /**
     * @param string $fileMime
     */
    public function setFileMime(string $fileMime): void
    {
        $this->fileMime = $fileMime;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * @param UserInterface $author
     */
    public function setAuthor(UserInterface $author): void
    {
        $this->author = $author;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}