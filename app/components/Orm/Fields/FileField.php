<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 13/04/16 08:11
 */

namespace Phact\Orm\Fields;

use Phact\Exceptions\InvalidAttributeException;
use Phact\Helpers\FileHelper;
use Phact\Storage\Files\StorageFile;
use Phact\Storage\Files\File;
use Phact\Storage\Files\FileInterface;
use Phact\Storage\Files\LocalFile;
use Phact\Storage\Storage;
use Phact\Storage\StorageInterface;

class FileField extends CharField
{
    public $rawGet = false;

    public $rawSet = true;
    /**
     * @var bool, encrypt filename to md5 hash
     */
    public $md5Name = false;

    /**
     * %Module - Module object class of model field. For example: Catalog
     * %Model - Model object class. For example: Product
     * %Y  Year on server, for example 2016
     * %m  month on server, for example 05
     * %d  day on server, for example 03
     * %H  hour, example 11
     * %i  minutes, example 01
     * %s  sec, example 11
     * @var string/function, upload template directory:
     */
    public $templateUploadDir = '%Module/%Model/%Y-%m-%d';

    /** @var null|string storage type. Default FileSystemStorage */
    public $storage = 'storage';

    /**
     * Delete old file on after model delete or set new file
     * @var bool
     */
    public $deleteOld = true;

    /** @var Storage */
    protected $_storage;

    /** @var  string upload directory for field */
    protected $_uploadDir;

    public function __construct(StorageInterface $storage)
    {
        $this->_storage = $storage;
    }

    /**
     * @return Storage
     * @throws \Phact\Exceptions\InvalidConfigException
     */
    public function getStorage()
    {
        return $this->_storage;
    }


    /**
     * @return string|null
     */
    public function getUrl()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            return $this->getStorage()->getUrl($this->getAttribute()->path);
        }
        return null;
    }

    /**
     * @return string|null full path to file
     */
    public function getPath()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            return $this->getStorage()->getPath($this->getAttribute()->path);
        }
        return null;
    }

    /**
     * @return string|null file name
     */
    public function getPathFilename()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            $path = $this->getStorage()->getPath($this->getAttribute()->path);
            return FileHelper::mbPathinfo($path, PATHINFO_FILENAME);
        }
        return null;
    }

    /**
     * @return string|null basename of file
     */
    public function getPathBasename()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            $path = $this->getStorage()->getPath($this->getAttribute()->path);
            return FileHelper::mbPathinfo($path, PATHINFO_BASENAME);
        }
        return null;
    }

    /**
     * @return string extension of file
     */
    public function getExtension()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            return $this->getStorage()->getExtension($this->getAttribute()->getPath());
        }
        return null;
    }

    /**
     * @return string extension of file
     */
    public function getSize()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            return $this->getStorage()->getSize($this->getAttribute()->getPath());
        }
        return null;
    }

    /**
     * @return null|bool if success delete
     */
    public function delete()
    {
        if (is_a($this->getAttribute(), FileInterface::class)) {
            return $this->getStorage()->delete($this->getAttribute()->getPath());
        }
        return null;
    }

    /**
     * Directory for upload related to storage
     *
     * @return mixed|null|string
     */
    public function getUploadDir()
    {
        if (is_null($this->_uploadDir)) {
            if (is_callable($this->templateUploadDir)) {
                $result = call_user_func($this->templateUploadDir, $this);
                if (is_string($result)) {
                    return $result;
                }
            }

            $uploadTo = strtr($this->templateUploadDir, [
                '%Y' => date('Y'),
                '%m' => date('m'),
                '%d' => date('d'),
                '%H' => date('H'),
                '%i' => date('i'),
                '%s' => date('s'),
                '%Model' => $this->getModel()->classNameShort(),
                '%Module' => $this->getModel()->getModuleName(),
            ]);

            $this->_uploadDir = rtrim($uploadTo);

            if ($this->_uploadDir) {
                $this->_uploadDir .= DIRECTORY_SEPARATOR;
            }
        }
        return $this->_uploadDir ?: null;
    }

    /**
     * Delete old file
     */
    public function deleteOld()
    {
        if (!$this->deleteOld) {
            return false;
        }
        /** @var FileInterface|null $old */
        $old = $this->getOldAttribute();
        if (is_a($old, FileInterface::class)) {
            $path = $old->getPath();
            if ($path) {
                return $this->getStorage()->delete($path);
            }
        }
        return false;
    }

    /**
     * @param $value string db value
     * @return null|StorageFile
     */
    public function attributePrepareValue($value)
    {
        if ($value) {
            $value = new StorageFile($value, $this->storage);
        }
        return $value;
    }

    /**
     * @param File|string|null $value
     * @param null $aliasConfig
     * @return mixed|void
     * @throws InvalidAttributeException
     */
    public function setValue($value, $aliasConfig = NULL)
    {
        if (is_null($value) || !$value) {
            $this->attribute = null;
        }

        if ($value instanceof StorageFile) {
            if (!$value->equalsTo($this->getAttribute())) {
                $this->attribute = $this->saveStorageFile($value);
            }
        }

        if (is_string($value) && file_exists($value) && is_readable($value)) {
            $value = new LocalFile($value);
        }


        if ($value instanceof File) {
            $this->attribute = $this->saveFile($value);
        }
        
        return $this->getAttribute();
    }

    /**
     * Prepare attribute for database
     *
     * @param $value StorageFile|null
     * @return string
     */
    public function dbPrepareValue($value)
    {
        if ($value instanceof StorageFile) {
            return $value->path;
        } else {
            return $value;
        }
    }

    /**
     * Prepare name
     *
     * @param FileInterface $file
     * @return string
     */
    public function getFileName(FileInterface $file)
    {
        $name = $file->getName();
        if ($this->md5Name) {
            $name = md5($name . uniqid()) . '.' . $file->getExt();
        }

        return $name;
    }

    /**
     * @param null $aliasConfig
     * @return mixed
     */
    public function getValue($aliasConfig = NULL)
    {
        return $this;
    }

    /**
     * @param File $file
     * @return StorageFile|false
     */
    protected function saveFile(File $file)
    {
        $uploadDir = $this->getUploadDir();
        $name = $this->getFileName($file);
        $path = $this->getStorage()->save($uploadDir . $name, $file);
        return ($path) ? new StorageFile($path, $this->getStorage()) : false;
    }

    /**
     * @param StorageFile $file
     * @return StorageFile|false
     */
    protected function saveStorageFile(StorageFile $file)
    {
        $uploadDir = $this->getUploadDir();
        $name = $this->getFileName($file);
        /** @var StorageFile $file */
        $fileStorage = $this->getStorage()->copyStorageFile($uploadDir . $name, $file);
        return $fileStorage;

    }

    public function afterDelete()
    {
        $this->deleteOld();
        parent::afterDelete();
    }

    public function afterSave()
    {
        if (is_null($this->getAttribute())) {
            $this->deleteOld();
        } elseif ($this->getOldAttribute() instanceof StorageFile && !$this->getOldAttribute()->equalsTo($this->getAttribute())) {
            $this->deleteOld();
        }

        parent::afterSave();
    }

    public function getFormField()
    {
        return $this->setUpFormField([
            'class' => \Phact\Form\Fields\FileField::class
        ]);
    }

    public function __toString()
    {
        return (string) $this->getUrl();
    }
}