<?php

namespace AppBundle\Controller;
use AppBundle\Manager\TodoManager;
use AppBundle\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    private $todoManager;

    public function __construct(TodoManager $todoManager)
    {
        $this->todoManager = $todoManager;
    }


    /**
     * @Route("profile/{username}/newtodo", name="newtodo")
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function newAction(Request $request)
    {
        $current_user=$this->getUser()->getUsername();
        $todo = new Todo();

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('priority', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Todo'])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();

            $todo->setByUser($current_user);

            $this->todoManager->create($todo);

            $this->addFlash(
                'notice',
                'Added todo with name '.$todo->getName()
            );
            return $this->redirectToRoute('display', array('username'=> $current_user));
        }
        return $this->render('default/newtodo.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/profile/{username}/display", name="display")
     * @param $username
     * @return Response
     */
    public function displayAction($username)
    {
        $user_logged_username=$this->getUser()->getUsername();

        if($username != $user_logged_username){
            $this->addFlash('notice',"Can't access other user's todo list!");
            return $this->redirectToRoute('display', array('username' => $user_logged_username));
        }

        $todos=$this->todoManager->show($username);

        return $this->render('default/display.html.twig', array(
            'todos' => $todos,
            'username' => $username));

    }

    /**
     * @Route("/profile/{username}/delete/{id}", name="Todo_Delete")
     * @param $id
     * @param $username
     * @return Response
     */
    public function deleteAction($id, $username){
        $deletedTodoName= $this->todoManager->delete($id, $username);
        $this->addFlash(
            'notice',
            'Deleted todo with name '.$deletedTodoName
        );
        return $this->redirectToRoute('display', array('username' => $username));
    }
}
