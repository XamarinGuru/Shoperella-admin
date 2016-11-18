<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Vendor;
use AppBundle\Entity\Deal;

class SeedCommand extends ContainerAwareCommand
{
  protected function configure() {
    $helpText = 'The <info>%command.name%</info> command seeds the app with' .
                " demo data.\n\n<info>php %command.full_name%";
    $this->setName('app:db:seed')
         ->setDescription('Seeds app with default data')
         ->setHelp($helpText);
  }

	protected function execute(InputInterface $input, OutputInterface $output) {
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
    // user
    $user = new User();
    $user->setName('Emperor Tamarin');
    $user->setEmail('test@monkeyjoes.com');
    $entityManager->persist($user);
    $entityManager->flush();
    // vendor 1
    $panera = new Vendor();
    $panera->setName('Panera Bread');
    $panera->setStreet1('2573 Richmond Road');
    $panera->setCity('Lexington');
    $panera->setState('KY');
    $panera->setZip('40509');
    $panera->setOwner($user);
    $entityManager->persist($panera);
    $entityManager->flush();
    // vendor 1 deals
    $drinkdeal = new Deal();
    $drinkdeal->setVendor($panera);
    $drinkdeal->setTitle('FREE DRINK');
    $drinkdeal->setCaption('with purchase of Pick 2');
    $drinkdeal->setDescription('Free drink with the purchase of Pick 2.');
    $drinkdeal->setDailyDeal(true);
    $entityManager->persist($drinkdeal);
    $entityManager->flush();
    // vendor 2
    $macys = new Vendor();
    $macys->setName("Macy's");
    $macys->setStreet1('3301 Nicholasville Rd');
    $macys->setCity('Lexington');
    $macys->setState('KY');
    $macys->setZip('40503');
    $macys->setOwner($user);
    $entityManager->persist($macys);
    $entityManager->flush();
    // vendor 2 deals
    $discount = new Deal();
    $discount->setVendor($macys);
    $discount->setTitle('20% OFF');
    $discount->setCaption('your purchase of $100');
    $discount->setDescription('20% off your purchase of $100 or more.');
    $discount->setDailyDeal(true);
    $entityManager->persist($discount);
    $entityManager->flush();
    // vendor 3
    $regal = new Vendor();
    $regal->setName('Regal Cinemas');
    $regal->setStreet1('1949 Star Shoot Pkwy');
    $regal->setCity('Lexington');
    $regal->setState('KY');
    $regal->setZip('40509');
    $regal->setOwner($user);
    $entityManager->persist($regal);
    $entityManager->flush();
    // vendor 3 deals
    $drinkshare = new Deal();
    $drinkshare->setVendor($regal);
    $drinkshare->setTitle('FREE LARGE DRINK');
    $drinkshare->setCaption('with purchase of 2 tickets');
    $drinkshare->setDescription('Free large drink with the purchase of 2 tickets.');
    $drinkshare->setDailyDeal(true);
    $entityManager->persist($drinkshare);
    $entityManager->flush();
  }
}
?>
