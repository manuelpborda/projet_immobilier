<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;

    // Inyecto las dependencias: el generador de rutas y el repositorio de usuarios
    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userRepository = $userRepository;
    }

    // Este método se ejecuta cuando alguien envía el formulario de login
    public function authenticate(Request $request): Passport
    {
        // Capturo el email enviado desde el formulario
        $email = $request->request->get('email', '');

        // Guardo el email en sesión por si el login falla
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // Construyo el "pasaporte" con email, contraseña y tokens de seguridad
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(), // Para permitir "recordarme"
            ]
        );
    }

    // Si el login es exitoso, redirijo al usuario según su tipo
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var \App\Entity\User $user */
        $user = $token->getUser();

        // Redirecciono según el valor del campo "typeUser"
        switch ($user->getTypeUser()) {
            case 'admin':
                return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
            case 'proprietaire':
                return new RedirectResponse($this->urlGenerator->generate('proprietaire_dashboard'));
            case 'client':
                return new RedirectResponse($this->urlGenerator->generate('client_dashboard'));
            default:
                // Por si acaso el tipo no está definido, lo envío al home
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
        }
    }

    // Esta función indica cuál es la ruta del formulario de login
    public function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
