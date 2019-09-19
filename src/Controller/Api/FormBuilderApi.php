<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Formbuilder;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\FormbuilderRepository;
use Labstag\Handler\FormBuilderPublishingHandler;
use Symfony\Component\Routing\Annotation\Route;

class FormBuilderApi extends ApiControllerLib
{

    public function __construct(FormBuilderPublishingHandler $formbuilderPublishingHandler)
    {
        $this->formbuilderPublishingHandler = $formbuilderPublishingHandler;
    }

    public function __invoke(Formbuilder $data): Formbuilder
    {
        $this->formbuilderPublishingHandler->handle($data);

        return $data;
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrash")
     *
     * @param string $_format
     */
    public function trash(FormbuilderRepository $repository)
    {
        return $this->trashAction($repository);
    }

    /**
     * @Route("/api/formbuilders/trash", name="api_formbuildertrashdelete", methods={"DELETE"})
     *
     * @param string $_format
     */
    public function delete(FormbuilderRepository $repository)
    {
        return $this->deleteAction($repository);
    }

    /**
     * @Route("/api/formbuilders/restore", name="api_formbuilderrestore", methods={"POST"})
     *
     * @param string $_format
     */
    public function restore(FormbuilderRepository $repository)
    {
        return $this->restoreAction($repository);
    }

    /**
     * @Route("/api/formbuilders/empty", name="api_formbuilderempty", methods={"POST"})
     *
     * @param string $_format
     */
    public function vider(FormbuilderRepository $repository)
    {
        return $this->emptyAction($repository);
    }
}
