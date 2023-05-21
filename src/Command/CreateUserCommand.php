<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a user',
)]
class CreateUserCommand extends Command
{

    function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if ($email and $password) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
            $this->userRepository->save($user, true);
            $io->success(sprintf('You have created a user with email %s', $email));
        }else {
            $io->error('You need to provide an email and a password');
        }


        return Command::SUCCESS;
    }
}
