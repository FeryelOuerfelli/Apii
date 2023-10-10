<?php

namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, private UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        // 2) do we have a matching user by email?
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($googleUser->getEmail());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "patient"));
            $user->setRoles(['ROLE_PATIENT']);
            $user->setNom($googleUser->getFirstName());
            $user->setPrenom($googleUser->getLastName());
            $user->setImage($googleUser->getAvatar());
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            $this->em->persist($user);
            $this->em->flush();
        }
        // 3) Maybe you just want to "register" them by creating
        // a User object
        return $user;
    }

    private function getGoogleClient()
    {
        return $this->clientRegistry
            // "facebook_main" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('google');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        /* $user = $token->getUser();
        if(in_array('ROLE_MEDECIN',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_medecin_profile'));
        }else{
            return new RedirectResponse($this->urlGenerator->generate('app_user_profile'));
        }*/
        return new RedirectResponse($this->urlGenerator->generate('app_user_home'));
        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    // ...
}
