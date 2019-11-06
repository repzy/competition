<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Competition;
use App\Entity\Distance;
use App\Specification\MimeTypeSpecification;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var SessionInterface
     */
    private $session;

    /**
     * AttachmentService constructor.
     * @param ParameterBagInterface $parameterBag
     * @param EntityManagerInterface $entityManager
     * @param MimeTypeSpecification $mimeTypeSpecification
     * @param SessionInterface $session
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $entityManager,
        MimeTypeSpecification $mimeTypeSpecification,
        SessionInterface $session
    ) {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
        $this->mimeTypeSpecification = $mimeTypeSpecification;
        $this->session = $session;
    }

    /**
     * @param Attachment $attachment
     */
    public function saveFile(Attachment $attachment): void
    {
        /** @var UploadedFile|null $file */
        $file = $attachment->getFile();
        $attachmentFolder = $this->parameterBag->get('attachment_folder');

        $fileSystem = new Filesystem();
        $fileSystem->mkdir($attachmentFolder);

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
     * @param Competition|Distance $entity
     */
    public function manageAddedAttachments($entity): void
    {
        if ($entity->getAttachments() instanceof PersistentCollection) {
            $addedAttachments = $entity->getAttachments()->getInsertDiff();
        } elseif ($entity->getAttachments() instanceof ArrayCollection) {
            $addedAttachments = $entity->getAttachments();
        } else {
            throw new \InvalidArgumentException();
        }

        foreach ($addedAttachments as $addedAttachment) {
            $mimeType = $addedAttachment->getFile()->getClientMimeType();

            if (!$this->mimeTypeSpecification->isSatisfiedBy($mimeType)) {
                $entity->removeAttachment($addedAttachment);
                $this->session->getFlashBag()->add('error', 'Сервіс не підтримує заватнаження файлів з розширенням '. $addedAttachment->getFile()->getExtension());
                continue;
            }

            $addedAttachment->setName(pathinfo($addedAttachment->getFile()->getClientOriginalName(), PATHINFO_FILENAME));
            $addedAttachment->setExtension($addedAttachment->getFile()->getClientOriginalExtension());
            $this->entityManager->persist($addedAttachment);
            $this->entityManager->flush($addedAttachment);

            $this->saveFile($addedAttachment);
        }
    }

    /**
     * @param Competition|Distance $entity
     */
    public function manageRemovedAttachments($entity): void
    {
        /** @var Attachment[] $removedAttachments */
        $removedAttachments = $entity->getAttachments()->getDeleteDiff();

        foreach ($removedAttachments as $removedAttachment) {
            $this->deleteFile($removedAttachment);
        }
    }
}
