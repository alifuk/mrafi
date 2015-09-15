<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

/**
 * A command console that creates users and stores them in the database.
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php app/console app:add-user
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php app/console app:add-user -vv
 *
 * See http://symfony.com/doc/current/cookbook/console/console_command.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class AddCategoryCommand extends ContainerAwareCommand {

    const MAX_ATTEMPTS = 5;

    /**
     * @var ObjectManager
     */
    private $em;

    protected function configure() {
        $this
                // a good practice is to use the 'app:' prefix to group all your custom application commands
                ->setName('app:add-category')
                ->setDescription('Creates category and stores it in the database')
                ->setHelp($this->getCommandHelp())
                // commands can optionally define arguments and/or options (mandatory and optional)
                // see http://symfony.com/doc/current/components/console/console_arguments.html
                ->addArgument('name', InputArgument::OPTIONAL, 'name')
                ->addArgument('urlName', InputArgument::OPTIONAL, 'visible url')
                ->addArgument('description', InputArgument::OPTIONAL, 'Short description of this template')
                ->addArgument('parent', InputArgument::OPTIONAL, 'Id of parent')
        ;
    }

    /**
     * This method is executed before the interact() and the execute() methods.
     * It's main purpose is to initialize the variables used in the rest of the
     * command methods.
     *
     * Beware that the input options and arguments are validated after executing
     * the interact() method, so you can't blindly trust their values in this method.
     */
    protected function initialize(InputInterface $input, OutputInterface $output) {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output) {
        if (null !== $input->getArgument('name') && null !== $input->getArgument('urlName') && null !== $input->getArgument('description') && null !== $input->getArgument('parent')
        ) {
            return;
        }

        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Add Category Command Interactive Wizard');
        $output->writeln('-----------------------------------');

        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln(array(
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php app/console app:add-user name urlName description parent',
            '',
        ));

        $output->writeln(array(
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
            '',
        ));

        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');

        // Ask for the username if it's not defined
        $name = $input->getArgument('name');
        if (null === $name) {
            $question = new Question(' > <info>Name</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The name cannot be empty');
                }

                return $answer;
            });
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $name = $console->ask($input, $output, $question);
            $input->setArgument('name', $name);
        } else {
            $output->writeln(' > <info>Name</info>: ' . $name);
        }

        // Ask for the username if it's not defined
        $urlName = $input->getArgument('urlName');
        if (null === $urlName) {
            $question = new Question(' > <info>urlName</info> or leave empty to use name as urlName: ');
            $urlName = $console->ask($input, $output, $question);
            $input->setArgument('urlName', $urlName);
        } else {
            $output->writeln(' > <info>urlName</info>: ' . $urlName);
        }

        // Ask for the username if it's not defined
        $description = $input->getArgument('description');
        if (null === $description) {
            $question = new Question(' > <info>description</info> optional: ');
            $description = $console->ask($input, $output, $question);
            $input->setArgument('description', $description);
        } else {
            $output->writeln(' > <info>description</info>: ' . $description);
        }

        // Ask for the username if it's not defined
        $parent = $input->getArgument('parent');
        if (null === $parent) {
            $question = new Question(' > <info>parent</info> optional: ');
            $parent = $console->ask($input, $output, $question);
            $input->setArgument('parent', $parent);
        } else {
            $output->writeln(' > <info>description</info>: ' . $parent);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $startTime = microtime(true);

        $name = $input->getArgument('name');
        $urlName = $input->getArgument('urlName');
        $description = $input->getArgument('description');
        $parent = $input->getArgument('parent');

        $parentObj = null;
        if (null !== $parent) {
            $parentObj = $this->em->getRepository('AppBundle:Category')->findOneBy(array('name' => $parent));
        }

        $existingCategory = $this->em->getRepository('AppBundle:Category')->findOneBy(array('name' => $name));

        if (null !== $existingCategory) {
            throw new \RuntimeException(sprintf('There is already a category "%s".', $name));
        }



        // create the user and encode its password
        $category = new Category();
        $category->setName($name);

        if (empty($urlName)) {
            $urlName = $name;
        }
        
        $output->writeln($parentObj->getId());
        
        $category->setUrlName($urlName);
        $category->setDescription($description);
        $category->setParent($parentObj->getId());

        $this->em->persist($category);
        $this->em->flush($category);

        $output->writeln('');
        $output->writeln(sprintf('[OK] %s was successfully created', $category->getName()));

        if ($output->isVerbose()) {
            $finishTime = microtime(true);
            $elapsedTime = $finishTime - $startTime;

            $output->writeln(sprintf('[INFO] New user database id: %d / Elapsed time: %.2f ms', $category->getId(), $elapsedTime * 1000));
        }
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function passwordValidator($plainPassword) {
        if (empty($plainPassword)) {
            throw new \Exception('The password can not be empty');
        }

        if (strlen(trim($plainPassword)) < 6) {
            throw new \Exception('The password must be at least 6 characters long');
        }

        return $plainPassword;
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function emailValidator($email) {
        if (empty($email)) {
            throw new \Exception('The email can not be empty');
        }

        if (false === strpos($email, '@')) {
            throw new \Exception('The email should look like a real email');
        }

        return $email;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp() {
        return <<<HELP
The <info>%command.name%</info> command creates new category and saves it in the database:

  <info>php %command.full_name%</info> <comment>name urlName description parent</comment>



HELP;
    }

}
