<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Repository\AttachmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AttachmentController extends AbstractController
{
    /**
     * @var AttachmentRepository
     */
    private $attachmentRepository;

    /**
     * AttachmentController constructor.
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    /**
     * @Route("/attachment/{id}", name="attachment_download", requirements={"id": "\d+"})
     *
     * @param int $id
     * @return BinaryFileResponse
     */
    public function downloadAction(int $id): BinaryFileResponse
    {
        /** @var Attachment $attachment */
        $attachment = $this->attachmentRepository->find($id);

        if (!$attachment instanceof Attachment) {
            throw new NotFoundHttpException();
        }

        $folder = $this->getParameter('attachment_folder');

        return $this->file(
            $folder . $attachment->getFileName(),
            $attachment->getName() . '.' . $attachment->getExtension()
        );
    }
}
