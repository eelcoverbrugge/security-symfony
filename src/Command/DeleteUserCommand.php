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
    name: 'DeleteUserCommand',
    description: 'This command deletes an user based on email (username) and password',
    aliases: ['app:delete-user'],
    hidden: false,
)]
class DeleteUserCommand extends Command
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
        $user = $this->entityManager->getRepository(SecurityUser::class)->findOneBy(['email' => $input->getArgument('email')]);

        if (!$user) {
            throw new \Exception(sprintf('No user found with email: "%s"', $input->getArgument('email')));
        }

        // @2do:
        // Delete videos from user if exist
        // Check if password is correct before deleting a user

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('User deleted!');

        return Command::SUCCESS;
    }
}
