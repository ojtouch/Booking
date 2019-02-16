<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Gérer la connexion et le formulaire de login.
     *
     * @Route("/login", name="account_login")
     *
     * @param AuthenticationUtils $utils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
        ]);
    }

    /**
     * Deconnexion.
     *
     * @Route("/logout", name="account_logout")
     *
     * @return Response
     */
    public function logout()
    {
    }

    /**
     * Afficher le formulaire d'inscription.
     *
     * @Route("/register", name="account_register")
     *
     * @param Request                      $request
     * @param ObjectManager                $manager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vous avez bien été enregistré, vous pouvez vous connecter'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView(),
            'username' => $user->getUsername(),
        ]);
    }

    /**
     * Modification de profil.
     *
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile()
    {
        return $this->render('account/profile.html.twig', [
        ]);
    }
}
