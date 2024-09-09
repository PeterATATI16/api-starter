<p>Bonjour {{ $name }},</p>

<p>Bienvenue sur notre plateforme !</p>

@if ($password)
    <p>Voici vos identifiants pour vous connecter :</p>
    <ul>
        <li>Email: {{ $email }}</li>
        <li>Mot de passe: {{ $password }}</li>
    </ul>
    <p>Nous vous recommandons de changer ce mot de passe après votre première connexion.</p>
@endif

<p>Cordialement,</p>
<p>L'équipe</p>
