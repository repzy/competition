<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Competition;
use App\Specification\MimeTypeSpecification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentService
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MimeTypeSpecification
     */
    private $mimeTypeSpecification;

    /**
     * AttachmentService constructor.
     * @param ParameterBagInterface $parameterBag
     * @param EntityManagerInterface $entityManager
     * @param MimeTypeSpecification $mimeTypeSpecification
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $entityManager,
        MimeTypeSpecification $mimeTypeSpecification
    ) {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
        $this->mimeTypeSpecification = $mimeTypeSpecification;
    }

    /**
     * @param Attachment $attachment
     */
    public function saveFile(Attachment $attachment): void
    {
        /** @var UploadedFile|null $file */
        $file = $attachment->getFile();
        $attachmentFolder = $this->parameterBag->get('attachment_folder');

        $file->move($attachmentFolder, $attachment->getFileName());
    }

    /**
     * @param Attachment $attachment
     */
    public function deleteFile(Attachment $attachment): void
    {
        $attachmentFolder = $this->parameterBag->get('attachment_folder');

        $fileSystem = new Filesystem();
        $fileSystem->remove($attachmentFolder . $attachment->getFileName());
    }

    /**
     * @param Competition $competition
     */
    public function manageAddedAttachments(Competition $competition): void
    {
        /** @var Attachment[] $addedAttachments */
        $addedAttachments = $competition->getAttachments()->getInsertDiff();

        foreach ($addedAttachments as $addedAttachment) {
            $mimeType = $addedAttachment->getFile()->getClientMimeType();

            if (!$this->mimeTypeSpecification->isSatisfiedBy($mimeType)) {
                $competition->removeAttachment($addedAttachment);
                continue;
            }

            $addedAttachment->setName(pathinfo($addedAttachment->getFile()->getClientOriginalName(), PATHINFO_FILENAME));
            $addedAttachment->setExtension($addedAttachment->getFile()->getClientOriginalExtension());
            $this->entityManager->persist($addedAttachment);
            $this->entityManager->flush();

            $this->saveFile($addedAttachment);
        }
    }

    /**
     * @param Competition $competition
     */
    public function manageRemovedAttachments(Competition $competition): void
    {
        /** @var Attachment[] $removedAttachments */
        $removedAttachments = $competition->getAttachments()->getDeleteDiff();

        foreach ($removedAttachments as $removedAttachment) {
            $this->deleteFile($removedAttachment);
        }
    }
}
