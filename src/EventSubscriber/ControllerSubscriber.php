<?php
namespace App\EventSubscriber;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ControllerSubscriber implements EventSubscriberInterface
{
    private $security;
    private $userRepo;
    private $manager;

    public function __construct(Security $security, UserRepository $userRepo, EntityManagerInterface $manager)
    {

        $this->security = $security;
        $this->userRepo = $userRepo;
        $this->manager = $manager;
    }
    public static function getSubscribedEvents()
    {

        return [
            KernelEvents::CONTROLLER => [
                ['processHomepage', 10]
            ],
        ];
    }
    public function processHomepage(ControllerEvent $event)
    {
        // get controller and route name
        $route = $event->getRequest()->get('_route');
        // get l'utilisateur acctuelle
        $user = $this->security->getUser();
        // verifier si l'utilisateur est connectée, et verifier son derniere connxion
        if ($user && $route == 'home') {
            $currentUser = $this->userRepo->find($user);
            // entity LastConnection related to the current user
            $userConnection = $currentUser->getLastConnection();
            $lastConnection = $userConnection->getUpdatedAt();
            $lastDay = $lastConnection->format('d-m-Y'); // ex. 27-07-2019
            $date = new \DateTime();
            $today = $date->format('d-m-Y');



        }
    }
}