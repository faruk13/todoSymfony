<?php


namespace AppBundle\Controller;

use AppBundle\Manager\TodoManager;
use AppBundle\Manager\UserManager;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends Controller
{
    protected $userManager;
    protected $authenticationUtils;
    protected $todoManager;

    public function __construct(UserManager $userManager,AuthenticationUtils $authenticationUtils, TodoManager $todoManager)
    {
        $this->userManager = $userManager;
        $this->authenticationUtils= $authenticationUtils;
        $this->todoManager=$todoManager;
    }

    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'username'=> $this->getLoggedUser()->getUsername()));
    }


    /**
     * @Route("/register", name="register", methods={"GET", "POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse|Response
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('password', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Register'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #$user = $form->getData();
            $password= $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->userManager->register($user);
            $this->addFlash(
                'notice',
                'Hello user ' . $user->getUsername(). ' You are registered now!'
            );
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/profile/{username}", name="profile")
     * @param $username
     * @return Response
     */
    public function profileAction($username){

        $user_logged=$this->getLoggedUser();
        $todoCount=$this->todoManager->todoCount($username);
        #var_dump($todoCount);
       return $this->render('default/profile.html.twig', array(
           'username' => $user_logged->getUsername(),
           'email' => $user_logged->getEmail(),
           'todoCount'=> $todoCount
           ));
    }



    public function getLoggedUser()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->getUser();
    }

}
