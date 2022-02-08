<?php
namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Hashids\Hashids;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PlaceholderImageService {

    private string $saveDirectory;
    private UniqIdentifierGeneratorInterface $generator;
    private string $placeholderServiceProviderUrl = "https://via.placeholder.com/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;

    /**
     * @param FilenameGenerator $generator
     */
    public function __construct(UniqIdentifierGeneratorInterface $generator, Hashids $hashid) {
        $this->generator = $generator;
        $this->hashids = $hashid;
    }

    /**
     * Set the upload main directory
     * @param ParameterBagInterface|string $directory
     * @return void
     */
    #[Required]
    public function setUploadDirectory(ParameterBagInterface|string $directory): void {
        if ($directory instanceof ParameterBagInterface) {
            $this->saveDirectory = $directory->get("upload.directory");
        }
        else {
            $this->saveDirectory = $directory;
        }
    }

    /**
     * Return the downloaded image contents
     * @param int $imageWidth
     * @param int $imageHeight
     * @return string
     * @throws Error
     */
    public function getNewImageStream(int $imageWidth, int $imageHeight): string {
        if ($imageWidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("The requested image format is too small, please provide us a larger format");
        }
        $contents = file_get_contents("{$this->placeholderServiceProviderUrl}/{$imageWidth}x{$imageHeight}");
        if (!$contents) {
            throw new Error("Placeholder image cannot be downloaded");
        }
        return $contents;
    }

    /** Download a new placeholder image and save it into the filesystem
     * @param int $imageWidth
     * @param int $imageHeight
     * @param string $filename
     * @return bool
     * @throws Error
     */
    public function getNewImageAndSave(int $imageWidth, int $imageHeight): bool {
        $file = $this->saveDirectory . $this->generator->generate();
        $contents = $this->getNewImageStream($imageWidth, $imageHeight);
        $bytes = file_put_contents($file, $contents);
        return file_exists($file) && $bytes;
    }
}