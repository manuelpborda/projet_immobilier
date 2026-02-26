<?php

return [
    // Núcleo Symfony
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],

    // Doctrine ORM (MySQL) + Migraciones
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],

    // MongoDB ODM (NoSQL)
    Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle::class => ['all' => true],

    // Depuración / Herramientas dev
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],

    // Logs
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],

    // Vista y utilidades
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],

    // Seguridad
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],

    // Front-end
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],

    // UX (Stimulus + Turbo)
    Symfony\UX\StimulusBundle\StimulusBundle::class => ['all' => true],
    Symfony\UX\Turbo\TurboBundle::class => ['all' => true],

    // Datos de prueba / seeds
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],

    // Paginación
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => ['all' => true],
];
