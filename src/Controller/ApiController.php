<?php
namespace App\Controller;
use App\Service\TjUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /** @var TjUserService $userService */
    private $userService;
    public function __construct(TjUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/api/getUserInfo/{id}")
     */
    public function getUserInfoAction(Request $request, $id)
    {
        $user = $this->userService->getFullUserInfo($id);
        return new JsonResponse($user);
    }
}