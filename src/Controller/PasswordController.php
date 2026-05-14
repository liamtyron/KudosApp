<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_pswd')]
    public function forgotPassword(Request $request,UserRepository $userRepository, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
       if($request->isMethod('POST')){
        $email = $request->request->get('email');
        $user = $userRepository->findOneBy(['email' =>$email]);
        //dd($email, $user);

        if(!$user){
            $this->addFlash('error', 'No account linked to this email chile');
            return $this->redirectToRoute('app_forgot_pswd');
        }

        $session->set('reset_user_id', $user->getId());
        return $this->redirectToRoute('app_update_pswd');
       }
        return $this->render('password/email.html.twig');

        
    }

    #[Route('/update-password', name: 'app_update_pswd')]
    public function updatePassword(Request $request, UserRepository $userRepository, RequestStack $requestStack, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager):Response
    {
        $session = $requestStack->getSession();
        $userId = $session->get('reset_user_id');

        if(!$userId){
            return $this->redirectToRoute('app_forgot_pswd');
        }

        $user = $userRepository->find($userId);
        $form = $this->createForm(UpdatePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();


            if($confirmPassword == $newPassword){
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                $entityManager->persist($user);
                $entityManager->flush();

                $session->remove('reset_user_id');

                $this->addFlash('success', 'Password updated! Please log in.');
                return $this->redirectToRoute('app_login');
            }
            else
                {$this->addFlash('errors', 'Passwords are not the same');
                    return $this->render('password/update_password.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
        }

                
        

        return $this->render('password/update_password.html.twig', [
            'form' => $form->createView(),
        ]);
     
        

    }

    
            

//     #[Route('/reset-password', name: 'app_reset_password')]
//     public function resetPassword(Request $request, SessionInterface $session, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
//     {
//         // get the verified user from session
//         $userId = $session->get('reset_user_id');

//         if (!$userId) {
//             return $this->redirectToRoute('app_forgot_password');
//         }

//         $user = $userRepository->find($userId);
//         $form = $this->createForm(PasswordType::class);
//         $form->handleRequest($request); // ← this must come before getData()

//         if ($form->isSubmitted() && $form->isValid()) {
//             $newPassword = $form->get('newPassword')->getData();
//             $confirmPassword = $form->get('confirmPassword')->getData();

//             if ($newPassword !== $confirmPassword) {
//                 $this->addFlash('error', 'Passwords do not match');
//                 return $this->render('password/reset.html.twig', [
//                     'form' => $form,
//                 ]);
//             }

//             // hash and save
//             $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
//             $entityManager->flush();

//             // clear session
//             $session->remove('reset_user_id');

//             $this->addFlash('success', 'Password updated! Please log in.');
//             return $this->redirectToRoute('app_login');
//         }

//         return $this->render('password/reset.html.twig', [
//             'form' => $form,
//         ]);
//     }
// }

    
        
}
