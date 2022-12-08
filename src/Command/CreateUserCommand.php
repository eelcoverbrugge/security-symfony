<?php

namespace App\Command;

use App\Entity\SecurityUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'CreateUserCommand',
    description: 'This command creates an user based on email (username) and password',
    aliases: ['app:create-user'],
    hidden: false,
)]
class CreateUserCommand extends Command
{
    private SecurityUser $securityUser;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SecurityUser $securityUser,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $entityManager,
    )
    {
        $this->securityUser = $securityUser;
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->securityUser->setEmail($input->getArgument('email'));
        $this->securityUser->setPassword($this->hasher->hashPassword($this->securityUser, $input->getArgument('password')));
        $this->entityManager->persist($this->securityUser);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('User created!');

        return Command::SUCCESS;
    }
}
